<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Catalogue</title>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
       
        .products-container{
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr 1fr;
            grid-gap: 1rem;
        }

        .product-image-container{
            padding: 2rem;
            /* width: 100px; */
            height: 150px;
        }
        .row-head{
            display:flex;
            flex-direction: row;
            justify-content: space-between;
        }
        article.card{
            padding: 0.25rem 1rem;
        }

    </style>

    <?php
    $_SESSION["username"] = "Tom Cruise";
    $activeMenu = "catalogue";
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
                Are you sure you wanna delete the following product: <span id="product-name"></span>?
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
                <h1 class="h3 d-inline align-middle">Edit Catalogue</h1>
                <div class="input-wrapper">
                    <i class="fas fa-search" aria-hidden="true"></i>
                    <input type="text" placeholder="Search product name" class="search-bar" onkeyup="search()">
                </div>
            </div>

            
                
            
            <?php
            $sql = "SELECT * FROM product";
            $result = $conn->query($sql);
            $rowCount = $result->rowCount();

            if ($rowCount) {
                echo '<div class="products-container">';
                while ($row = $result->fetch()) :
            ?>

                    <article class="card">
                        <div class="product-image-container">
                            <img width="100%" height="100%" src="images/product/<?php echo $row['image_name']; ?> " alt="">
                        </div>
                        <div class="info">
                            <p style="font-size:15px;line-height:1.5em;letter-spacing:0.5px" class="product-name" contenteditable="true" spellcheck=”false”><?= $row['product_name']; ?></p>
                            <div class="row-head">
                                <h6 style="letter-spacing:0.5px">Rs<span id="price" contenteditable="true" spellcheck=”false”> <?php echo $row["price"] ?></span></h6>
                                <i class="fa fa-trash delete-product" style="cursor:pointer"></i>
                            </div>
                        </div>
                        <?php
                        ?>
                    </article>
            <?php endwhile;
            }else{ ?>

            <div class="card" style="height: 100%;">
                No product in catalogue
                <a href="add-product.php">Click here to add</a>
            </div>

            <?php }//end else ?>

            </div>
        
        </div>
    </main>
    </div>
    <!--end of main div-->
    </div>
    <!--end of wrapper div-->

    </body>

    <script>
        $('body').attr("spellcheck",false)

        function search(){
            const input=$(".search-bar").val().toLowerCase();//for case insensitive search
            const productNames=$(".product-name");

            for (var i=0;i<productNames.length;i++){

                if($(productNames[i]).text().toLowerCase().indexOf(input)>-1){
                    $(productNames[i]).parentsUntil(".products-container").show()
                }
                else{
                    $(productNames[i]).parentsUntil(".products-container").hide()
                }
            }

        }

        $(document).ready(function(){

            $(".overlay").click(hideModal);
            $(".choice>.fa-times").click(hideModal);
            $(".choice>.fa-check").click(tickClicked);

            $(".delete-product").click(function(){
                $(".overlay").show();
                $(".modal").show();
                // alert("clicked")

                const productName = $(event.currentTarget).parentsUntil(".card").find(".product-name").text();
                $("#product-name").text(productName)
            })

            function hideModal(){
                $(".overlay").hide();
                $(".modal").hide();
            }

            function tickClicked(){
                const productName= $("#product-name").text();
                
                $.ajax("deleteProduct.php",{
                    data : {productName:productName},
                    success:function(data){
                        if (data=="success"){
                            Swal.fire(
                                'Deletion successful!',
                                'Account has been deleted',
                                'success'
                                )
                            }
                        else if (data=="failure"){
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!',
                            })
                        }
                        hideModal();

                        //To do reload products!
                    }
                })

                
            }
        })
    //     $(document).ready(function() {

    //         loadAccounts();
    //         $(".overlay").click(hideModal);

    //         function trashClicked(event){
    //             const tr = $(event.currentTarget).parentsUntil("tbody");
    //             const name = tr.children(".name").text();
    //             const type = tr.children(".type").text();
    //             const email = tr.children(".email").text();
                
    //             $("#accountName").text(name);
    //             $("#accountType").text(type);
    //             $("#accountEmail").text(email);
    //             $(".overlay").show();
    //             $(".modal").show();
    //         }

    //         function hideModal(){
    //             $(".overlay").hide();
    //             $(".modal").hide();
    //         }

    //         function tickClicked(event){
    //             const main = $(event.currentTarget).parentsUntil(".main");
    //             const accountName = main.children(".message").children("p").children("#accountName").text()
    //             const accountType = main.children(".message").children("p").children("#accountType").text()
    //             const accountEmail = main.children(".message").children("p").children("#accountEmail").text()

    //             $.ajax("delete.php", {
    //                 data: {
    //                     accountName: accountName,
    //                     accountType: accountType,
    //                     accountEmail: accountEmail
    //                 },
    //                 // success: function()
    //                 success: function(data) {
    //                     // alert(data);
    //                     loadAccounts();
    //                     hideModal();
    //                 }
    //             });

    //         }

            




    //         function loadAccounts() {

                
    //             $.ajax({
    //                 url: "loadAccounts.php",
    //                 dataType: "JSON",
    //                 success: function(data) {
    //                     console.log(data.length);

    //                     if (data.length==0){
    //                         const message=
    //                         `<tr>
    //                             <td colspan=100% style="text-align:center;background-color:#222e3c12">No account found</td>
    //                         </tr>`

    //                         $("tbody").html(message);
    //                     }
    //                     else{

    //                         const barbers=data[0];
    //                         const customers=data[1];
    //                         const admin= data[2];
    
    //                         var result="";
    //                         for (var i=0;i<barbers.length;i++){
    //                             result+=
    //                             `<tr>
    //                             <td class="name">${barbers[i].first_name+" "+barbers[i].last_name}</td>
    //                             <td class="email d-none d-xl-table-cell">${barbers[i].email}</td>
    //                             <td class="d-none d-xl-table-cell">${barbers[i].last_active}</td>
    //                             <td class="type d-none d-xl-table-cell">Barber</td>
    //                             <td><i class="fa fa-trash" style="cursor:pointer" name="barber-${barbers[i].barberid}"></i></td>
    //                             </tr>`
    //                         }
    
    //                         for (var i=0;i<customers.length;i++){
    //                             result+=
    //                             `<tr>
    //                             <td class="name">${customers[i].first_name+" "+customers[i].last_name}</td>
    //                             <td class="email d-none d-xl-table-cell">${customers[i].email}</td>
    //                             <td class="d-none d-xl-table-cell">${customers[i].last_active}</td>
    //                             <td class="type d-none d-xl-table-cell">Customer</td>
    //                             <td><i class="fa fa-trash" style="cursor:pointer" name="customer-${customers[i].customerid}"></i></td>
    //                             </tr>`
    //                         }

    //                         for (var i=0;i<admin.length;i++){
    //                             result+=
    //                             `<tr>
    //                             <td class="name">${admin[i].first_name+" "+admin[i].last_name}</td>
    //                             <td class="email d-none d-xl-table-cell">${admin[i].email}</td>
    //                             <td class="d-none d-xl-table-cell">${admin[i].last_active}</td>
    //                             <td class="type d-none d-xl-table-cell">Admin</td>
    //                             <td><i class="fa fa-trash" style="cursor:pointer" name="admin-${admin[i].adminid}"></i></td>
    //                             </tr>`
    //                         }
                            
                            
    //                         $("tbody").html(result);
    
    //                         $(".fa-trash").click(trashClicked);
    //                         $(".choice>.fa-times").click(hideModal);
    //                         $(".choice>.fa-check").click(tickClicked);

    //                     }    
                        

    //                 }
    //             });

    //         }

    //     })
    </script>

</html>