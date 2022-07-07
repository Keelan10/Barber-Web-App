<?php

require_once "includes/database.php";

if (isset($_POST["transactionid"])){

    try{
        $conn->beginTransaction();
        $preparedStatement = $conn->prepare("update transactions set is_cancelled=1 where transactionid = ?");
    
        $preparedStatement->execute(array($_POST["transactionid"]));

        if ($preparedStatement->rowCount()==0) return;

        if($_POST["option"]=="order"){
            // set all products purchased to 30% of their price (70% refund)
            $setPriceStatement = $conn->prepare("update orderdetails set unit_price = 0.3* unit_price where orderid = ? "); 
            $setPriceStatement->execute(array($_POST["transactionid"]));

            $getProductsStatement = $conn->prepare("SELECT productid, quantity FROM orderdetails where orderid = ?");

            // increment stock
            $incrementStockStatement = $conn-> prepare("update product set quantity = quantity + ? where productid= ?");

            $getProductsStatement->execute(array($_POST["transactionid"]));

            while ($row = $getProductsStatement->fetch()){
                $productid = $row["productid"];
                $quantity = $row["quantity"];

                $incrementStockStatement->execute(array($quantity, $productid));
            }


        }else if($_POST["option"]=="appointment"){
            // set all paid services  to 30% of their price (70% refund)
            $setPriceStatement = $conn->prepare("update appointmentdetails set price = 0.3* price where transactionid = ? "); 
            $setPriceStatement->execute(array($_POST["transactionid"]));

        }

    
        $conn->commit();
        echo "success";
    }catch(\PDOException $e){
        $conn->rollBack();
        echo "failure";

        // show the error message
        die($e->getMessage());
    }
}