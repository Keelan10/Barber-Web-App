<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add-service</title>


    <?php
    $activeMenu = "services";
    include_once("includes/admin-menu.php");

    $serviceNameErr = $serviceDescErr = $servicePriceErr = $serviceDurationErr = "";
    $serviceName = $serviceDesc = $servicePrice = $serviceDuration = "";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        if (empty($_POST['Servicename'])) {
            $serviceNameErr = "Service name is required.";
            //echo  $productnameErr;
        } else {
            $serviceName = test_input($_POST['Servicename']);
            if (!preg_match("/^[A-Z][a-z]+(['\-\s][A-Z][a-z]+)*$/", $serviceName)) {
                $serviceNameErr = "Service name should start with a capital letter and consists of alphabets only.";
                //echo  $productnameErr;
            }
        }

        if (empty($_POST['Price'])) {
            $servicePriceErr = "Price of service is required.";
            //echo $priceErr;
        } else {
            $servicePrice = test_input($_POST['Price']);
            if (!filter_var($servicePrice, FILTER_VALIDATE_FLOAT)) {
                $servicePriceErr = "Price of service should be numeric.";
                //echo $priceErr;
            }
        }

        if (empty($_POST['Duration'])) {
            $serviceDurationErr = "Duration of service is required.";
            //echo $quantityErr;
        } else {
            $serviceDuration = test_input($_POST['Duration']);
            if (!filter_var($serviceDuration, FILTER_VALIDATE_FLOAT)) {
                $serviceDurationErr = "Duration of product should be numeric.";
                //echo $quantityErr;
            }
        }

        if (empty($_POST['Description'])) {
            $serviceDescErr = "Description of service is required.";
            //echo $weightErr;
        } else {
            $serviceDesc = test_input($_POST['Description']);
        }

        if ($serviceNameErr == ""  && $serviceDescErr == ""  && $servicePriceErr == ""  && $serviceDurationErr == "") {
            require_once "includes/database.php";

            $sInsert = "INSERT INTO service (name,price,duration,description) VALUES ("
                . $conn->quote($serviceName) . ","
                . $conn->quote($servicePrice) . ","
                . $conn->quote($serviceDuration) . ","
                . $conn->quote($serviceDesc)  . ")";

            $addResult = $conn->exec($sInsert);
            if ($addResult) {
                $serviceNameErr = $serviceDescErr = $servicePriceErr = $serviceDurationErr = "";
                $serviceName = $serviceDesc = $servicePrice = $serviceDuration = "";
                //header("Location:../add-product.php?uploaded");
            } else {
                $serviceNameErr = $serviceDescErr = $servicePriceErr = $serviceDurationErr = "";
                $serviceName = $serviceDesc = $servicePrice = $serviceDuration = "";
                //header("Location:../add-product.php?failure");
            }
        }
    }

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


    ?>

    <main class="content">
        <div class="container-fluid p-0">

            <div class="mb-3">
                <h1 class="h3 d-inline align-middle">Add Service</h1>
            </div>
            <form method="post" enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Service Name
                                    <?php if ($serviceNameErr != "") {
                                        echo "<p class = 'error'>$serviceNameErr</p>";
                                    } ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <input type="text" class="form-control" placeholder="Name of service" name="Servicename" value="<?php if ($serviceNameErr == "") {
                                                                                                                                    echo $serviceName;
                                                                                                                                } ?>" pattern="[A-Z][a-z]+(['\-\s][A-Z][a-z]+)*" required>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Description
                                    <?php if ($serviceDescErr != "") {
                                        echo "<p class = 'error'>$serviceDescErr</p>";
                                    } ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <textarea class="form-control" rows="4" cols="50" name="Description" required><?php if ($serviceDescErr == "") {
                                                                                                                    echo $serviceDesc;
                                                                                                                } ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Price
                                    <?php if ($servicePriceErr != "") {
                                        echo "<p class = 'error'>$servicePriceErr</p>";
                                    } ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <input type="number" class="form-control" placeholder="Price of service" name="Price" min="0.00" max="10000.00" step="0.01" value="<?php if ($servicePriceErr == "") {
                                                                                                                                                                        echo $servicePrice;
                                                                                                                                                                    } ?>" required>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Duration
                                    <?php if ($serviceDurationErr != "") {
                                        echo "<p class = 'error'>$serviceDurationErr</p>";
                                    } ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <input type="number" class="form-control" placeholder="Duration of service" name="Duration" min="1" value="<?php if ($serviceDurationErr == "") {
                                                                                                                                                echo $serviceDuration;
                                                                                                                                            } ?>" required>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="submit" value="Ok" class="form-control ok-btn" style="width: 10%;">
            </form>
        </div>
    </main>
    </div>
    <!--end of main div-->
    </div>
    <!--end of wrapper div-->
    </body>

</html>