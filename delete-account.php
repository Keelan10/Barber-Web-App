<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Account</title>
    <!-- SWEET ALERT -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <?php
    $activeMenu = "accounts";
    include_once("includes/admin-menu.php");
    ?>
    <div class="overlay"></div>
    <div class="popup card">
        <span class="popup-message"></span>
    </div>
    <div class="modal">
        <div class="header">
            <h3>Confirmation</h3>
        </div>
        <div class="message">
            <p>
                Are you sure you wanna delete the <span id="accountType"></span> account <span id="accountName"></span> (Email: <span id="accountEmail"></span>)?
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
                <h1 class="h3 d-inline align-middle">View/Delete Account</h1>
                <div class="input-wrapper">
                    <i class="fas fa-search" aria-hidden="true"></i>
                    <input type="text" placeholder="Search email" class="search-bar" onkeyup="search()">
                </div>
            </div>


            <div class="card flex-fill">
                <div class="card-header">

                    <h5 class="card-title mb-0">Accounts</h5>
                   
                </div>
                <table class="table table-hover my-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th class="d-none d-xl-table-cell">Email</th>
                            <th>Last active</th>
                            <th class="d-none d-xl-table-cell">Type</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

        </div>
    </main>
    </div>
    <!--end of main div-->
    </div>
    <!--end of wrapper div-->

    </body>

    <script>
        function search(){
            const input=$(".search-bar").val().toLowerCase();//for case insensitive search
            const emails=$(".email");

            for (var i=0;i<emails.length;i++){

                if($(emails[i]).text().toLowerCase().indexOf(input)>-1){
                    $(emails[i]).parentsUntil("tbody").show()
                }
                else{
                    $(emails[i]).parentsUntil("tbody").hide()
                }
            }

        }
        
        $(document).ready(function() {

            loadAccounts();
            $(".overlay").click(hideModal);
            $(".choice>.fa-check").click(tickClicked);


            function trashClicked(event){
                const tr = $(event.currentTarget).parentsUntil("tbody");
                const name = tr.children(".name").text();
                const type = tr.children(".type").text();
                const email = tr.children(".email").text();
                
                $("#accountName").text(name);
                $("#accountType").text(type);
                $("#accountEmail").text(email);
                $(".overlay").show();
                $(".modal").show();
            }

            function hideModal(){
                $(".overlay").hide();
                $(".modal").hide();
            }

            function tickClicked(event){
                const main = $(event.currentTarget).parentsUntil(".main");
                const accountName = main.children(".message").children("p").children("#accountName").text()
                const accountType = main.children(".message").children("p").children("#accountType").text()
                const accountEmail = main.children(".message").children("p").children("#accountEmail").text()

                $.ajax("delete.php", {
                    data: {
                        accountName: accountName,
                        accountType: accountType,
                        accountEmail: accountEmail
                    },
                    success: function(data) {
                        loadAccounts();
                        hideModal();

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
                    }
                });

            }

            




            function loadAccounts() {
                
                $.ajax({
                    url: "loadAccounts.php",
                    dataType: "JSON",
                    success: function(data) {
                        // console.log(data.length);

                        if (data.length==0){
                            const message=
                            `<tr>
                                <td colspan=100% style="text-align:center;background-color:#222e3c12">No account found</td>
                            </tr>`

                            $("tbody").html(message);
                        }
                        else{

                            const barbers=data[0];
                            const customers=data[1];
                            const admin= data[2];
    
                            var result="";
                            for (var i=0;i<barbers.length;i++){
                                result+=
                                `<tr>
                                <td class="name">${barbers[i].first_name+" "+barbers[i].last_name}</td>
                                <td class="email d-none d-xl-table-cell">${barbers[i].email}</td>
                                <td class="d-none d-xl-table-cell">${barbers[i].last_active}</td>
                                <td class="type d-none d-xl-table-cell">Barber</td>
                                <td><i class="fa fa-trash" style="cursor:pointer" name="barber-${barbers[i].barberid}"></i></td>
                                </tr>`
                            }
    
                            for (var i=0;i<customers.length;i++){
                                result+=
                                `<tr>
                                <td class="name">${customers[i].first_name+" "+customers[i].last_name}</td>
                                <td class="email d-none d-xl-table-cell">${customers[i].email}</td>
                                <td class="d-none d-xl-table-cell">${customers[i].last_active}</td>
                                <td class="type d-none d-xl-table-cell">Customer</td>
                                <td><i class="fa fa-trash" style="cursor:pointer" name="customer-${customers[i].customerid}"></i></td>
                                </tr>`
                            }

                            for (var i=0;i<admin.length;i++){
                                result+=
                                `<tr>
                                <td class="name">${admin[i].first_name+" "+admin[i].last_name}</td>
                                <td class="email d-none d-xl-table-cell">${admin[i].email}</td>
                                <td class="d-none d-xl-table-cell">${admin[i].last_active}</td>
                                <td class="type d-none d-xl-table-cell" colspan="2">Admin</td>
                                </tr>`
                            }
                            
                            
                            $("tbody").html(result);
    
                            $(".fa-trash").click(trashClicked);
                            $(".choice>.fa-times").click(hideModal);

                        }    
                        

                    }
                });

            }

        })
    </script>

</html>