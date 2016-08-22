<?php 

/**
* This is a base class for CASHA USSD Manager Database
*/
class KashaUssdAddress extends UssdManagerModel{	
	/**
	 * Table name
	 * @var string
	 */
	protected $table='ussd_address';

	/**
	 * Migrate
	 * @return [type] [description]
	 */
	public function migrate()
	{
	$charset_collate = $this->db->get_charset_collate();
	$query =  "CREATE TABLE IF NOT EXISTS  ".$this->table." (
						  `id` int(11) AUTO_INCREMENT NOT NULL,
						  `first_name` varchar(200) DEFAULT 'USSD',
						  `last_name` varchar(200) DEFAULT 'Interface',
						  `company` varchar(200) DEFAULT 'KASHA.RW',
						  `email` varchar(200) DEFAULT 'ussd@mail.com',
						  `phone` varchar(200) DEFAULT '2507xxxxxx',
						  `address_1` varchar(200) DEFAULT 'Chez Lando Hotel Reception, KG 201 St, Remera, Kigali',
						  `address_2` varchar(200) DEFAULT 'Chez Lando Hotel Reception, KG 201 St, Remera, Kigali',
						  `city` varchar(200) DEFAULT 'Kigali',
						  `state` varchar(200) DEFAULT 'Kigali',
						  `postcode` varchar(200) DEFAULT '250',
						  `country` varchar(200) DEFAULT 'Rwanda',
						  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
						  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
						   PRIMARY KEY (`id`)
						)$charset_collate;";
    
	 $this->db->query( $query );
	}
}