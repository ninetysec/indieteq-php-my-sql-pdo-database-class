<?php
	require("Test.class.php");

	// Creates the instance
	$db = new Db();

	$dbEasy  = new Test();

	// All Info
	$dbEasy_all = $dbEasy->All()->toArray();

	// Find Info
	$dbEasy->Id = "1";
	$dbEasy_find = $dbEasy->Find()->toArray();
	
	// Find Info Print SQL
	// toSql being used only for All or Find
	$dbEasy->Id = "1";
	$dbEasy_find_sql = $dbEasy->Find()->toSql();

	// Update Info
	$dbEasy->Id = "99";
	$dbEasy->Firstname = "中文TEST1";
	$dbEasy_save = $dbEasy->Save();

	// Create Info
	$dbEasy->Firstname = "测试";
	$dbEasy->Lastname  = "测试测试";
	$dbEasy->Sex = "F";
	$dbEasy->Age = "18";
	$creation = $dbEasy->Create();

	// Search info
	// @param array $where
	// @param array $sort
	$dbEasy_search  = $dbEasy->Search(['Sex' => 'F'],['Age' => 'desc']);
	var_dump($dbEasy_search);

	// Delete Info
	$dbEasy->id = "22";	
	$delete = $dbEasy->Delete();

	//var_dump($delete);
		
	// 3 ways to bind parameters :		
	
	// 1. Read friendly method	
	$db->bind("firstname","John");
	$db->bind("age","19");
/*
	// 2. Bind more parameters
	$db->bindMore(array("firstname"=>"John","age"=>"19"));		

	// 3. Or just give the parameters to the method
	$db->query("SELECT * FROM Persons WHERE firstname = :firstname AND age = :age", array("firstname"=>"John","age"=>"19"));

	//  Fetching data
	$person 	 =     $db->query("SELECT * FROM Persons");

	// If you want another fetchmode just give it as parameter
	$persons_num     =     $db->query("SELECT * FROM Persons", null, PDO::FETCH_NUM);
	
	// Fetching single value
	$id1	 =     $db->query("SELECT * FROM test WHERE Id = :id ", array('id' => $_GET['id1'] ) );

	$id2	 =     $db->query("SELECT * FROM test WHERE Id = :id ", array('id' => $_GET['id2'] ) );
	
	// Single Row
	$id_age 	 =     $db->row("SELECT Id, Age FROM Persons WHERE firstname = :f", array("f"=>"Zoe"));
		
	// Single Row with numeric index90
	$id_age_num      =     $db->row("SELECT Id, Age FROM Persons WHERE firstname = :f", array("f"=>"Zoe"),PDO::FETCH_NUM);
	
	// Column, numeric index
	$ages  		 =     $db->column("SELECT age FROM Persons");

	// The following statements will return the affected rows
	
	// Update statement
	//$update		 =  $db->query("UPDATE Persons SET firstname = :f WHERE Id = :id",array("f"=>"Johny","id"=>"3")); 
	
	// Insert statement
//	$insert	 	 =  $db->query("INSERT INTO Persons(Firstname,Age) 	VALUES(:f,:age)",array("f"=>"Vivek","age"=>"20"));
	
	// Delete statement
//	$delete	 	 =  $db->query("DELETE FROM Persons WHERE Id = :id",array("id"=>"6")); 
/*
	function d($v, $t = "") 
	{
		echo '<pre>';
		echo '<h1>' . $t. '</h1>';
		var_dump($v);
		echo '</pre>';
	}
	//d($person, "All persons");
	d($id_age, "Single Row, Id and Age");
	//d($firstname, "Fetch Single value, The firstname");
	d($ages, "Fetch Column, Numeric Index");
*/
	// var_dump($id1);
	// var_dump($id2);

$tmp = 'a:4:{s:12:"price_ladder";a:1:{i:0;a:2:{s:6:"amount";i:1;s:5:"price";d:33;}}s:15:"restrict_amount";i:98;s:13:"gift_integral";i:0;s:7:"deposit";d:0;}';
var_dump(unserialize($tmp));

?>
