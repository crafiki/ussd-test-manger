<?php 

/**
* USSD REPOSITORY
*/
class UssdFlowRepository{

	/**
	 * Unwanted keys in the xml
	 * @var array
	 */
	public $unwanted = ['@root'];

	/**
	 * Holds ussd session model
	 * @var database\models\KashaUssdSession
	 */
	private $ussdSession;

	/**
	 * Holds session
	 * @var string
	 */
	private $sessionData = null;

	/**
	 * Ussd menu items
	 * @var database\models\KashaUssdMenuItem
	 */
	private $ussdMenuItem;

	/**
	 * Response template
	 * @var string
	 */
	protected $responseCommand ='<COMMAND>
								    <MSISDN>PARAM_MSISDN</MSISDN>
								    <SESSIONID>PARAM_SESSIONID</SESSIONID>
								    <FREEFLOW>PARAM_FREEFLOW</FREEFLOW>
								    <MESSAGE>PARAM_MESSAGE</MESSAGE>
								    <NEWREQUEST>PARAM_NEWREQUEST</NEWREQUEST>
								    <MENUS>PARAM_MENUS</MENUS>
								  </COMMAND>';

	/**
	 * Get sanatized request
	 * @var null
	 */
	protected $rawRequest = null;

	function __construct()
	{
		$this->ussdSession = new KashaUssdSession;
		$this->ussdMenuItem = new KashaUssdMenuItem;
	}

	/**
	 * Process incoming USSD SESSION
	 * @param  array  $ussdData 
	 * @return string 
	 */
	public function process(string $rawInput)
	{
		// Prepare data
		$this->rawRequest = $rawInput;
		$this->sessionData = $this->getSanatized();

		try
		{
			// Determine which level of USSD we are at
			$session = $this->ussdSession->where(['msisdn'=>$this->sessionData['msisdn'],'sessionid'=>$this->sessionData['sessionid']]);

			$nextMenu = '';

			// Determine parent
			switch ($session->count()) {
				case 0:      // This is the first entry

					$nextMenu              = $this->getFirstMenu();
					break;
				default:
					// Determine which parent
					$previousChoice= $session->orderBy('created_at','desc')->first();
					$message = 'Thank you for your purchase';
				    $nextMenu = $this->buildResponse($this->sessionData,$message);
					break;
			}
			
			$this->sessionData['raw_response'] = $nextMenu;
			// Log the session
			if(!$this->logSession($this->sessionData)){
				throw new Exception("Unable to process ussd session", 1);
			}

			return $nextMenu;
		}
		catch(Exception $ex)
		{
			$this->sessionData['raw_response'] = 'Sorry but error occured while processing your request.Error:'.$ex->getMessage();
			$message = 'Sorry but error occured while processing your request';
	    	return   $this->buildResponse($this->sessionData,$message);
		}
	}

    protected function getFirstMenu()
    {
    	$message = 'Welcome to KASHA Store';
    	$menus   = $this->ussdMenuItem->orderBy('menu_order')->get();
    	return   $this->buildResponse($this->sessionData,$message,$menus);
    }
	/**
	 * Sanatizes inputs
	 * @return  array
	 */
	private function getSanatized()
	{
	 	$cleanedData  = xmlToArray($this->rawRequest);
	 	// REMOVED NON NEEDED DATA
	 	foreach ($this->unwanted as $key => $value) {
	 		unset($cleanedData[$value]);
	 	}
	 	
	 	$cleanedData['raw_request'] = $this->rawRequest;
	 	$cleanedData = array_change_key_case($cleanedData, CASE_LOWER);

	 	return $cleanedData;
	}

	/**
	 * Building response as required by OLTRANZ
	 * @param  array  $session      
	 * @param  string $message      
	 * @param  array  $responseMenu 
	 * @param  string $freeflow     FREEFLOW can be C : continue (gives to user input capacity   or B: Block (last message)
	 * @return string
	 */
	private function buildResponse(array $session,$message,array $responseMenu=[],$freeflow = 'FC')
	{	
	  // Build response
	 foreach ($session as $key => $value) {
	 	// If we have a key to replace then do so
	 	$key = strtoupper('param_'.$key);
	    if (strpos($this->responseCommand,$key)) {
	    	$this->responseCommand = str_replace($key,$value,$this->responseCommand);
	    }
	 }

	 // Adding FreeFLow 
	 $this->responseCommand = str_replace('PARAM_FREEFLOW',$freeflow,$this->responseCommand);

	 // ADD MESSAGE to the response
	 $this->responseCommand = str_replace('PARAM_MESSAGE',$message,$this->responseCommand);

	 if (empty($responseMenu) || count($responseMenu) == 0) {
	 	 $this->responseCommand = str_replace('PARAM_MENUS','',$this->responseCommand);
	 	 return $this->responseCommand;
	 }


	 // We have menu/ options to add to the response 
	 // show it here then.
     // Building options
	  $menu = '';
	  foreach ($responseMenu as $key => $item) {
	  		 $menu .= '<MENU>'.++$key.'.'.$item->name.'('.$item->price.get_woocommerce_currency_symbol().')</MENU>';
	  }
   	 
   	  // ADD OPTIONS
   	  $this->responseCommand = str_replace('PARAM_MENUS',$menu,$this->responseCommand);	   

	  return  $this->responseCommand;
	}

	/**
	 * Log ussd session
	 * @param  array  $data 
	 * @return 
	 */
	private function logSession()
	{
		return $this->ussdSession->save($this->sessionData);
	}
}