<?php 

/**
* This is a base class for CASHA USSD Manager Database
*/
class KashaUssdMenuItem extends UssdManagerModel{	
	/**
	 * Table name
	 * @var string
	 */
	protected $table='ussd_menu_items';

	public function wooCommerceItems()
	{

	 $query="SELECT ID,post_title,post_content,post_author,post_date_gmt FROM `" . $this->db->prefix . "posts` where post_type='product' and post_status = 'publish'";

	 $items = $this->db->get_results($query);
	 $products = [];
	 foreach ($items as $product) {

	 	 $product = new WC_Product($product->ID);
	 	 $products[] = $product ;
	 }
	 return $products;
	}
}