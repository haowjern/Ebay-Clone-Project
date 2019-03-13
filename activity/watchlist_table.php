<?php
    include_once("watchtable_interface.php");
    include '../database.php';
    //$buyerID = $_SESSION['userID'];
    $buyerID = 11;
    $watches = get_watchtable($buyerID); // get all watch elements for user
    
    foreach ($watches as $watch) {
        //echo ("<tr> <td> ".$watch["buyerID"]."</td> <td>".$watch["productID"]."</td> </tr>");


        print($watch['productName']);
        echo ("<tr> <td> ".$watch["productName"]."</td> </tr>");

        //echo ("<tr> <td> ".$watch[""]."</td> <td> ".$watch[""]."</td> <td> ".$watch[""]."</td> <td>".$watch["productID"]."</td> </tr>");

        //<td> ".$watch[""]."</td>  
        //echo ("<tr> <td> ".$watch["buyerID"]."</td> <td>"."£".$watch["productID"]."</td> </tr>");
    }
    // testing use - echo ("<tr> <td> "."Testing-delete this: "."</td> <td>".time()."</td> </tr>");



    // want:    productName    sellerName   latestBidder    latestBidPrice      endDate     endTime



?> 