<?php 
function set_watch($watch_arr, $instr) {         
    /* Add watch event to database 
    Parameters: 
    - <$product_arr>: Object with attributes - productID, buyerID
    - <$instr>: insert/delete
    */ 

    include 'database.php';
    include "header.php";

    // check object has the correct properties
    $properties = ["productID", "buyerID"]; 
    foreach ($properties as $value) {
        if (!array_key_exists($value, $watch_arr)) {
            echo "Parameter is not an object with the correct properties\n"; 
        }
    }

    $productID = $watch_arr['productID'];
    $buyerID = $_SESSION['userID'];
    $instr = $instr;

    // add new 
    if ($instr == "insert") {
        $sql = "INSERT INTO watchlist (productID, buyerID) VALUES ('$productID', '$buyerID')";
        if ($connection->query($sql)==TRUE) {
            echo("Inserted new watchlist item.\n");
        } else {
            echo("Error: " . $sql . "<br>" . $connection->error);
        }

    // delete 
    } elseif ($instr == "delete") {
        $sql="DELETE FROM watchlist WHERE buyerID = '$buyerID' AND productID = '$productID'";  
        if ($connection->query($sql)==TRUE) {
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
