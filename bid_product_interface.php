<?php 
function set_bidEvent($bid_arr, $instr) {
    /* Add bid event to database 
    Parameters: 
    - <$product_arr>: Object with attributes - 
    - <$instr>: 
    */ 

    include 'database.php';

    // check object has the correct properties
    $properties = ["bidID", "productID", "buyerID", "payment", "price"];
    foreach ($properties as $value) {
        if (!array_key_exists($value, $bid_arr)) {
            echo "Parameter is not an object with the correct properties\n"; 
        }
    }

    $bidID = $bid_arr['bidID'];
    $productID = $bid_arr['productID'];
    $buyerID = $bid_arr['buyerID'];
    $payment = $bid_arr['payment'];
    $bidPrice = $bid_arr['price'];


    // add new 
    if ($instr = "insert") {
        $sql = "INSERT INTO bidEvents (productID, buyerID, payment, bidPrice) VALUES ('$productID', '$buyerID', '$payment', $bidPrice)";
        $result = $connection->query($sql); 
        if ($result==TRUE) {
            echo("Inserted new bid events.\n");
        } else {
            echo("Error: " . $sql . "<br>" . $connection->error);
        }

    // update
    } elseif ($instr = "update") {
        $sql = "INSERT INTO bidEvents (productID,  buyerID, payment, bidPrice) VALUES ($productID', '$buyerID', '$payment', '$bidPrice');
        WHERE bidID = '$bidID'";

        if ($connection->query($sql)==TRUE) {
            echo("Updated bid events.");
        } else {
            echo("Error: " . $sql . "<br>" . $connection->error);
        }

    // delete 
    } elseif ($instr = "delete") {
        $sql="DELETE FROM bidEvents WHERE bidID = '$bidID'";
        
        if ($connection_->query($sql)==TRUE) {
            echo("Deleted bid event successfully.");
        } else {
            echo("Error: " . $sql . "<br>" . $connection->error);
        }

    } else {
        echo("Error: Selected wrong instruction for set_bidEvent.");
    }

    return $bid_arr;

    $connection->close();
}
?>