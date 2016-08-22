<?php

/**
* Oltranz payment gw
*/
class PaymentGateWay
{
		// Submit payment and handle response
	public function processPayment( $order_id ) {
		global $woocommerce;

		// Retrieve phone 
		$phonenumber = $_POST["phonenumber"];
        $phonenumber = $this->sanatizePhone($phonenumber);

        if (strlen($phonenumber) <> 12) {
        	throw new Exception("Error Processing Request:".$phonenumber, 1);
        	
        }

        // GENERATE TRANSACTION ID FOR THIS ORDER
        $transactionId = $this->generateTransactionId();

		// Get this Order's information so that we know
		// who to charge and how much
		$customer_order = new WC_Order( $order_id );


		// IF ORDER AMOUNT IS 0 WE DON'T HAVE TO PROCEED WE CAN CONFIRM THE ORDER JUST HERE
		if($customer_order->order_total <= 0){
			// Payment has been successful because it is 0
			$customer_order->add_order_note( __('We are confirming order because customer has nothing to pay. Total is 0 ', 'spyr-tigocash' ) );
			$customer_order->payment_complete();

			// Redirect to thank you page
			return array(
				'result'   => 'success',
				'redirect' => $this->get_return_url( $customer_order ),
			);
		}

		// Are we testing right now or is it a real transaction
		$environment = ( $this->environment == "yes" ) ? 'TRUE' : 'FALSE';

		// Decide which URL to post to
		$environment_url = $environment == 'TRUE'  ?  $this->oltranz_backend_url_pro :  $this->oltranz_backend_url;

		if ($environment_url == null || empty($environment_url)) {
			$environment_url = 'http://10.171.1.53:8080/PaymentGateway/payments/paymentRequestv2';
		}

		$paymentspid = '3382';
		// Determine the payment service provider to be used
		switch (substr($phonenumber,0,5)) {
			case '25072': // TIGO RWANDA
				$paymentspid = $this->paymentspid_tigo;
				break;
			case '25078': // MTN RWANDA
				$paymentspid = $this->paymentspid_mtn;
				break;
			case '25073': // AIRTEL RWANDA
				$paymentspid = $this->paymentspid_airtel;
				break;
			default:
				$paymentspid = '3382';
				break;
		}

		// This is where the fun stuff begins
		// Prepare initial payload
		$payload = '<COMMAND>
						<CONTRACTID>PARAM_CONTRACTID</CONTRACTID>
						<PAYINGACCOUNTIDATSP>PARAM_MSISDN</PAYINGACCOUNTIDATSP>
						<PAYMENTSPID>PARAM_PAYMENTSPID</PAYMENTSPID>
						<DESCR>PARAM_DESCRIPTION</DESCR>
						<TRANSID>PARAM_TRANSACTIONID</TRANSID>
						<AMOUNT>PARAM_AMOUNT</AMOUNT>
					</COMMAND>';

		// Make the request contains the realy parameters
		$payload =  str_replace('PARAM_CONTRACTID',$this->contractid,$payload);
		$payload =  str_replace('PARAM_MSISDN',$phonenumber,$payload);
		$payload =  str_replace('PARAM_PAYMENTSPID',$paymentspid,$payload);
		$payload =  str_replace('PARAM_TRANSACTIONID',$transactionId,$payload);
		$payload =  str_replace('PARAM_DESCRIPTION','Billing '.$customer_order->billing_first_name,$payload);	
		$payload =  str_replace('PARAM_AMOUNT',$customer_order->order_total,$payload);	
				
		// Send this payload to Oltranz for processing
		$response = wp_remote_post( $environment_url, array(
			'method'    => 'POST',
			'body'      =>  $payload,
			'timeout'   => 90,
			'sslverify' => false,
		) );
		
		// PREPARE TRANSACTION DATA
		$transactionData = [];
		$transactionData['transactionid'] = $transactionId;
		$transactionData['woocommerce_order_id'] = $customer_order->get_order_number(); 
		$transactionData['raw_requeset'] = $payload; 
		$transactionData['raw_callback'] = 'Not Available'; 
		$transactionData['url'] = $environment_url; 
		
		if ( is_wp_error( $response ) ) 
		{
			$message = 'We are currently experiencing problems trying to connect to this payment gateway. Sorry for the inconvenience.';
			$transactionData['raw_response'] = $message; 
			$this->saveTransaction($transactionData);

			throw new Exception( __( $message, 'spyr-tigocash' ) );
		}

		if ( empty( $response['body'] ) )
		{
			$message = 'Payment gat\'s Response was empty.';
			$transactionData['raw_response'] = $message; 
			$this->saveTransaction($transactionData);

			throw new Exception( __( $message, 'spyr-tigocash' ) );
		}

		// Retrieve the body's resopnse if no errors found
		$response_body = wp_remote_retrieve_body( $response );
		$transactionData['raw_response'] = $response_body; 

		// RECORD THIS TRANSATION INTO DB
		$this->saveTransaction($transactionData);
		$cleanResponse = $this->xmlToArray($response_body);
		$message = $this->getMessageByCode($cleanResponse['REQUESTSTATUS']);

		// Test the code to know if the transaction went through or not.
		// 1 or 4 means the transaction was a success
		if (strpos($response_body,'<REQUESTSTATUS>301</REQUESTSTATUS>')!== false) {								 
			// Mark order as Paid
			// $customer_order->payment_complete();
			

			$message = $message.'[PaymentPhone:'.$phonenumber.',Amount:'.$customer_order->order_total.']';
			$cleanResponse = $this->xmlToArray($response_body);

			if (isset($cleanResponse['REQUESTSTATUSDESC'])) {
				$message .= ' <br/> <strong> Current Status </strong> : '.$cleanResponse['REQUESTSTATUSDESC'];
			}

			if (isset($cleanResponse['REQUESTSTATUS'])) {
				$message .= ' <br/> <strong> Current Description</strong> : '.$cleanResponse['REQUESTSTATUS'];
			}

			// Payment has been successful
			$customer_order->add_order_note( __($message, 'spyr-tigocash' ) );
			// Mark as on-hold (we're awaiting the cheque)
			$customer_order->update_status( 'on-hold', _x( $message, 'Mobil money payment', 'woocommerce' ) );

			// Reduce stock levels
			$customer_order->reduce_order_stock();

			// Empty the cart (Very important step)
			$woocommerce->cart->empty_cart();

			// Redirect to thank you page
			return array(
				'result'   => 'success',
				'redirect' => $this->get_return_url( $customer_order )
			);
		}

		// Transaction was not succesful
		// Add notice to the cart
		throw new Exception($message, 1);
		
	}

