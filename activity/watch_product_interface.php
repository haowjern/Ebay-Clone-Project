<?php 
function set_watch($watch_arr, $instr) {         
    /* Add watch event to database 
    Parameters: 
    - <$product_arr>: Object with attributes - productID, buyerID
    - <$instr>: insert/delete
    */ 

    include 'database.php';

    // check object has the correct properties
    $properties = ["watchID", "productID", "buyerID"]; 
    foreach ($properties as $value) {
        if (!array_key_exists($value, $watch_arr)) {
            echo "Parameter is not an object with the correct properties\n"; 
        }
    }

    $watchID = $watch_arr['watchID'];
    $productID = $watch_arr['productID'];
    $buyerID = $watch_arr['buyerID'];


    // add new 
    if ($instr = "insert") {
        $sql = "INSERT INTO watchlist (productID, buyerID) VALUES ('$productID', '$buyerID')";
        $result = $connection->query($sql); 
        if ($result==TRUE) {
            echo("Inserted new watchlist item.\n");
        } else {
            echo("Error: " . $sql . "<br>" . $connection->error);
        }

    // delete 
    } elseif ($instr = "delete") {
        $sql="DELETE FROM watchEvents WHERE watchID = '$watchID'";  
        
        if ($connection_->query($sql)==TRUE) {
            echo("Deleted watch event successfully.");
        } else {
            echo("Error: " . $sql . "<br>" . $connection->error);
        }

    } else {
        echo("Error: Selected wrong instruction for set_watchEvent.");
    }

    return $watch_arr;

    $connection->close();
}
?>