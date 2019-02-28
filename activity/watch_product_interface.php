<?php 
function set_watchEvent($watch_arr, $instr) {                                   /* set_watchEvent    $watch_arr   
    /* Add watch event to database 
    Parameters: 
    - <$product_arr>: Object with attributes - 
    - <$instr>: 
    */ 

    include 'database.php';

    // check object has the correct properties
    $properties = ["watchID", "productID", "buyerID", "payment", "price"];          ######### watchID equivalent to bidID?
    foreach ($properties as $value) {
        if (!array_key_exists($value, $watch_arr)) {
            echo "Parameter is not an object with the correct properties\n"; 
        }
    }

    $watchID = $watch_arr['watchID'];
    $productID = $watch_arr['productID'];
    $buyerID = $watch_arr['buyerID'];
    $payment = $watch_arr['payment'];
    $bidPrice = $watch_arr['price'];            #####...


    // add new 
    if ($instr = "insert") {
        $sql = "INSERT INTO watchEvents (productID, buyerID, payment, bidPrice) VALUES ('$productID', '$buyerID', '$payment', $bidPrice)";
        $result = $connection->query($sql); 
        if ($result==TRUE) {
            echo("Inserted new bid events.\n");
        } else {
            echo("Error: " . $sql . "<br>" . $connection->error);
        }


        // this above is pointed to by watch_product.php !!!!!!!!!!


        

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
        $sql="DELETE FROM watchEvents WHERE watchID = '$watchID'";              // .............................similar? remove from watch?
        
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