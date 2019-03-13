
<?php
    include_once("bid_product_interface.php");
    $productID = $_POST["productID"];
    $bids = get_bidEvent("all", $productID); // get all bid elements 
    
    foreach ($bids as $bid) {
        echo ("<tr> <td> ".$bid["buyerID"]."</td> <td>"."£".$bid["bidPrice"]."</td> </tr>");
    }
    // testing use - echo ("<tr> <td> "."Testing-delete this: "."</td> <td>".time()."</td> </tr>");
?> 