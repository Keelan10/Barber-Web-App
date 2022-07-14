<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Catalogue</title>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/c8ee3dd930.js"></script>
    <style>
        .products-container {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            grid-gap: 1rem;
        }

        .product-image-container {
            padding: 2rem;
            /* width: 100px; */
            height: 150px;
        }

        .row-head {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }

        article.card {
            padding: 0.25rem 1rem;
        }
    </style>

    <?php
    // $_SESSION["username"] = "Tom Cruise";
    $activeMenu = "services";
    require_once("includes/database.php");
    include_once("includes/admin-menu.php");
    ?>
    <div class="overlay">

    </div>
    <div class="modal">
        <div class="header">
            <h3>Confirmation</h3>
        </div>
        <div class="message">
            <p>
                Are you sure you wanna delete the following service?<span id="service-name"></span>
            </p>
        </div>
        <div class="choice">
            <i class="fas fa-check" style="color:green"></i>
            <i class="fas fa-times" style="color: red"></i>
        </div>
    </div>

    <main class="content">
        <div class="container-fluid p-0">

            <div class="mb-3">
                <h1 class="h3 d-inline align-middle">Edit Services and pricing</h1>
            </div>




            <?php
            $sql = "SELECT * FROM service";
            $result = $conn->query($sql);
            $rowCount = $result->rowCount();

            if ($rowCount) {
                echo '<div class="products-container">';
                while ($row = $result->fetch()) :
            ?>

                    <article class="card">
                        <i class="fas fa-check" class="tickUpdate" style="color:green;padding-top:1rem;text-align:right;display:none;"></i>
                        <div class="info">
                            <p style="font-size:20px;line-height:1.5em;letter-spacing:0.5px;margin-top:1rem;" class="service-name" contenteditable="true" spellcheck=”false”><?= $row['name']; ?></p>
                            <div class="desc">
                                <p class="servDesc" style="letter-spacing:0.5px;text-align: justify;" contenteditable="true" spellcheck=”false”><?= $row['description']; ?></p>
                                <h6 style="letter-spacing:0.5px;color:#BEB8B6 ;">Duration: <span class="duration" contenteditable="true" spellcheck=”false”><?php echo $row["duration"] ?></span> min</h6>
                            </div>
                            <div class="row-head">
                                <h6 style="letter-spacing:0.5px">Rs<span id="price" class="<?php echo $row["serviceid"] ?>" contenteditable="true" spellcheck=”false”> <?php echo $row["price"] ?></span></h6>
                                <i class="fa fa-trash delete-service" id="<?php echo $row["serviceid"] ?>" style="cursor:pointer"></i>
                            </div>
                        </div>
                        <?php
                        ?>
                    </article>
                <?php endwhile;
            } else { ?>

                <div class="card" style="height: 100%;">
                    No services to be displayed
                    <a href="add-product.php">Click here to add</a>
                </div>

            <?php } //end else 
            ?>

        </div>

        </div>
    </main>
    </div>
    <!--end of main div-->
    </div>
    <!--end of wrapper div-->

    </body>

    <script>
        $(document).ready(function() {

            $("i.delete-service").click(function() {
                const article = $(this).parents("article")
                var serviceID = $(this).attr('id');
                var servicename = $(this).parentsUntil(".card").find(".service-name").text();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "deleteServices.php",
                            data: {
                                serviceID: serviceID
                            },
                            method: "POST",
                            error: function(xhr) {
                                alert("An error occrured" + xhr.status + " " + xhr.statusText)
                            },
                            success: function(data) {
                                if (data == "success") {
                                    Swal.fire(
                                        'Deletion successful!',
                                        'Service has been deleted',
                                        'success'
                                    )
                                    article.remove()
                                } else if (data == "failure") {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Something went wrong!',
                                    })
                                }
                            }
                        })
                    }
                })
            })

            //Name Changed
            $("p.service-name").blur(function() {
                $(this).parents(".card").children(".fa-check").show();
                TickUpdate = $(this).parents(".card").children(".fa-check");
                var serviceID = $(this).parentsUntil("div#info").children(".row-head").children("i").attr('id');
                var serviceName = $(this).parentsUntil("div#info").children("p.service-name").text();
                var serviceDesc = $(this).parentsUntil("div#info").children(".desc").children("p.servDesc").text();
                var serviceDura = $(this).parentsUntil("div#info").children(".desc").children("h6").children("span.duration").text();
                var servicePrice = $(this).parentsUntil("div#info").children(".row-head").children("h6").children("span#price").text();

                $(TickUpdate).click(function() {
                    Swal.fire({
                        title: 'Do you want to save the changes?',
                        showDenyButton: true,
                        showCancelButton: true,
                        confirmButtonText: 'Save',
                        denyButtonText: `Don't save`,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Swal.fire('Saved!', '', 'success')
                            $.ajax({
                                url: "updateServices.php",
                                data: {
                                    serviceID: serviceID,
                                    serviceName: serviceName,
                                    serviceDesc: serviceDesc,
                                    serviceDura: serviceDura,
                                    servicePrice: servicePrice
                                },
                                method: "POST",
                                error: function(xhr) {
                                    alert("An error occrured" + xhr.status + " " + xhr.statusText)
                                },
                                success: function(data) {
                                    if (data == "success") {
                                        Swal.fire(
                                            'Update successful!',
                                            'Service has been updated',
                                            'success'
                                        )
                                        $(TickUpdate).hide();
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Oops...',
                                            text: 'Something went wrong!',
                                        })
                                    }
                                }
                            })
                        } else if (result.isDenied) {
                            Swal.fire('Changes are not saved', '', 'info')
                        }
                    })
                })
            })

            //Description Changed
            $("p.servDesc").blur(function() {
                $(this).parents(".card").children(".fa-check").show();
                TickUpdate = $(this).parents(".card").children(".fa-check");
                var serviceID = $(this).parentsUntil("div#info").children(".row-head").children("i").attr('id');
                var serviceName = $(this).parentsUntil("div#info").children("p.service-name").text();
                var serviceDesc = $(this).parentsUntil("div#info").children(".desc").children("p.servDesc").text();
                var serviceDura = $(this).parentsUntil("div#info").children(".desc").children("h6").children("span.duration").text();
                var servicePrice = $(this).parentsUntil("div#info").children(".row-head").children("h6").children("span#price").text();

                $(TickUpdate).click(function() {
                    Swal.fire({
                        title: 'Do you want to save the changes?',
                        showDenyButton: true,
                        showCancelButton: true,
                        confirmButtonText: 'Save',
                        denyButtonText: `Don't save`,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Swal.fire('Saved!', '', 'success')
                            $.ajax({
                                url: "updateServices.php",
                                data: {
                                    serviceID: serviceID,
                                    serviceName: serviceName,
                                    serviceDesc: serviceDesc,
                                    serviceDura: serviceDura,
                                    servicePrice: servicePrice
                                },
                                method: "POST",
                                error: function(xhr) {
                                    alert("An error occrured" + xhr.status + " " + xhr.statusText)
                                },
                                success: function(data) {
                                    if (data == "success") {
                                        Swal.fire(
                                            'Update successful!',
                                            'Service has been updated',
                                            'success'
                                        )
                                        $(TickUpdate).hide();
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Oops...',
                                            text: 'Something went wrong!',
                                        })
                                    }
                                }
                            })
                        } else if (result.isDenied) {
                            Swal.fire('Changes are not saved', '', 'info')
                        }
                    })
                })
            })

            //Price Changed
            $("span#price").blur(function() {
                $(this).parents(".card").children(".fa-check").show();
                TickUpdate = $(this).parents(".card").children(".fa-check");
                var serviceID = $(this).parents(".row-head").children(".fa-trash").attr('id')
                var serviceName = $(this).parentsUntil("div#info").children("p.service-name").text();
                var serviceDesc = $(this).parentsUntil("div#info").children(".desc").children("p.servDesc").text();
                var serviceDura = $(this).parentsUntil("div#info").children(".desc").children("h6").children("span.duration").text();
                var servicePrice = $(this).parentsUntil("div#info").children(".row-head").children("h6").children("span#price").text();

                $(TickUpdate).click(function() {
                    Swal.fire({
                        title: 'Do you want to save the changes?',
                        showDenyButton: true,
                        showCancelButton: true,
                        confirmButtonText: 'Save',
                        denyButtonText: `Don't save`,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Swal.fire('Saved!', '', 'success')
                            $.ajax({
                                url: "updateServices.php",
                                data: {
                                    serviceID: serviceID,
                                    serviceName: serviceName,
                                    serviceDesc: serviceDesc,
                                    serviceDura: serviceDura,
                                    servicePrice: servicePrice
                                },
                                method: "POST",
                                error: function(xhr) {
                                    alert("An error occrured" + xhr.status + " " + xhr.statusText)
                                },
                                success: function(data) {
                                    if (data == "success") {
                                        Swal.fire(
                                            'Update successful!',
                                            'Service has been updated',
                                            'success'
                                        )
                                        $(TickUpdate).hide();
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Oops...',
                                            text: 'Something went wrong!',
                                        })
                                    }
                                }
                            })
                        } else if (result.isDenied) {
                            Swal.fire('Changes are not saved', '', 'info')
                        }
                    })
                })
            })

            //Duration Changed
            $("span.duration").blur(function() {
                $(this).parents(".card").children(".fa-check").show();
                TickUpdate = $(this).parents(".card").children(".fa-check");
                var serviceID = $(this).parentsUntil("div#info").children(".row-head").children("i").attr('id');
                var serviceName = $(this).parentsUntil("div#info").children("p.service-name").text();
                var serviceDesc = $(this).parentsUntil("div#info").children(".desc").children("p.servDesc").text();
                var serviceDura = $(this).parentsUntil("div#info").children(".desc").children("h6").children("span.duration").text();
                var servicePrice = $(this).parentsUntil("div#info").children(".row-head").children("h6").children("span#price").text();

                $(TickUpdate).click(function() {
                    Swal.fire({
                        title: 'Do you want to save the changes?',
                        showDenyButton: true,
                        showCancelButton: true,
                        confirmButtonText: 'Save',
                        denyButtonText: `Don't save`,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Swal.fire('Saved!', '', 'success')
                            $.ajax({
                                url: "updateServices.php",
                                data: {
                                    serviceID: serviceID,
                                    serviceName: serviceName,
                                    serviceDesc: serviceDesc,
                                    serviceDura: serviceDura,
                                    servicePrice: servicePrice
                                },
                                method: "POST",
                                error: function(xhr) {
                                    alert("An error occrured" + xhr.status + " " + xhr.statusText)
                                },
                                success: function(data) {
                                    if (data == "success") {
                                        Swal.fire(
                                            'Update successful!',
                                            'Service has been updated',
                                            'success'
                                        )
                                        $(TickUpdate).hide();
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Oops...',
                                            text: 'Something went wrong!',
                                        })
                                    }
                                }
                            })
                        } else if (result.isDenied) {
                            Swal.fire('Changes are not saved', '', 'info')
                        }
                    })
                })
            })




        });
    </script>

</html>