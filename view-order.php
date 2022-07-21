<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View-order</title>
</head>
<style>
    .overlay {
        display: none;
        top: 0;
        left: 0;
        background-color: rgba(0, 0, 0, 0.25);
        position: fixed;
        width: 100%;
        height: 100%;
    }

    .receipt {
        border: 1px solid black;
        border-radius: 7px;
        width: 40%;
        /* min-height: 20rem; */
        display: flex;
        flex-direction: column;
        margin: auto;
        margin-top: 6rem;
        box-shadow: 0 5px 15px rgb(0 0 0 / 50%);
        background: white;
        padding: 1rem;
    }


    .fa-times {
        cursor: pointer;
        transition: all 1s;
    }

    .fa-times:hover {
        transform: scale(1.25);
    }


    /* -------------------------------------
            INVOICE
            Styles for the billing table
        ------------------------------------- */

    .invoice {
        margin: 40px auto;
        text-align: left;
        width: 80%;
    }

    .invoice td {
        padding: 5px 0;
    }

    .invoice .invoice-items {
        width: 100%;
    }

    .invoice .invoice-items td {
        border-top: #eee 1px solid;
    }

    .invoice .invoice-items .total td {
        border-top: 2px solid #333;
        border-bottom: 2px solid #333;
        font-weight: 700;
    }

    .content-block {
        padding: 0 0 20px;
        text-align: center;
    }

    .alignright {
        text-align: right;
    }

    table h2 {
        font-size: 24px;
    }
</style>
<?php
//$_SESSION["username"]="Aw wa";
$activeMenu = "orders";
include_once("includes/admin-menu.php");
require_once("includes/database.php");

/* 1 transaction komien order kav ena */
/* Dan receipt bisn ena price, paymenttype, paymentdate, product name, quantity*/
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $(".clickReceipt").click(function() {
            const customer_name = $(this).siblings(".customer_name").text()
            var orderId = $(this).attr('id');
            var url = "getPurchasedItems.php";
            $.ajax({
                    dataType:"json",
                    url: url,
                    data: {
                        orderId: orderId
                    },
                    accepts: "application/json",
                    method: "POST",
                    error: function(xhr) {
                        alert("An error occured: " + xhr.status + " " + xhr.statusText);
                    }
                })
                .done(function(data) {
                    // console.log(data)
                    var result = "";
                    var sum = 0;
                    $.each(data, function(i,obj) {
                        // alert(obj['paymentid'])
                        $("span#customer-name").html(customer_name)
                        $("span#orderNo").html(orderId)
                        // $("span#paymentType").html(obj['paymenttype'])
                        $("span#paymentDate").html(obj['date'])
                    })

                    for (var i = 0; i < data.length; i++) {
                        sum += parseInt(data[i].totalPerProduct)
                        result +=
                            `<tr>
                                <td>${data[i].product_name} (x${data[i].quantity})</td>
                                <td class="alignright">Rs ${parseInt(data[i].totalPerProduct)}</td>
                            </tr>`

                    }

                    // // console.log(sum)
                    result +=
                        `<tr class="total">
                            <td class="alignright" width="80%">Total</td>
                            <td class="alignright">Rs ${sum}</td>
                        </tr>`;
                    // console.log(result)
                    $(".items-container").html(result)
                    $("#date").text(data[0].date)
                    if(data[0].is_cancelled) $("#cancelled").show();
                    else $("#cancelled").hide();
                });
            $(".overlay").show();
        });
        $(".fa-times").click(function() {
            $(".overlay").hide()
        })
    });
</script>

<body>
    <div class="overlay">
        <div class="receipt">
            <i class="fas fa-times" style="margin-left: auto;font-size:2rem;color:red"></i>
            <table class="" width="100%" cellpadding="0" cellspacing="0">
                <tbody>
                    <tr>
                        <td class="content-wrap aligncenter">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tbody>
                                    <tr>
                                        <td class="content-block">
                                            <h2>Receipt</h2>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content-block">
                                            <table class="invoice">
                                                <tbody class="receipt-body">
                                                    <tr>
                                                        <th>
                                                            <span id="customer-name"></span>
                                                            <!-- <br>Invoice #<span id="orderNo"><span>
                                                                    <br>Payment Type<span id="paymentType"><span>
                                                                            <br>Payment Date<span id="paymentDate"><span> -->
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            Invoice #<span id="orderNo"><span>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th id='cancelled' style="color:red">
                                                            Cancelled
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            Payment Date : <span id="paymentDate"><span>
                                                        </th>
                                                    </tr>

                                                    <tr>
                                                        <td>
                                                            <table class="invoice-items" cellpadding="0" cellspacing="0">
                                                                <tbody class="items-container"></tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <!-- <td class="content-block"> Company Inc. 123 Van Ness, San Francisco 94102</td> -->
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
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
                            <th>Customer</th>
                            <th>Sales Date</th>
                            <th>Payment Ref</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        //DETAILS FOR RECEIPTS INCLUDED IN SELECT STATEMENT
                        $sSelect = "select payment.paymentid,payment.paymentdate,orderdetails.orderid,SUM(unit_price*orderdetails.quantity) AS total_price,concat(customer.first_name,\" \",customer.last_name) AS customerName
                                    from transactions,customer,orders,orderdetails,product,payment
                                    where transactions.customerid=customer.customerid
                                    AND orders.transactionid=transactions.transactionid
                                    AND orders.transactionid=orderdetails.orderid
                                    AND product.productid=orderdetails.productid
                                    AND transactions.paymentid=payment.paymentid
                                    GROUP BY orders.transactionid
                                    ORDER BY orderdetails.orderid;";
                        $result = $conn->query($sSelect);
                        $rowcount = $result->rowcount();
                        // echo $rowcount;
                        if ($rowcount == 0) {
                            echo "<td colspan=\"100%\" style=\"text-align: center;\"> No Order Request on file </td>";
                        } else {
                            while ($row = $result->fetch()) {
                                echo "<tr>";

                                echo "<td>#" . $row["orderid"] . "</td>";
                                echo "<td class='customer_name'>" . $row["customerName"] . "</td>";
                                echo "<td>" . $row["paymentdate"] . "</td>";
                                echo "<td>#" . $row["paymentid"] . "</td>";
                                echo "<td id = " . $row["orderid"] . " class = \"clickReceipt\" style=\"cursor:pointer; color:#003f87;font-weight: 500;\">Click here for complete receipt</td>";

                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
    </main>

    </div>
    <!--end of main div-->
    </div>
    <!--end of wrapper div-->
</body>

</html>