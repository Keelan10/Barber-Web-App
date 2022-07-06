<?php

require_once "includes/database.php";
session_start();

if (isset($_SESSION["customer_userid"]) && isset($_POST["products"]) && !empty($_POST["products"]) ){
    $products = $_POST["products"];
    // print_r($_POST["products"]);
    // die();
    // decrement from stock
    // Note that product names are unique
    $decrementStock = $conn->prepare("update product set quantity = quantity - ? where productid = ?");
    
    // insert into payment, transactions, order and order details
    $insertPayment = $conn->prepare("insert into payment(paymentdate) VALUES (CURDATE());");
    $insertTransaction = $conn->prepare("insert into transactions (customerid,paymentid) VALUES(?, LAST_INSERT_ID());");
    $insertOrder = $conn->prepare("insert into orders(transactionid) VALUES (LAST_INSERT_ID());");
        
    // insert products into order details
    $insertProducts = $conn->prepare('insert into orderdetails(orderid,productid,quantity,unit_price) values (?,?,?,(SELECT price from product where productid=?))');
    
    // get orderID
    $getOrderIDStatement = "select LAST_INSERT_ID() as orderid"; 
    
    // transaction
    try{
        $conn->beginTransaction();
        
        // insert into payment, transactions, order
        $insertPayment->execute();
        $insertTransaction->execute(array($_SESSION["customer_userid"]));
        $insertOrder->execute();
        
        // get orderid
        foreach($conn->query($getOrderIDStatement) as $row){
            $orderid = $row["orderid"];
        }
        // echo $orderid;
        
        
        for ($i=0; $i < COUNT($products) ; $i++) { 
            $qty = $products[$i]["qty"];
            $name = $products[$i]["name"];
            $productId=$products[$i]["id"];
            // print_r($products);
            // echo $qty;
            
            // decrement in stock
            $decrementStock->execute(array($qty, $productId));
            
            // insert into order details table
            $insertProducts->execute(array($orderid,$productId,$qty,$productId));
            // die();
            
        }

        $conn->commit();
        echo "success";
    }catch(\PDOException $e){
        $conn->rollBack();
        echo "failure";

        // show the error message
        die($e->getMessage());
    }
    // $preparedStatement->execute(array($_POST["transactionid"]));

    // if ($preparedStatement->rowCount()==0){
    //     echo "failure";
    // }else{
    //     echo "success";
    // }
    

}

