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
	 * Order by 
	 * @var string
	 */
	protected $orderBy = '';
	
	/**
	 * Limit by 
	 * @var string
	 */
	protected $limit = '';
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

		// Migrate
		$this->migrate();
		// $this->setAttributes();
	}

	/**
	 * Get all transactions from the current table
	 * @param  array $columns 
	 * @return array   
	 */
	public function get($columns = [])
	{
	 	$columns = count($columns) > 0 ? implode(',',$columns) : ' * ';
		$sqlQuery = "SELECT $columns from ".$this->table.$this->condition.$this->orderBy.$this->limit;

		return  $this->db->get_results($sqlQuery);
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
	 * Get first element in the records
	 * @param  array $columns 
	 * @return array | null
	 */
	public function first($columns = [])
	{
		return isset($this->get($columns)[0]) ? $this->get()[0] : null;
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
	 * Get current records count
	 * @return 
	 */
	public function count()
	{
		return count($this->get());
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
	 * Order a record in a given order
	 * @param  string $columnName name to be ordered
	 * @param  string $order      ASC/DESC
	 * @return $this
	 */
	public function orderBy($columnName,$order = 'ASC')
	{
		$this->orderBy = ' ORDER BY '.$columnName.' '.$order;
		return $this;
	}

	/**
	 * Attached LIMIT OFFSET to the query
	 * @param  integer $limit  
	 * @param  integer $offset 
	 * @return  $this        
	 */
	public function limit($limit=1,$offset=1)
	{
		$this->limit = ' LIMIT '.$limit.','.$offset;

		return $this;
	}
	/**
	 * Adding condition to the query based on the input
	 * @param  array  $array
	 * @return $this
	 */
	public function where(array $conditions)
	{
		// If we already have a where condition 
		// then do not set this to the initial 
		// ones
		if (is_null($this->condition) || empty($this->condition)) {
				$this->condition = ' WHERE ';
		}
		
		$this->condition .='(';
		foreach ($conditions as $key => $value) {
			if (strtolower(trim($this->condition)) !=='where' and strtolower(trim($this->condition)) !=='where (') {
				$this->condition = $this->condition . ' AND ';
			}
			$this->condition = $this->condition . ' '.$key.' = '.$value;

		}
		$this->condition .=')';

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