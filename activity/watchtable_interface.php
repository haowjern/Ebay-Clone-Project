<?php

function get_watchtable($buyerID) {
    include '../database.php';
    
    $watches = []; 

        $sql = "SELECT * FROM Watchlist WHERE buyerID = '$buyerID'
                ORDER BY watchID DESC";
        $result = $connection->query($sql);
        if ($result->num_rows>0) { 
            while ($row=$result->fetch_assoc()) {
                $watch_arr['watchID'] = $row['watchID'];
                $productID = $row['productID'];
                $watch_arr['buyerID'] = $row['buyerID'];

                // get product name from product id and product table
                $sql = "SELECT * FROM Product WHERE productID = '$productID'";
                $result1 = $connection->query($sql);
                while ($row=$result1->fetch_assoc()) {
                    $watch_arr['productName'] = $row['product_name'];
                    $watch_arr['endDate'] = $row['enddate'];
                    $watch_arr['endTime'] = $row['endtime'];

                    // get seller name from seller id and user table
                    $sellerID = $row['sellerID'];
                    $sql = "SELECT * FROM Users WHERE userID = '$sellerID'";
                    $result2 = $connection->query($sql);
                    while ($row=$result2->fetch_assoc()) {
                        $watch_arr['sellerName'] = $row['username'];
                    }
                }
                
                array_push($watches, $watch_arr);
            }
            //echo("Received watchlist item successfully.");
        } else {
            // does this work fine? test...

            $watch_arr['productName'] = "None";
            $watch_arr['sellerName'] = "-"; 
            $watch_arr['endDate'] = "-";
            $watch_arr['endTime'] = "-";
                    
            array_push($watches, $watch_arr);
        }
    return $watches;
}
?>