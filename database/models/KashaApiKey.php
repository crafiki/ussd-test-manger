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
	 * Migrate the table just to be compliant with the interface
	 * @return 
	 */
	public function migrate()
	{
	}
}