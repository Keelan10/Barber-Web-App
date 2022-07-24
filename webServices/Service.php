<?php
require_once("dbcontroller.php");
/* 
A domain Class to demonstrate RESTful web services
*/
Class Service {
	private $services = array();
	public function getAllService(){
		if(isset($_GET['name'])){
			$name = $_GET['name'];
			$query = 'SELECT * FROM service WHERE name LIKE "%' .$name. '%"';
		} else {
			$query = 'SELECT * FROM service';
		}
		$dbcontroller = new DBController();
		$this->services = $dbcontroller->executeSelectQuery($query);
		return $this->services;
	}

	public function addService(){
		if(isset($_POST['name'])){
			$name = $_POST['name'];
			$price = 0.0;
			$duration = 0;
			$description = "";
			
			if(isset($_POST['price'])){
				$price = $_POST['price'];
			}
			if(isset($_POST['duration'])){
				$duration = $_POST['duration'];
			}
			if(isset($_POST['description'])){
				$description = $_POST['description'];
			}

			$query = "INSERT INTO service (name,price,duration, description) values (?,?,?,?)";
			$data = [$name, $price , $duration, $description];
			$dbcontroller = new DBController();
			$result = $dbcontroller->executeQuery($query, $data );
			if($result != 0){
				$result = array('success'=>1);
				return $result;
			}
		}
	}
	
	public function deleteService(){
		if(isset($_GET['id'])){
			$service_id = $_GET['id'];
			$query = 'DELETE FROM service WHERE serviceid = ?';
			$data = [$service_id];
			$dbcontroller = new DBController();
			$result = $dbcontroller->executeQuery($query, $data);
			if($result != 0){
				$result = array('success'=>1);
				return $result;
			}
		}
	}
	
	public function editService(){
		if(isset($_POST['name']) && isset($_GET['id'])){
			$name = $_POST['name'];
			$price = $_POST['price'];
			$duration = $_POST['duration'];
			$description = $_POST['description'];
			$service_id = $_GET['id'];
			$query = "UPDATE service SET name = ?, price = ? , duration = ? , description= ? WHERE serviceid = ? ";
			$data = [$name, $price , $duration, $description ,$service_id ];
			$dbcontroller = new DBController();
			$result= $dbcontroller->executeQuery($query, $data);
			if($result != 0){
				$result = array('success'=>1);
				return $result;
			}
		}
		
	}
	
}
?>