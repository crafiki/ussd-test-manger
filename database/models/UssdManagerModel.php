<?php 

/**
* This is a base class for CASHA USSD Manager Database
*/
class UssdManagerModel{	
	/**
	 * Database instance
	 * @var database connection
	 */
	protected $db;

	/**
	 * Table name
	 * @var string
	 */
	protected $table='';

	/**
	 * Query condition
	 * @var null
	 */
	protected $condition = '';
	/**
	 * Primary key of this table
	 * @var string
	 */
	protected $primaryKey = 'id';

	function __construct()
	{
		global $wpdb;
		$this->db = $wpdb;
		$this->table = $wpdb->prefix . $this->table;
		// $this->setAttributes();
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
		return  $this->db->get_results("SELECT * from ".$this->table.$this->condition);
	}

    /**
	 * Get resource
	 * @param  $id 
	 * @return array
	 */
	public function find($id)
	{
		$results = $this->where([$this->primaryKey=>$id])->get();
		return isset($results[0]) ? $results[0] : null;
	}

    /**
	 * Save / update a resource in the DB
	 * @param  array  $data 
	 * @return      
	 */
	public function save(array $data)
	{	
		// Remove un needed information 
		// if available
		unset($data['save']);
		unset($data['update']);

		if ($this->exists($data['id'])) {
			return  $this->db->update($this->table,$data,array('id'=>$data['id']));
		}
		return $this->db->insert($this->table,$data);
	}

   /**
	 * Check if a transaction exists
	 * @param  string $id 
	 * @return bool 
	 */
	public function exists($id)
	{
		return count($this->find($id)) > 0;
	}

	/**
	 * Delete a resource from DB
	 * @param  numeric $id 
	 * @return  null | string
	 */
	public function delete($id)
	{
		return $this->db->query($this->db->prepare("DELETE FROM ".$this->table." WHERE ".$this->primaryKey." = %s",$id));
	}

	/**
	 * Adding condition to the query based on the input
	 * @param  array  $array
	 * @return $this
	 */
	public function where(array $conditions)
	{
		$this->condition = ' WHERE ';
		foreach ($conditions as $key => $value) {
			if (strtolower(trim($this->condition)) !=='where') {
				$this->condition = $this->condition . ' AND ';
			}
			$this->condition = $this->condition . ' '.$key.' = '.$value;
		}

		return $this;
	}

	/**
	 * Set properties of the class
	 * @return null
	 */
	protected function setAttributes()
	{
		$sql = "SHOW COLUMNS FROM ".$this->table;
		$columns = $this->db->get_results($sql);
		foreach ($columns as $row) {
			$this->{$row->Field};
		}

	}
}