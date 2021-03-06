<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase History</title>
    <!-- SWEET ALERT -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        .content-block{
            padding: 0 0 20px;
            text-align: center;
        }

        .alignright{
            text-align: right;
        }

        table h2{
            font-size: 24px;
        }
    </style>
    <?php
    $active_menu = "purchase";
    include "includes/customer-menu.php";
    ?>

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
                                            <h2>Thanks for your purchase</h2>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content-block">
                                            <table class="invoice">
                                                <tbody class="receipt-body">
                                                    <tr>
                                                        <td><span id="customer-name"><?= $_SESSION["customer_username"]?></span><br>Invoice #<span id="orderNo"><span><br><span id="date"><span></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="color:red" id="cancelled"></td>
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
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
    <div class="content">
        <h1>Purchase history</h1>
        <table class="order-list">
            <thead>
                <tr>
                    <th style="width:10%">Order No</th>
                    <th style="width:10%">Sale Date</th>
                    <th>Details</th>
                    <th style="width:8%">Amount paid</th>
                    <th style="width:10%">Payment Ref</th>
                    <th style="width:8%">Cancel</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once("includes/database.php");
                
                $sql =
                    "select payment.paymentid,payment.paymentdate,orderdetails.orderid,SUM(unit_price*orderdetails.quantity) AS total_price,picked, is_cancelled
                    from transactions,customer,orders,orderdetails,product,payment
                    where customer.customerid=" . $conn->quote($_SESSION["customer_userid"]) . "
                    AND transactions.customerid=customer.customerid
                    AND orders.transactionid=transactions.transactionid
                    AND orders.transactionid=orderdetails.orderid
                    AND product.productid=orderdetails.productid
                    AND transactions.paymentid=payment.paymentid
                    GROUP BY orders.transactionid
                    ORDER BY paymentdate DESC,orderid DESC;
                    ";


                $Results = $conn->query($sql);
                $numrows = $Results->rowCount();

                if ($numrows == 0) {
                    echo "<tr style=\"background-color: #F2F2F2\">
                            <td colspan=\"100%\" style=\"text-align: center;background-color:#F2F2F2\"> No Purchases on file </td>
                            </tr>";
                } else {
                    $i = 0;
                    while ($row = $Results->fetch()) {
                        if ($i == 0) {
                            echo "<tr class=\"even\">";
                            $i = 1;
                        } else {
                            echo "<tr>";
                            $i = 0;
                        }

                        echo "<td>#" . $row["orderid"] . "</td>";
                        echo "<td>" . $row["paymentdate"] . "</td>";
                        echo "<td style=\"cursor:pointer; color:#003f87;font-weight: 500;\" id=\"" . $row["orderid"] . "\" class='click-receipt'>Click here for complete receipt</td>";
                        echo "<td>Rs <span class='price-paid'>" . round($row["total_price"], 0) . "</span></td>";
                        echo "<td>#" . $row["paymentid"] . "</td>";
                        if (!$row["picked"] && !$row["is_cancelled"]){
                            echo "<td style=\"cursor:pointer; color:#003f87;font-weight: 500;\" id=\"" . $row["orderid"] . "\" class='cancel-order'>Cancel</td>";
                        }else{
                            echo "<td></td>";
                        }
                        echo "</tr>";
                    }
                }


                ?>
            </tbody>
        </table>
    </div>
    </body>
    <script>
        $("body").ready(function() {

            <?php
                if(isset($_GET["referrer"]) && $_GET["referrer"]=="shop"){
                    echo "Swal.fire(
                        'Purchase successful!',
                        'Thank you for your purchase.',
                        'success'
                      )";
                }
            ?>

            $(".click-receipt").click(function(event) {
                const orderId = $(event.currentTarget).attr("id")
                $("#orderNo").text(orderId)

                // console.log("here")
                $.ajax({
                    url: "getPurchasedItems.php",
                    type:"POST",
                    data: {orderId: orderId},
                    dataType: "JSON",
                    success: function(data) {
                        // console.log(data)
                        var result =""
                        var sum=0;
                        
                        for (var i = 0; i < data.length; i++) {
                            sum+=parseInt(data[i].totalPerProduct)
                            result+=
                            `<tr>
                                <td>${data[i].product_name} (x${data[i].quantity})</td>
                                <td class="alignright">Rs ${parseInt(data[i].totalPerProduct)}</td>
                                </tr>`
                                
                            }
                            
                        // console.log(sum)
                        result+=
                        `<tr class="total">
                        <td class="alignright" width="80%">Total</td>
                        <td class="alignright">Rs ${sum}</td>
                        </tr>`;
                        // console.log(result)
                        $(".items-container").html(result)
                        $("#date").text(data[0].date)
                        if(data[0].is_cancelled) $("#cancelled").text("Cancelled")
                        else $("#cancelled").text("")
                        
                        $(".overlay").show()
                    }
                })

            })

            $(".fa-times").click(function() {
                $(".overlay").hide()
            })

            $(".cancel-order").click(function(){
                const transactionid= $(this).attr("id");
                const cancel = this
                const pricePaid = $(this).siblings().children(".price-paid")


                Swal.fire({
                title: 'Are you sure?',
                text: "Note that you will only get 70% refund!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    $.post("cancelTransaction.php", {transactionid: transactionid, option: "order"}, function(data){
                        if (data=="success"){
                            pricePaid.text((0.3* parseInt(pricePaid.text()).toFixed(0)))
                            $(cancel).replaceWith("<td></td>");
                            Swal.fire(
                                'Cancelled!',
                                'Your order has been cancelled.',
                                'success'
                                )
                        }
                        else{
                            Swal.fire(
                                'Error!',
                                'An error occurred',
                                'error'
                                )
                        }
                    })

                    

                }
                })
            })
        })
    </script>

</html>