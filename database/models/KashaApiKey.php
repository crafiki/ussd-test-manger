<?php 

/**
* MANAGES KashaAPIKey
*/
class KashaApiKey extends UssdManagerModel{	

	/**
	 * Table name
	 * @var string
	 */
	protected $table = 'woocommerce_api_keys';

	/**
	 * Just to comply
	 * @return  
	 */
	public function migrate()
	{
		# code...
	}
}