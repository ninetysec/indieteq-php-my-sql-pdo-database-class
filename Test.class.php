<?php 
require_once("DbEasy.class.php");

class Test Extends DbEasy {
	
	# Your Table name 
	protected $table = 'test';
	
	# Primary Key of the Table
	protected $pk	 = 'id';
	
}

?>