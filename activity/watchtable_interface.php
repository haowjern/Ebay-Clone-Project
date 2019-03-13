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
                //$watch_arr['productID'] = $row['productID'];
                $productID = $row['productID'];
                $watch_arr['buyerID'] = $row['buyerID'];

                // get product name from product id and product table
                $sql = "SELECT * FROM Product WHERE productID = '$productID'";
                $result1 = $connection->query($sql);
                while ($row=$result1->fetch_assoc()) {
                    $watch_arr['productName'] = $row['product_name'];
                    $watch_arr['endDate'] = $row['enddate'];
                    $watch_arr['endTime'] = $row['endtime'];
                    //$watch_arr['sellerID'] = $row['sellerID'];

                    $sellerID = $row['sellerID'];
                    $sql = "SELECT * FROM Users WHERE userID = '$sellerID'";
                    $result1 = $connection->query($sql);
                    while ($row=$result1->fetch_assoc()) {
                        $watch_arr['sellerName'] = $row['username'];
                    }
                
                }
                

                // if there's time, do the following:
                // from bidevents, via productid:
                // get latest bid matching productid
                    // get bidprice from latest bid    
                    // get buyerid from latest bid
                        // get buyer name from users


                array_push($watches, $watch_arr);
            }
            //echo("Received watchlist item successfully.");
        } else {
            // does this work fine? test...

            $watch_arr['productName'] = 0;
            $watch_arr['sellerName'] = 0; 
            $watch_arr['endDate'] = 0;
            $watch_arr['endTime'] = 0;
                    
            array_push($watches, $watch_arr);
        }
    return $watches;
}
?>