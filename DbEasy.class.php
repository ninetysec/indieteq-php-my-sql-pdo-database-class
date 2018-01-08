<?php 
/* *
 * Easy Crud  -  This class kinda works like ORM. Just created for fun :) 
 * @author		Author: Vivek Wicky Aswal / Scar1et
 * @git 			https://github.com/wickyaswal/PHP-MySQL-PDO-Database-Class
 * @version      0.2
 */
require_once("Db.class.php");
class DbEasy {

	private $db;

	public $variables;

	public function __construct($data = array()) {
		$this->db =  new DB();	
		$this->variables  = $data;
	}

	public function __set($name,$value){
		if(strtolower($name) === $this->pk) {
			$this->variables[$this->pk] = $value;
		}
		else {
			$this->variables[$name] = $value;
		}
	}

	public function __get($name)
	{	
		if(is_array($this->variables)) {
			if(array_key_exists($name,$this->variables)) {
				return $this->variables[$name];
			}
		}

		return null;
	}


	public function save($id = "0") {
		$this->variables[$this->pk] = (empty($this->variables[$this->pk])) ? $id : $this->variables[$this->pk];

		$fieldsvals = '';
		$columns = array_keys($this->variables);

		foreach($columns as $column)
		{
			if($column !== $this->pk)
			$fieldsvals .= $column . " = :". $column . ",";
		}

		$fieldsvals = substr_replace($fieldsvals , '', -1);

		if(count($columns) > 1 ) {

			$sql = "UPDATE " . $this->table .  " SET " . $fieldsvals . " WHERE " . $this->pk . "= :" . $this->pk;
			if($id === "0" && $this->variables[$this->pk] === "0") { 
				unset($this->variables[$this->pk]);
				$sql = "UPDATE " . $this->table .  " SET " . $fieldsvals;
			}

			$result = $this->exec($sql);

			return $result;
		}

		$this->variables = false;

		return $this;
	}

	public function create() { 
		$bindings   	= $this->variables;

		if(!empty($bindings)) {
			$fields     =  array_keys($bindings);
			$fieldsvals =  array(implode(",",$fields),":" . implode(",:",$fields));
			$sql 		= "INSERT INTO ".$this->table." (".$fieldsvals[0].") VALUES (".$fieldsvals[1].")";
		}
		else {
			$sql 		= "INSERT INTO ".$this->table." () VALUES ()";
		}

		$result = $this->exec($sql);

		return $this->db->lastInsertId();
	}

	public function delete($id = "") {
		$id = (empty($this->variables[$this->pk])) ? $id : $this->variables[$this->pk];

		if(!empty($id)) {
			$sql = "DELETE FROM " . $this->table . " WHERE " . $this->pk . "= :" . $this->pk. " LIMIT 1" ;
		}

		$result = $this->exec($sql, array($this->pk=>$id));

		return $result;
	}

	public function find($id = "") {
		$id = (empty($this->variables[$this->pk])) ? $id : $this->variables[$this->pk];

		if(!empty($id)) {
			$sql = "SELECT * FROM " . $this->table ." WHERE " . $this->pk . "= :" . $this->pk . " LIMIT 1";	

			$result = $this->db->row($sql, array($this->pk=>$id));
			$this->variables = ($result != false) ? $result : null;
			return $this;
		}
	}
	/**
	* @param array $fields.
	* @param array $sort.
	* @return array of Collection.
	* Example: $user = new User;
	* $found_user_array = $user->search(array('sex' => 'Male', 'age' => '18'), array('dob' => 'DESC'));
	* // Will produce: SELECT * FROM {$this->table_name} WHERE sex = :sex AND age = :age ORDER BY dob DESC;
	* // And rest is binding those params with the Query. Which will return an array.
	* // Now we can use for each on $found_user_array.
	* Other functionalities ex: Support for LIKE, >, <, >=, <= ... Are not yet supported.
	*/
	public function search($fields = array(), $sort = array()) {
		$bindings = empty($fields) ? $this->variables : $fields;
		$this->variables = $bindings;

		$sql = "SELECT * FROM " . $this->table;

		if (!empty($bindings)) {
			$fieldsvals = array();
			$columns = array_keys($bindings);
			foreach($columns as $column) {
				$fieldsvals [] = $column . " = :". $column;
			}
			$sql .= " WHERE " . implode(" AND ", $fieldsvals);
		}
		
		if (!empty($sort)) {
			$sortvals = array();
			foreach ($sort as $key => $value) {
				$sortvals[] = $key . " " . $value;
			}
			$sql .= " ORDER BY " . implode(", ", $sortvals);
		}

		$result = $this->exec($sql);

		return $result;
	}

	public function all(){
		$result = $this->db->query("SELECT * FROM " . $this->table);
		$this->variables = ($result != false) ? $result : null;

		return $this;
	}
	
	public function min($field)  {
		if($field)
		$result = $this->db->single("SELECT min(" . $field . ")" . " FROM " . $this->table);
		$this->variables = ($result != false) ? $result : null;

		return $this;
	}

	public function max($field)  {
		if($field)
		$result = $this->db->single("SELECT max(" . $field . ")" . " FROM " . $this->table);
		$this->variables = ($result != false) ? $result : null;

		return $this;
	}

	public function avg($field)  {
		if($field)
		$result = $this->db->single("SELECT avg(" . $field . ")" . " FROM " . $this->table);
		$this->variables = ($result != false) ? $result : null;

		return $this;
	}

	public function sum($field)  {
		if($field)
		$result = $this->db->single("SELECT sum(" . $field . ")" . " FROM " . $this->table);
		$this->variables = ($result != false) ? $result : null;

		return $this;
	}

	public function count($field)  {
		if($field)
		$result = $this->db->single("SELECT count(" . $field . ")" . " FROM " . $this->table);
		$this->variables = ($result != false) ? $result : null;

		return $this;
	}	

	public function toSql() {
		return $this->db->sQuery->queryString;
	}

	public function toArray() {
		return $this->variables;
	}

	private function exec($sql, $array = null) {
		if($array !== null) {
			// Get result with the DB object
			$result =  $this->db->query($sql, $array);	
		}
		else {
			// Get result with the DB object
			$result =  $this->db->query($sql, $this->variables);	
		}
		
		// Empty bindings
		$this->variables = array();

		return $result;
	}

}
?>
