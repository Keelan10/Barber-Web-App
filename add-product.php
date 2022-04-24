<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add-product</title>

    
    <?php
        $activeMenu="catalogue";
        include_once("includes/admin-menu.php");

        $productnameErr = $priceErr = $quantityErr = $weightErr = $categoryErr = $fileErr = "";
        $productname = $price = $quantity = $weight = $category = "";

        if ($_SERVER['REQUEST_METHOD'] == "POST"){

            if (empty($_POST['Productname'])){
                $productnameErr = "Product name is required.";
                //echo  $productnameErr;
            }
            else {
                $productname = test_input($_POST['Productname']);
                if (!preg_match("/^[A-Z][a-z]+(['\-\s][A-Z][a-z]+)*$/",$productname)){
                    $productnameErr = "Product name should start with a capital letter and consists of alphabets only.";
                    //echo  $productnameErr;
                }
            }

            if (empty($_POST['Price'])){
                $priceErr = "Price of product is required.";
                //echo $priceErr;
            }
            else{
                $price = test_input($_POST['Price']);
                if (!filter_var($price,FILTER_VALIDATE_FLOAT)){
                    $priceErr = "Price of product should be numeric.";
                    //echo $priceErr;
                }
            }

            if (empty($_POST['Quantity'])){
                $quantityErr = "Quantity of product is required.";
                //echo $quantityErr;
            }
            else{
                $quantity = test_input($_POST['Quantity']);
                if (!filter_var($quantity,FILTER_VALIDATE_FLOAT)){
                    $quantityErr = "Quantity of product should be numeric.";
                    //echo $quantityErr;
                }
            }

            if (empty($_POST['Weight'])){
                $weightErr = "Weight of product is required.";
                //echo $weightErr;
            }
            else{
                $weight = test_input($_POST['Weight']);
                if (!filter_var($weight,FILTER_VALIDATE_FLOAT)){
                    $weightErr = "Weight of product should be numeric.";
                    //echo $weightErr;
                }
            }

            if (empty($_POST['Category'])){
                $categoryErr = "Category of product is required.";
                //echo $categoryErr;
            }
            else{
                $category = test_input($_POST['Category']);
                if (!preg_match("/^[a-z]+$/",$category)){
                    $categoryErr = "Category of wrong format";
                    //echo $categoryErr;
                }
            }


            $fileName=$_FILES['file']['name'];
            $fileType=$_FILES['file']['type'];
            $tmp_name=$_FILES['file']['tmp_name'];
            $error=$_FILES['file']['error'];
            $size=$_FILES['file']['size'];

            $tempExtension=explode('.',$fileName);

            $fileExtension= strtolower(end($tempExtension));

            //a list of allowed extensions
            $isAllowed=array('jpg','jpeg','png');

            if (in_array($fileExtension,$isAllowed)){
                if ($error===0){
                    if ($size<300000){
                        $newFileName = uniqid("",false).".$fileExtension";
                        $fileDestination="../images/product/".$newFileName;
                        move_uploaded_file($tmp_name,$fileDestination);
                        // header("Location:./add-product.php?uploaded");

                    }
                    else{
                        $fileErr = "Size is too big";
                        //echo $fileErr;
                    }
                }
                else{
                    $fileErr = "An error has occurred";
                    //echo $fileErr;
                }
            }
            else{
                $fileErr = "Not among allowed extensions";
                //echo $fileErr;
            }
        }

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        if ($productnameErr == ""  && $priceErr == ""  && $quantityErr == ""  && $weightErr == ""  && $categoryErr == ""  && $fileErr == ""){
            // require_once "includes/database.php";

            // $sInsert = "INSERT INTO product (product_name,price,weight,quantity,category,image_name) VALUES ("
            //             .$conn->quote($productname).","
            //             .$conn->quote($price).","
            //             .$conn->quote($weight).","
            //             .$conn->quote($quantity).","
            //             .$conn->quote($category).","
            //             .$conn->quote($newFileName).")";

            // $addResult=$conn->exec($Insert);
            // if ($addResult){
            //     header("Location:../add-product.php?uploaded");
            // }
            // else{
            //     header("Location:../add-product.php?failure");
            // }

        }
    ?>

    <main class="content">
                <div class="container-fluid p-0">

                    <div class="mb-3">
                        <h1 class="h3 d-inline align-middle">Add product</h1>
                    </div>
                    <form method="post" enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"];?>">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Product Name
                                        <?php if ($productnameErr != ""){ echo "<p class = 'error'>$productnameErr</p>";}?>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <input type="text" class="form-control" placeholder="Name of product" name ="Productname" value="<?php if ($productnameErr == ""){echo $productname;} ?>">
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Price
                                        <?php if ($priceErr != ""){ echo "<p class = 'error'>$priceErr</p>";}?>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <input type="number" class="form-control" placeholder="Unit Price of product" name="Price" min="1" value = "<?php if ($priceErr == ""){echo $price;} ?>">
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Quantity
                                        <?php if ($quantityErr != ""){ echo "<p class = 'error'>$quantityErr</p>";}?>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <input type="number" class="form-control" placeholder="Quantity" name ="Quantity" min="1" value = "<?php if ($quantityErr == ""){echo $quantity;}?>">
                                </div>
                            </div>

                        </div>

                        <div class="col-12 col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Weight
                                        <?php if ($weightErr != ""){ echo "<p class = 'error'>$weightErr</p>";}?>
                                    </h5>
                                </div>
                                <div class="card-body">
                                <input type="number" class="form-control" placeholder="Weight of Product" name="Weight" min="1" value = "<?php if ($weightErr == ""){echo $weight;} ?>">
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Category
                                        <?php if ($categoryErr != ""){ echo "<p class = 'error'>$categoryErr</p>";}?>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <input type="text" class="form-control" placeholder="Category of Product" name="Category" value = "<?php if ($categoryErr == ""){echo $category;}?>">
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Upload file
                                        <?php if ($fileErr != ""){ echo "<p class = 'error'>$fileErr</p>";}?>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <input type="file" name="file" value = "<?php if ($fileErr == ""){echo $newFileName;} ?>" ><br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="submit" value="Ok" class="form-control ok-btn" style="width: 10%;">
                    </form>
                </div>
            </main>
        </div><!--end of main div-->
    </div><!--end of wrapper div-->
</body>

</html>