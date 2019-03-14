<?php 

function set_bidEvent($bid_arr, $instr) {
    /* Add bid event to database 
    Parameters: 
    - <$product_arr>: Object with attributes - 
    - <$instr>: 
    */ 

    if (file_exists('../database.php')){
        include '../database.php';
    } else {
        include './database.php';
    }
    include 'update_watching.php';
    
    
    // check object has the correct properties
    $properties = ["bidID", "productID", "buyerID", "payment", "price"];
    foreach ($properties as $value) {
        if (!array_key_exists($value, $bid_arr)) {
            echo "Parameter is not an object with the correct properties\n"; 
        }
    }
    // include './update_watching.php';
    // check object has the correct properties
    $bidID = $bid_arr['bidID'];
    $productID = $bid_arr['productID'];
    $buyerID = $bid_arr['buyerID'];
    $payment = $bid_arr['payment'];
    $bidPrice = $bid_arr['price'];
    $date_today = date("Y/m/d");
    $time_today = date("H:i:s");
    // add new 
    if ($instr === "insert") {
        $sql = "INSERT INTO bidEvents (productID, buyerID, payment, bidPrice, bidDate, bidTime) 
                VALUES ('$productID', '$buyerID', '$payment', '$bidPrice', '$date_today', 
                        '$time_today')";
        $result = $connection->query($sql); 
        if ($result==TRUE) {
            
            // send email to all watchers and all who have already bid on this product
            //include 'update_watching.php';

            send_email_updating_watchers($bid_arr);


            echo("Inserted new bid events.\n");
        } else {
            echo("Error: " . $sql . "<br>" . $connection->error);
        }
    // update
    } elseif ($instr === "update") {
        $sql = "INSERT INTO bidEvents (productID,  buyerID, payment, bidPrice) VALUES ('$productID', '$buyerID', '$payment', '$bidPrice');
        WHERE bidID = '$bidID'";
        if ($connection->query($sql)==TRUE) {
            echo("Updated bid events.");
        } else {
            echo("Error: " . $sql . "<br>" . $connection->error);
        }
    // delete 
    } elseif ($instr === "delete") {
        $sql="DELETE FROM bidEvents WHERE bidID = '$bidID'";
        
        if ($connection->query($sql)==TRUE) {
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

function get_bidEvent($condition, $productID) {
    if (file_exists('../database.php')){
        include '../database.php';
    } else {
        include './database.php';
    }
    
    $bids = []; 
    if ($condition == "latest") {
        $sql = "SELECT * FROM bidEvents WHERE bidPrice=(
            SELECT MAX(bidPrice) FROM bidEvents WHERE productID = '$productID'
            );";
        $result = $connection->query($sql);
        if ($result->num_rows>0) { 
            while ($row=$result->fetch_assoc()) {
                $bid_arr['bidID'] = $row['bidID'];
                $bid_arr['productID'] = $row['productID'];
                $bid_arr['buyerID'] = $row['buyerID'];
                $bid_arr['payment'] = $row['payment'];
                $bid_arr['bidPrice'] = $row['bidPrice'];
    
                array_push($bids, $bid_arr);
            }

            // echo("Received bid event successfully.");
        } else {
            $bid_arr['bidID'] = 0; 
            $bid_arr['productID'] = 0;
            $bid_arr['buyerID'] = 0;
            $bid_arr['payment'] = 0;
            $bid_arr['bidPrice'] = 0;
            array_push($bids, $bid_arr);
        }

    } elseif ($condition == "all") {
        $sql = "SELECT * FROM bidEvents WHERE productID = '$productID'
                ORDER BY bidID DESC";
        $result = $connection->query($sql);
        if ($result->num_rows>0) { 
            while ($row=$result->fetch_assoc()) {
                $bid_arr['bidID'] = $row['bidID'];
                $bid_arr['productID'] = $row['productID'];
                $bid_arr['buyerID'] = $row['buyerID'];
                $bid_arr['payment'] = $row['payment'];
                $bid_arr['bidPrice'] = $row['bidPrice'];
                $bid_arr['bidDate'] = $row['bidDate'];
                $bid_arr['bidTime'] = $row['bidTime'];
                array_push($bids, $bid_arr);
            }
            echo("Received bid event successfully.");
        } else {
            $bid_arr['bidID'] = 0; 
            $bid_arr['productID'] = 0;
            $bid_arr['buyerID'] = 0;
            $bid_arr['payment'] = 0;
            $bid_arr['bidPrice'] = 0;
            $bid_arr['bidDate'] = 0;
            $bid_arr['bidTime'] = 0;
            array_push($bids, $bid_arr);
        }
    }
    return $bids;
}
?>