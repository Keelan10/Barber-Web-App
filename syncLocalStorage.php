<?php
require_once "includes/database.php";

// Refresh local storage with up to date database values for price and quantity

// it will be empty if there is no product in local storage
if (isset($_GET["products"]) && !empty($_GET["products"])){
    $products = $_GET["products"];

    $preparedStatement = $conn->prepare("SELECT price, quantity from product where product_name = ?");

    for ($i=0;$i<count($products);$i++){

        $preparedStatement->execute(array($products[$i]["name"]));

        // if product not in database, delete from local storage
        if ($preparedStatement->rowCount()==0){
            unset($products[$i]);
            return;
        }

        while($row=$preparedStatement->fetch()){

            // if quantity is zero, remove from local storage
            if ($row["quantity"]==0){
                unset($products[$i]);
                return;
            }

            // if quantity in local storage exceeds stock, set quantity of local storage to quantity in stock 
            if ($products[$i]["qty"]>$row["quantity"]){
                $products[$i]["qty"]=$row["quantity"];
            }
            
            // update price
            $products[$i]["price"] = $row["price"];

        
        }
    }
    header('Content-Type: application/json');
    echo json_encode($products);
}