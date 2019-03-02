<?php 
function set_watch($watch_arr, $instr) {                                   /* set_watchEvent    $watch_arr   
    /* Add watch event to database 
    Parameters: 
    - <$product_arr>: Object with attributes - 
    - <$instr>: 
    */ 

    include 'database.php';

    // check object has the correct properties
    $properties = ["watchID", "productID", "buyerID"];          ######### watchID equivalent to bidID?
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
            echo("Inserted new bid events.\n");
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