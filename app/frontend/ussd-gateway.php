<?php 

 function processUssd()
 {

 	$unauthorizedAccess = '<COMMAND><MESSAGE>You are not authorized to do this.</MESSAGE></COMMAND>';

 	 $apiKey = new KashaAPIKey;
 	// SETTING RESPONSE HEADER
    header('Content-type: application/xml');

    if (!isset($_GET['api_token'])) {
    	echo $unauthorizedAccess; exit;
    }

    if (!$apiKey->where(['consumer_secret'=>$_GET['api_token']])->count() > 0) {
    	echo $unauthorizedAccess; exit;
    }

 	// Get submitted information
 	$submittedData = getRawInput();
   
 	// Process USSD session
 	echo  (new UssdFlowRepository)->process($submittedData);
 	exit;
 }

/**
 * Class Simple_Json_Api
 */
 function ussdGateWay_activate() {
  ussdGateWay_rules();
  flush_rewrite_rules();
 }

 function ussdGateWay_deactivate() {
  flush_rewrite_rules();
 }

 function ussdGateWay_rules() {
  add_rewrite_rule('ussd-gateway/?([^/]*)', 'index.php?pagename=ussd-gateway', 'top');
 }

// Make sure this is considered only on the oltranz fallback

$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if (strpos($actual_link, 'ussd-gateway') !== false) 
{
 //register activation function
 register_activation_hook(__FILE__, 'ussdGateWay_activate');
 //register deactivation function
 register_deactivation_hook(__FILE__, 'ussdGateWay_deactivate');
 //add rewrite rules in case another plugin flushes rules
 add_action('init', 'ussdGateWay_rules');
 //add plugin query vars (product_id) to wordpress
 add_filter('query_vars', 'ussdGateWay_query_vars');
 //register plugin custom pages display
 add_filter('template_redirect', 'processUssd');
}