<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Holiday-History</title>
    <style>
        button{
            padding: 1px;
            color: white;
            width: 60%;
        }
        .approved{
            background-color: rgb(28,187,140);
            border: 2px solid rgb(28,187,140);
            border-radius:4px;
            
            
        }
        .rejected{
            background-color: rgb(220,53,69);
            border: 2px solid rgb(220,53,69);
            border-radius:4px;

            
        }
        .pending{
            background-color: rgb(252,185,44);
            border: 2px solid rgb(252,185,44);
            border-radius:4px;

            
        }
    </style>
</head>
<body>
    <?php 
        $active = "holidayinfo";
        include "includes/barber-menu.php";
    ?>
    <!-- <div class="fake-menu" style="width:80%;height:6rem;"></div> -->

    <div class="content">
        <h1>Holiday History</h1>
        <table class="order-list">
            <thead>
                <tr>
                    <th style="width:20%">Holiday Ref</th>
                    <th style="width:20%">Start Date</th>
                    <th style="width:20%">End Date</th>
                    <th>Holiday Description</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    require_once("includes/database.php");

                    
                    // $_SESSION["barberid"]=2;

                    $sSelect = " SELECT * FROM holiday WHERE barberid = " 
                                . $conn->quote($_SESSION["barber_userid"]) .";";

                    $result = $conn->query($sSelect);
                    $rowcount = $result->rowcount();

                    if ($rowcount == 0) {
                        echo "<td colspan=\"100%\" style=\"text-align: center;background-color:#F2F2F2\"> No Holiday information on file </td>";
                    }
                    else{
                        $i=0;
                        while($row = $result->fetch()){

                            if($i==0){
                                echo "<tr class=\"even\">";
                                $i=1;
                            }
                            else{
                                echo "<tr>";
                                $i=0;
                            }

                            echo "<td>".$row["holidayid"]."</td>";
                            echo "<td>".$row["start_date"]."</td>";
                            echo "<td>".$row["end_date"]."</td>";
                            echo "<td>".$row["description"]."</td>";

                            if ($row['is_approved'] == 1 && $row['responded'] == 1){
                                echo "<td><button class='approved'>Approved</button></td>";
                            }
                            else if ($row['is_approved'] == 0  && $row['responded'] == 1){
                                echo "<td><button class='rejected'>Rejected</button></td>";
                            }
                            else if ( $row['responded'] == 0){
                                echo "<td><button class='pending'>Pending</button></td>";
                            }
                        }
                    }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>