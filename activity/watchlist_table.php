<?php
    include_once("watchtable_interface.php");
    include '../database.php';
    $buyerID = $_SESSION['userID'];
    $watches = get_watchtable($buyerID); // get all watch elements for user
    
    foreach ($watches as $watch) {
        echo ("<tr> <td> ".$watch["productName"]."</td> <td> ".$watch["sellerName"]."</td> <td> ".$watch["endDate"]."</td> <td> ".$watch["endTime"]."</td> </tr>");
    }
?> 