	/**
	 * Creates order
	 * 
	 * @param  array $session 
	 * session['product_id'];
	 * session['ussd_session'];
	 * session['ussd_address'];
	 * 
	 * @return bool
	 */
	public function createOrder($session)
	{

	    $address = array(
	        'first_name' => $_POST['notes']['domain'],
	        'last_name'  => '',
	        'company'    => $_POST['customer']['company'],
	        'email'      => $_POST['customer']['email'],
	        'phone'      => $_POST['customer']['phone'],
	        'address_1'  => $_POST['customer']['address'],
	        'address_2'  => '', 
	        'city'       => $_POST['customer']['city'],
	        'state'      => '',
	        'postcode'   => $_POST['customer']['postalcode'],
	        'country'    => 'NL'
	    );

	    $order = wc_create_order();
	    foreach ($_POST['product_order'] as $productId => $productOrdered) :
	        $order->add_product( get_product( $productId ), 1 );
	    endforeach;

	    $order->set_address( $address, 'billing' );
	    $order->set_address( $address, 'shipping' );

	    $order->calculate_totals();

	    update_post_meta( $order->id, '_payment_method', 'ideal' );
	    update_post_meta( $order->id, '_payment_method_title', 'iDeal' );

	    // Store Order ID in session so it can be re-used after payment failure
	    WC()->session->order_awaiting_payment = $order->id;

	    // Process Payment
	    $available_gateways = WC()->payment_gateways->get_available_payment_gateways();
	    $result = $available_gateways[ 'ideal' ]->process_payment( $order->id );

	    // Redirect to success/confirmation/payment page
	    if ( $result['result'] == 'success' ) {

	        $result = apply_filters( 'woocommerce_payment_successful_result', $result, $order->id );

	        wp_redirect( $result['redirect'] );
	        exit;
	    }

	}
}