<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <title>Admin</title>

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <script src="https://unpkg.com/counterup2@2.0.2/dist/index.js"> </script>

    <?php
    $activeMenu = "dashboard";
    include_once("includes/admin-menu.php");
    require_once("includes/database.php");
    ?>

    <style>
        .statistic-section {
            padding-top: 70px;
            padding-bottom: 70px;
            background: #00c6ff;
            /* fallback for old browsers */
            background: -webkit-linear-gradient(to right, #0072ff, #00c6ff);
            background: linear-gradient(to right, #0072ff, #00c6ff);
        }

        .count-title {
            font-size: 50px;
            font-weight: normal;
            margin-top: 10px;
            margin-bottom: 0;
            text-align: center;
            font-weight: bold;
            color: #fff;
        }

        .stats-text {
            font-size: 15px;
            font-weight: normal;
            margin-top: 15px;
            margin-bottom: 0;
            text-align: center;
            color: #fff;
            text-transform: uppercase;
            font-weight: bold;
        }

        .stats-line-black {
            margin: 12px auto 0;
            width: 55px;
            height: 2px;
            background-color: #fff;
        }

        .stats-icon {
            font-size: 35px;
            margin: 0 auto;
            float: none;
            display: table;
            color: #fff;
        }

        @media (max-width: 992px) {
            .counter {
                margin-bottom: 40px;
            }
        }

        #barcharts {
            display: grid;
            grid-template-columns: 1fr 2fr;
            grid-gap: 1rem;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        .stati {
            background: #fff;
            height: 6em;
            padding: 1em;
            margin: 1em 0;
            -webkit-transition: margin 0.5s ease, box-shadow 0.5s ease;
            /* Safari */
            transition: margin 0.5s ease, box-shadow 0.5s ease;
            -moz-box-shadow: 0px 0.2em 0.4em rgb(0, 0, 0, 0.8);
            -webkit-box-shadow: 0px 0.2em 0.4em rgb(0, 0, 0, 0.8);
            box-shadow: 0px 0.2em 0.4em rgb(0, 0, 0, 0.8);
        }

        .stati:hover {
            margin-top: 0.5em;
            -moz-box-shadow: 0px 0.4em 0.5em rgb(0, 0, 0, 0.8);
            -webkit-box-shadow: 0px 0.4em 0.5em rgb(0, 0, 0, 0.8);
            box-shadow: 0px 0.4em 0.5em rgb(0, 0, 0, 0.8);
        }

        .stati i {
            font-size: 3.5em;
        }

        .stati div {
            width: calc(100% - 3.5em);
            display: block;
            float: right;
            text-align: right;
        }

        .stati div b {
            font-size: 2.2em;
            width: 100%;
            padding-top: 0px;
            margin-top: -0.2em;
            margin-bottom: -0.2em;
            display: block;
        }

        .stati div span {
            font-size: 1em;
            width: 100%;
            color: rgb(0, 0, 0, 0.8);
            display: block;
        }

        .stati.left div {
            float: left;
            text-align: left;
        }

        .stati.wet_asphalt {
            color: rgb(52, 73, 94);
        }

        .stati.turquoise {
            color: rgb(26, 188, 156);
        }

        .stati.emerald {
            color: rgb(46, 204, 113);
        }

        .stati.peter_river {
            color: rgb(52, 152, 219);
        }

        .stati.amethyst {
            color: rgb(155, 89, 182);
        }

        .stati.orange {
            color: rgb(243, 156, 18);
        }

        .stati.pumpkin {
            color: rgb(211, 84, 0);
        }
    </style>
    <?php
    $sqlBarber = "SELECT COUNT(barberid) as num From barber";
    $Result = $conn->query($sqlBarber);
    $numbarber = $Result->fetch(PDO::FETCH_ASSOC);

    $sqlProducts = "SELECT COUNT(productid) as num From product";
    $Result = $conn->query($sqlProducts);
    $numproduct = $Result->fetch(PDO::FETCH_ASSOC);

    $sqlServices = "SELECT COUNT(serviceid) as num From service";
    $Result = $conn->query($sqlServices);
    $numservice = $Result->fetch(PDO::FETCH_ASSOC);

    $sqlClients = "SELECT COUNT(customerid) as num From customer";
    $Result = $conn->query($sqlClients);
    $numclient = $Result->fetch(PDO::FETCH_ASSOC);
    ?>
    <main class="content">
        <div class="container-fluid p-0">
            <div class="mb-3">
                <h1 class="h3 d-inline align-middle">Admin Dashboard</h1>
            </div>
            <section id="statistic" class="statistic-section one-page-section">
                <div class="container">
                    <div class="row text-center">
                        <div class="col-xs-12 col-md-3">
                            <div class="counter">
                                <i class="fa fa-address-book-o fa-2x stats-icon"></i>
                                <h2 class="timer count-title count-number"><?php echo $numclient['num'] ?></h2>
                                <div class="stats-line-black"></div>
                                <p class="stats-text">Number of Clients</p>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="counter">
                                <i class="fa fa-scissors fa-2x stats-icon"></i>
                                <h2 class="timer count-title count-number"><?php echo $numservice['num'] ?></h2>
                                <div class="stats-line-black"></div>
                                <p class="stats-text">Number of services</p>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="counter">
                                <i class="fas fa-store-alt fa-2x stats-icon"></i>
                                <h2 class="timer count-title count-number"><?php echo $numproduct['num'] ?></h2>
                                <div class="stats-line-black"></div>
                                <p class="stats-text">Number of products</p>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="counter">
                                <i class="fas fa-user-tie fa-2x stats-icon"></i>
                                <h2 class="timer count-title count-number"><?php echo $numbarber['num'] ?></h2>
                                <div class="stats-line-black"></div>
                                <p class="stats-text">Number of barbers</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section id="barcharts">
                
                    <canvas id="myChart" style="width:100%;max-width: 400px;height:400px"></canvas>
                    <script>
                        $(document).ready(function() {
                            const counterUp = window.counterUp.default
                            const callback = entries => {
                                entries.forEach(entry => {
                                    const el = entry.target
                                    if (entry.isIntersecting && !el.classList.contains('is-visible')) {
                                        counterUp(el, {
                                            duration: 2000,
                                            delay: 16,
                                        })
                                        el.classList.add('is-visible')
                                    }
                                })
                            }
                            const IO = new IntersectionObserver(callback, {
                                threshold: 1
                            })
                            $('.counter').each(function(i, el) {
                                IO.observe(el)
                            })

                            var arrbarber = [];
                            var arrbarberclient = [];
                            $.ajax({
                                    url: "chartAjax/getbarberclient.php",
                                    data: {},
                                    method: "POST",
                                    accepts: "application/json",
                                    error: function(xhr) {}
                                })
                                .done(function(data) {
                                    $.each(data, function(i, obj) {
                                        arrbarber.push(obj.barbername);
                                        arrbarberclient.push(obj.appointments);
                                    })
                                    // var xValues = ["Italy", "France", "Spain", "USA", "Argentina"];
                                    var xValues = arrbarber;
                                    // var yValues = [2, 5, 3];
                                    var yValues = arrbarberclient;

                                    new Chart(document.getElementById("myChart"), {
                                        type: 'bar',
                                        data: {
                                            labels: arrbarber,
                                            datasets: [{
                                                label: "Number of clients",
                                                backgroundColor: ["#3e95cd", "#8e5ea2", "#3cba9f", "#e8c3b9", "#c45850"],
                                                data: arrbarberclient
                                            }]
                                        },
                                        options: {
                                            legend: {
                                                display: false
                                            },
                                            title: {
                                                display: true,
                                                text: 'Number of clients per barber'
                                            },
                                            scales: {
                                                yAxes: [{
                                                    ticks: {
                                                        beginAtZero: true
                                                    }
                                                }]
                                            }
                                        }
                                    });
                                })
                        });
                    </script>
                

                <?php

                use Opis\JsonSchema\{
                    Validator,
                    ValidationResult,
                    ValidationError,
                    Schema
                };

                require 'vendor/autoload.php';

                $url = 'http://localhost/Barber-Web-App/chartAjax/getproductclient.php';
                $json = file_get_contents($url);
                $obj = json_decode($json, false);
                // echo $json;
                // die;

                $schema = Schema::fromJsonString(file_get_contents('schema/adminJSONSchema.json'));

                $validator = new Validator();

                /** @var ValidationResult $result */
                $result = $validator->schemaValidation($obj, $schema);

                if (!$result->isValid()) {
                    /** @var ValidationError $error */
                    $error = $result->getErrors();
                    echo '$data is invalid', PHP_EOL;
                    echo "<br>";
                    foreach ($error as $key => $value) {
                        # code...
                        echo "Error: ", $value->keyword(), PHP_EOL;
                        echo json_encode($value->keywordArgs(), JSON_PRETTY_PRINT), PHP_EOL;
                        echo "<br>";

                    }
                    //echo "Error: ", $error->keyword(), PHP_EOL;
                    //echo json_encode($error->keywordArgs(), JSON_PRETTY_PRINT), PHP_EOL;
                } else {
                    $array_product = array();
                    $array_quantity = array();
                    foreach ($obj as $data) {
                        array_push($array_product, $data->product_name);
                        array_push($array_quantity, $data->Quantity);
                    }
                    // print_r($array_product);
                    // print_r($array_quantity);
                }

                ?>

                    <canvas id="bar-chart-horizontal" style="max-width:100%;"></canvas>
                    <script>
                        $(document).ready(function() {
                            var arrproduct = [];
                            var arrproduct = <?php echo json_encode($array_product) ?>;
                            var arrproductclient = <?php echo json_encode($array_quantity) ?>;

                            console.log(arrproduct)

                            new Chart(document.getElementById("bar-chart-horizontal"), {
                                type: 'horizontalBar',
                                data: {
                                    labels: arrproduct,
                                    datasets: [{
                                        label: "Number sold",
                                        backgroundColor: ["#3e95cd", "#8e5ea2", "#3cba9f", "#e8c3b9", "#c45850", "#c55850"],
                                        barPercentage: 0.3,
                                        barThickness: 2,
                                        maxBarThickness: 8,
                                        minBarLength: 2,
                                        data: arrproductclient
                                    }]
                                },
                                options: {
                                    legend: {
                                        display: false
                                    },
                                    title: {
                                        display: true,
                                        text: 'Number of products sold'
                                    },
                                    scales: {
                                        xAxes: [{
                                            ticks: {
                                                beginAtZero: true
                                            }
                                        }]
                                    }
                                }
                            });
                        })
                    </script>
                
            </section>
            <?php
            //SALES REVENUE
            $sql = "SELECT product.product_name,orderdetails.productid,SUM(orderdetails.quantity)*orderdetails.unit_price AS price,SUM(orderdetails.quantity)as Quantity,orderdetails.unit_price
                        FROM orderdetails, product
                        WHERE product.productid = orderdetails.productid
                        GROUP BY orderdetails.productid;";
            $result = $conn->query($sql);
            $salesRevenue = 0;
            while ($salesdata = $result->fetch()) {
                $salesRevenue += $salesdata['price'];
            }

            //SERVICES REVENUE
            $sql = "SELECT sum(price) as services FROM appointmentdetails;";
            $result = $conn->query($sql);
            $servicesData = $result->fetch();
            $servicesRevenue = 0;
            $servicesRevenue = (int) $servicesData['services'];

            //GET NUMBER OF BARBER WHO WORKED TILL NOW
            $sql = "SELECT concat(barber.first_name,\" \",barber.last_name) as barbername,barber.barberid,COUNT(appointment.barberid) as appointments
                    FROM appointment,barber
                    WHERE appointment.barberid = barber.barberid
                    GROUP BY barber.barberid;";
            $result = $conn->query($sql);
            $barbernum = $result->rowCount();
            $totalRevenue = $servicesRevenue + $salesRevenue;
            $avg_per_barber = (int) ($totalRevenue / $barbernum);
            ?>
            <section class="stats-num">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="stati turquoise ">
                                <i class="fa fa-dollar icons"></i>
                                <div>
                                    <b>Rs <?php echo $salesRevenue ?></b>
                                    <span>Sales Revenue</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stati peter_river ">
                                <i class="fa fa-dollar icons"></i>
                                <div>
                                    <b>Rs <?php echo $servicesRevenue ?></b>
                                    <span>Services Revenue</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stati pumpkin ">
                                <i class="fa fa-dollar icons"></i>
                                <div>
                                    <b>Rs <?php echo $totalRevenue ?></b>
                                    <span>Total Revenue</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stati wet_asphalt ">
                                <i class="fa fa-dollar icons"></i>
                                <div>
                                    <b>Rs <?php echo $avg_per_barber ?></b>
                                    <span>Revenue/Barber</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>


</html>