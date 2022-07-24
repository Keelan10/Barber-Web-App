<?php
require_once("dbcontroller.php");
/* 
A domain Class to demonstrate RESTful web services
*/
Class Product {
	private $products = array();
	public function getAllProduct(){
		if(isset($_GET['name'])){
			$name = $_GET['name'];
			$query = 'SELECT * FROM product WHERE product_name LIKE "%' .$name. '%"';
		} else {
			$query = 'SELECT * FROM product';
		}
		$dbcontroller = new DBController();
		$this->products = $dbcontroller->executeSelectQuery($query);
		return $this->products;
	}

	public function addProduct(){
		if(isset($_POST['name'])){
			$product_name = $_POST['name'];
			$price = 0.0;
			$weight = 0;
			$quantity = 0;
			$category = "";
            $image_name="";
			
			if(isset($_POST['price'])){
				$price = $_POST['price'];
			}
			if(isset($_POST['weight'])){
				$weight = $_POST['weight'];
			}
			if(isset($_POST['quantity'])){
				$quantity = $_POST['quantity'];
			}
            if(isset($_POST['category'])){
				$category = $_POST['category'];
			}


			$query = "INSERT INTO product (product_name,price,weight, quantity,category,image_name) values (?,?,?,?,?,?)";
			$data = [$product_name, $price , $weight, $quantity, $category,$image_name];
			$dbcontroller = new DBController();
			$result = $dbcontroller->executeQuery($query, $data );
			if($result != 0){
				$result = array('success'=>1);
				return $result;
			}
		}
	}
	
	public function deleteProduct(){
		if(isset($_GET['id'])){
			$product_id = $_GET['id'];
			$query = 'DELETE FROM product WHERE productid = ?';
			$data = [$product_id];
			$dbcontroller = new DBController();
			$result = $dbcontroller->executeQuery($query, $data);
			if($result != 0){
				$result = array('success'=>1);
				return $result;
			}
		}
	}
	
	public function editProduct(){
		if(isset($_POST['name']) && isset($_GET['id'])){
			$product_name = $_POST['name'];
			$price = $_POST['price'];
			$weight = $_POST['weight'];
			$quantity = $_POST['quantity'];
			$category = $_POST['category'];
			$product_id = $_GET['id'];
			$query = "UPDATE product SET product_name = ?, price = ? , weight = ? , quantity= ? , category = ? WHERE productid = ? ";
			$data = [$product_name, $price , $weight, $quantity , $category, $product_id ];
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