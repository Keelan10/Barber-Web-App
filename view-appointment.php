<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View-appointment</title>
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
$activeMenu = "appointments";
include_once("includes/admin-menu.php");
require_once("includes/database.php");

?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $(".clickReceipt").click(function() {
            const transacID = $(this).attr('id');
            const url = "viewAppointmentAjax.php";
            const customer_name =  $(this).siblings(".customer_name").text()
            const stylist = $(this).siblings(".stylist").text()

            $.ajax({
                    url: url,
                    data: {
                        transactionID: transacID
                    },
                    accepts: 'application/json',
                    method: "POST",
                    error: function(xhr) {
                        alert("An error occured: " + xhr.status + " " + xhr.statusText);
                    }
                })
                .done(function(data) {
                    var result = "";
                    var sum = 0;
                    var duration=0;

                    
                    $("span#customer-name").html(customer_name)
                    $("#stylist_name").text(stylist);
                    $("span#transactionID").html(transacID)
                    $("span#appointment_datetime").html(data[0]['appointment_datetime'])
                    $("span#paymentDate").html(data[0]['payment_date'])
                    if (data[0].is_cancelled) $("#cancelled").show();
                    else $("#cancelled").hide();
                    // console.log(data)
                    $.each(data, function(i, obj) {
                        duration+=obj.duration
                        sum += parseInt(obj['price'])
                        result +=
                        `<tr>
                        <td>${obj['name']}</td>
                        <td class="alignright">Rs ${parseInt(obj['price'])}</td>
                        </tr>`
                    });
                    result +=
                    `<tr class="total">
                    <td class="alignright" width="80%">Total</td>
                    <td class="alignright">Rs ${sum}</td>
                    </tr>`;
                    $("#duration").html(duration+" mins")
                    $(".items-container").html(result)
                    
                })
            $(".overlay").show();
        });
        $(".fa-times").click(function() {
            $(".overlay").hide();
        });
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
                                            <h2>Appointment Info</h2>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content-block">
                                            <table class="invoice">
                                                <tbody class="receipt-body">
                                                    <tr>
                                                        <th>
                                                            <span id="customer-name"></span>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            Transaction #<span id="transactionID"><span>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th style="color:red" id="cancelled">
                                                            Cancelled
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            Stylist : <span id="stylist_name"><span>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            Appointment Date/Time : <span id="appointment_datetime"><span>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            Duration : <span id="duration"><span>
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
                <h1 class="h3 d-inline align-middle">View Appointments</h1>
            </div>
            <div class="card flex-fill">
                <div class="card-header">
                    <h5 class="card-title mb-0">Appointment Details</h5>
                </div>

                <table class="table table-hover my-0">
                    <thead>
                        <tr>
                            <th>Transaction Ref</th>
                            <th>Customer</th>
                            <th>Appointment Date</th>
                            <th>Time</th>
                            <th>Stylist</th>
                            <th>Payment Ref</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        //DETAILS FOR RECEIPTS INCLUDED IN SELECT STATEMENT
                        $sSelect = "SELECT appointment.transactionid,concat(customer.first_name,\" \",customer.last_name) as customerName,appointmentdate,start_time,concat(barber.first_name,\" \",barber.last_name) AS stylist,transactions.paymentid
                                    FROM appointment,payment,transactions,barber,customer
                                    WHERE transactions.transactionid=appointment.transactionid
                                    AND transactions.paymentid=payment.paymentid
                                    AND barber.barberid=appointment.barberid
                                    AND transactions.customerid = customer.customerid
                                    ORDER BY appointmentdate DESC, start_time
                                    ;";
                        $result = $conn->query($sSelect);
                        $rowcount = $result->rowcount();
                        // echo $rowcount;
                        if ($rowcount == 0) {
                            echo "<td colspan=\"100%\" style=\"text-align: center;\"> No Appointment Request on file </td>";
                        } else {
                            while ($row = $result->fetch()) {
                                echo "<tr>";

                                echo "<td>" . $row["transactionid"] . "</td>";
                                echo "<td class='customer_name'>" . $row["customerName"] . "</td>";
                                echo "<td>" . $row["appointmentdate"] . "</td>";
                                echo "<td>" . $row["start_time"] . "</td>";
                                echo "<td class='stylist'>" . $row["stylist"] . "</td>";
                                echo "<td>" . $row["paymentid"] . "</td>";
                                echo "<td id = " . $row["transactionid"] . " class = \"clickReceipt\" style=\"cursor:pointer; color:#003f87;font-weight: 500;\">Click here for complete info</td>";

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
