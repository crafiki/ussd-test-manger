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

	/**
	 * Get wooCommerce products 
	 * @return [type] [description]
	 */
	public function wooCommerceItems()
	{

	 $query="SELECT ID,post_title,post_content,post_author,post_date_gmt FROM `" . $this->db->prefix . "posts` where post_type='product' and post_status = 'publish'";

	 $items = $this->db->get_results($query);
	 $products = [];
	 foreach ($items as $product) {

	 	 $product = new WC_Product($product->ID);

	 	 $item = new stdClass();
	 	 $item->id = $product->id;
	 	 $item->name = $product->post->post_title;
	 	 $item->content = $product->post->post_content;	 	 
	 	 $item->price = $product->get_price();
	 	 $products[] = $item ;
	 }
	 return $products;
	}

  /**
	 * Get all transactions fromt the current table
	 * @param  numerci id $value 
	 * @param  numerci $limit 
	 * @param  numeric $offset
	 * @return array   
	 */
	public function get()
	{

		 $items =  $this->db->get_results("SELECT * from ".$this->table.$this->condition.$this->orderBy);
		 $products = [];
		 foreach ($items as $product) {

		 	 $product = new WC_Product($product->woocommerce_item_id);

		 	 $item = new stdClass();
		 	 $item->id = $product->id;
		 	 $item->order = $product->order;
		 	 $item->name = $product->post->post_title;
		 	 $item->content = get_the_post_thumbnail_url($item->id , 'thumbnail') ;	 	 
		 	 $item->price = $product->get_price();

		 	 $products[] = $item ;
		 }
		 return $products;
	}

}