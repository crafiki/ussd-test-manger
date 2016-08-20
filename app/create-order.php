<?php 
function createOrder($productId)
{
	$address = array(
            'first_name' => 'Fresher',
            'last_name'  => 'StAcK OvErFloW',
            'company'    => 'stackoverflow',
            'email'      => 'test@test.com',
            'phone'      => '777-777-777-777',
            'address_1'  => '31 Main Street',
            'address_2'  => '', 
            'city'       => 'Chennai',
            'state'      => 'TN',
            'postcode'   => '12345',
            'country'    => 'IN'
        );

        $order = wc_create_order();
        $order->add_product( get_product( '12' ), 2 ); //(get_product with id and next is for quantity)
        $order->set_address( $address, 'billing' );
        $order->set_address( $address, 'shipping' );
        $order->add_coupon('Fresher','10','2'); // accepted param $couponcode, $couponamount,$coupon_tax
        $order->calculate_totals();
}