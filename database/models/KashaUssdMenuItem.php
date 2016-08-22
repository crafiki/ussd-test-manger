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
	 * @param  array $columns 
	 * @return array   
	 */
	public function get($columns = [])
	{	
		 $columns = count($columns) > 0 ? implode(',',$columns) : ' * ';

		 $sqlQuery = "SELECT $columns from ".$this->table.$this->condition.$this->orderBy.$this->limit;

		 $items =  $this->db->get_results( $sqlQuery);

		 $products = [];
		 foreach ($items as $menuItem) {

		 	 $product = new WC_Product($menuItem->woocommerce_item_id);

		 	 $item = new stdClass();
		 	 $item->id = $product->id;
		 	 $item->menu_order = $menuItem->menu_order;
		 	 $item->name = $product->post->post_title;
		 	 $item->content = get_the_post_thumbnail_url($item->id , 'thumbnail') ;	 	 
		 	 $item->price = $product->get_price();

		 	 $products[] = $item ;
		 }
		 return $products;
	}

	/**
	 * Get product ids
	 * @param  array  $columns 
	 * @return array 
	 */
	public function getProductIds($columns = [])
	{
		 $columns = count($columns) > 0 ? implode(',',$columns) : ' * ';
		 $sqlQuery = "SELECT $columns from ".$this->table.$this->condition.$this->orderBy.$this->limit;
		 
		 $results = $this->db->get_results( $sqlQuery);
		 $productIds = [];
		 foreach ($results as $key => $item) {
		 	$productIds[] = $item->woocommerce_item_id;
		 }

		 return $productIds;
	}

	/**
	 * migrate
	 * @return 
	 */
	public function migrate()
	{
	$charset_collate = $this->db->get_charset_collate();
	$query = "CREATE TABLE IF NOT EXISTS  ".$this->table." (
							  `id` int(11) AUTO_INCREMENT NOT NULL,
							  `woocommerce_item_id` int(11) UNIQUE NOT NULL,
							  `name` varchar(200) DEFAULT NULL,
							  `price` varchar(200) DEFAULT NULL,
							  `quantity` varchar(200) DEFAULT NULL,
							  `menu_order` int(11) NOT NULL DEFAULT '1',
							  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
							  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
							   PRIMARY KEY (`id`)
							) $charset_collate;";

	$this->db->query( $query );
	}

}