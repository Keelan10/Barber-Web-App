<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Holiday Approval</title>


    <?php
        $activeMenu="holiday";
        include_once("includes/admin-menu.php");
        require_once("includes/database.php");

        

        if ($_SERVER['REQUEST_METHOD'] == "POST"){
            $holiday_id = "";
            foreach ($_POST as $key=>$value){
                $holiday_id = $key;

                $sUpdate = "UPDATE holiday
                            SET is_approved = ".$conn->quote($value).",responded = '1'
                            WHERE holidayid = ".$conn->quote($holiday_id).";";

                //echo $sUpdate;
                $UpdateResult = $conn->exec($sUpdate); 
                            

            }

        }

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }  
    ?>
    <main class="content">
        <div class="container-fluid p-0">
            <div class="mb-3">
                <h1 class="h3 d-inline align-middle">View Holiday Request</h1>
            </div>
            <div class="card flex-fill">
                <div class="card-header">
                    <h5 class="card-title mb-0">Requests</h5>
                </div>

            <table class="table table-hover my-0">
                <thead>
                    <tr>
                        <th>Holiday ref</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Description</th>
                        <th>Stylist</th>
                        <th>Response</th>      
                        <!-- <?php if ($responseErr != ""){echo "<div class='error'> (Please respond before hitting confirm!) </div>";}?> -->
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        require_once("includes/database.php");

                        // $name= $_SESSION["username"];
                        $sSelect="SELECT * FROM holiday WHERE responded = '0';";
                        $result = $conn->query($sSelect);
                        $rowcount = $result->rowcount();
                        //$rowcount = 0;

                        if ($rowcount == 0){
                            echo "<td colspan=\"100%\" style=\"text-align: center;\"> No Holiday Request on file </td>";
                        }
                        else {
                            $i=0;
                            while($row = $result->fetch()){
                                echo "<tr>";

                                echo "<td>".$row["holidayid"]."</td>";
                                echo "<td>".$row["start_date"]."</td>";
                                echo "<td>".$row["end_date"]."</td>";
                                echo "<td>".$row["description"]."</td>";

                                $holidayid = $row["holidayid"];

                                $sql = 'SELECT concat(barber.first_name," ",barber.last_name) AS stylist  FROM barber WHERE barberid = ' . $conn->quote($row['barberid']);
                                $rowresult = $conn->query($sql);
                                $rowbarber = $rowresult->fetch();

                                echo "<td>".$rowbarber["stylist"]."</td>";
                                echo    '<td>
                                            <form action="holiday-approval.php" method="post" >
                                                <input type="radio" id="approved" name="'. "$holidayid" .'" value="1" >
                                                <label for="approved" class = "response">Accept</label>
                                                <input type="radio" id="disapproved" name="'. "$holidayid" .'" value="0" >
                                                <label for="disapproved" class = "response">Reject</label>
                                        </td>';
                                echo "</tr>";
                            }
                        }
                    ?>
                </tbody>
            </table>
            <div class="btn-containers">
                <input type="submit" value = "Confirm" class="btn btn-secondary" style="width: 7%; margin: 1rem; padding: 0.1rem;"> 
                <input type="Reset" value = "Reset" class="btn btn-secondary" style="width: 7%; margin: 1rem; padding: 0.1rem;"> 
            </div>
            </form>
        </div>
    </main>

        </div><!--end of main div-->
    </div><!--end of wrapper div-->
</body>

</html>