<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View-order</title>
</head>
<?php
    $_SESSION["username"]="Aw wa";
    $activeMenu="accounts";
    include_once("includes/admin-menu.php");
    require_once("includes/database.php");

    /* 1 transaction komien order kav ena */
    /* Dan receipt bisn ena price, paymenttype, paymentdate, product name, quantity*/
?>
<body>
    <main class="content">
    <div class="container-fluid p-0">
            <div class="mb-3">
                <h1 class="h3 d-inline align-middle">View Orders</h1>
            </div>
            <div class="card flex-fill">
                <div class="card-header">
                    <h5 class="card-title mb-0">Order Details</h5>
                </div>

            <table class="table table-hover my-0">
                <thead>
                    <tr>
                        <th>Order Ref</th>
                        <th>Product</th>
                        <th>Customer</th>
                        <th>Payment Ref</th>   
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php 
                        //DETAILS FOR RECEIPTS INCLUDED IN SELECT STATEMENT
                        $sSelect = "SELECT orders.transactionid,product.product_name,concat(customer.first_name,' ',customer.last_name) AS customer,
                                            payment.paymentid,payment.paymenttype,payment.paymentdate,SUM(product.price*orderdetails.quantity) AS total_price
                                    FROM orders,orderdetails,product,customer,payment,transactions
                                    WHERE orders.transactionid = orderdetails.orderid
                                    AND product.productid = orderdetails.productid
                                    AND transactions.customerid=customer.customerid
                                    AND transactions.paymentid = payment.paymentid";

                        $result = $conn->query($sSelect);
                        $rowcount = $result->rowcount();
                        // echo $rowcount;
                        if ($rowcount == 0){
                            echo "<td colspan=\"100%\" style=\"text-align: center;\"> No Order Request on file </td>";
                        }
                        else{
                            while($row = $result->fetch()){
                                echo "<tr>";

                                echo "<td>".$row["transactionid"]."</td>";
                                echo "<td>".$row["product_name"]."</td>";
                                echo "<td>".$row["customer"]."</td>";
                                echo "<td>".$row["paymentid"]."</td>";
                                echo "<td style=\"cursor:pointer; color:#003f87;font-weight: 500;\">Click here for complete receipt</td>";

                                echo "</tr>";
                            }
                                
                        }
                    ?>
                    <tr>
                        <td>1</td>
                        <td>Proraso - Shaving Cream Tube</td>
                        <td>Rivanen Ummavassee</td>
                        <td>3</td>
                        <td style="cursor:pointer; color:#003f87;font-weight: 500;">Click here for complete receipt</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Blind Barber - Clean Love Cream</td>
                        <td>Tesheena Naiko</td>
                        <td>1</td>
                        <td style="cursor:pointer; color:#003f87;font-weight: 500;">Click here for complete receipt</td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Proraso - Daily Face Cleaner</td>
                        <td>Rivanen Ummavassee</td>
                        <td>3</td>
                        <td style="cursor:pointer; color:#003f87;font-weight: 500;">Click here for complete receipt</td>
                    </tr>
                </tbody>
            </main>

        </div><!--end of main div-->
    </div><!--end of wrapper div-->
</body>

</html>