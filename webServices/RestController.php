<?php

//Adapted from https://phppot.com/php/php-restful-web-service/
require_once("ServiceRestHandler.php");
require_once("ProductRestHandler.php");
$method = $_SERVER['REQUEST_METHOD'];
$view = "";

if(isset($_GET["resource"]))
	$resource = $_GET["resource"];
if(isset($_GET["page_key"]))
	$page_key = $_GET["page_key"];
/*
controls the RESTful services
URL mapping
*/


switch($resource){
	case "service":	
		switch($page_key){

			case "list":
				// to handle REST Url /service/list/
		
				//echo "list invoked from service";
				$serviceRestHandler = new ServiceRestHandler();
				$result = $serviceRestHandler->getAllServices();
			break;
	
			case "create":
				// to handle REST Url /service/create/
				//echo "create invoked from service";
				$serviceRestHandler = new ServiceRestHandler();
				$serviceRestHandler->add();
			break;
		
			case "delete":
				// to handle REST Url /service/delete/<row_id>
				//echo "delete invoked from service";
				$serviceRestHandler = new ServiceRestHandler();
				$result = $serviceRestHandler->deleteserviceById();
			break;
		
			case "update":
				//echo "update invoked from service";
				// to handle REST Url /service/update/<row_id>
				$serviceRestHandler = new ServiceRestHandler();
				$serviceRestHandler->editServiceById();
			break;
		}
	break;

	case "product":	
		switch($page_key){

			case "list":
				// to handle REST Url /service/list/
				
				//echo "list invoked from service";
				$productRestHandler = new ProductRestHandler();
				$result = $productRestHandler->getAllProducts();
			break;
	
			case "create":
				// to handle REST Url /service/create/
				//echo "create invoked from service";
				$productRestHandler = new ProductRestHandler();
				$productRestHandler->add();
			break;
		
			case "delete":
				// to handle REST Url /service/delete/<row_id>
				//echo "delete invoked from service";
				$productRestHandler = new ProductRestHandler();
				$result = $productRestHandler->deleteProductById();
			break;
		
			case "update":
				//echo "update invoked from service";
				// to handle REST Url /service/update/<row_id>
				$productRestHandler = new ProductRestHandler();
				$productRestHandler->editProductById();
			break;
		}
	
	break;
}	
?>
