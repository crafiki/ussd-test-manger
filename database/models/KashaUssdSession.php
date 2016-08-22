<?php 

/**
* This is a base class for CASHA USSD Manager Database
*/
class KashaUssdSession extends UssdManagerModel{	
	/**
	 * Table name
	 * @var string
	 */
	protected $table='ussd_sessions';

	/**
	 * Migrate the table
	 * @return 
	 */
	public function migrate()
	{
	$charset_collate = $this->db->get_charset_collate();
	$query =   "CREATE TABLE IF NOT EXISTS  ".$this->table." (
				  `id` int(11) NOT NULL,
				  `msisdn` varchar(200) DEFAULT NULL,
				  `sessionid` varchar(200) DEFAULT NULL,
				  `newrequest` varchar(200) DEFAULT NULL,
				  `agentid` varchar(200) DEFAULT NULL,
				  `input` varchar(200) DEFAULT NULL,
				  `spid` varchar(200) DEFAULT NULL,
				  `frommultiussd` varchar(200) DEFAULT NULL,
				  `resume` varchar(200) DEFAULT NULL,
				  `slug` varchar(200) DEFAULT NULL,
				  `raw_request` text,
				  `raw_response` text,
				  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
				   PRIMARY KEY (`id`)
				) $charset_collate;";
						
	 $this->db->query( $query );
	}
}