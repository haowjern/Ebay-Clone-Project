<?php

function get_watchtable($buyerID) {
    include '../database.php';
    
    $watches = []; 

        $sql = "SELECT * FROM Watchlist WHERE buyerID = '$buyerID'
                ORDER BY watchID DESC";
        $result = $connection->query($sql);
        
        if (mysqli_num_rows($result)>0) {
            echo "Checking .."; 
            while ($row=$result->fetch_assoc()) {
                $watch_arr['watchID'] = $row['watchID'];
                $watch_arr['productID'] = $row['productID'];
                $productID = $row['productID'];
                $watch_arr['buyerID'] = $row['buyerID'];

                // get product name from product id and product table

                $sql = "SELECT product_name FROM Product WHERE productID = '$productID'";
        
                $result = $connection->query($sql);
                $watch_arr['productName'] = $result;
               
                
                // if the above works, ...


                // from product:
                // get end date & end time
                // get sellerid             -> get sellername from users

                
                // from bidevents, via productid:
                // get latest bid matching productid
                    // get bidprice from latest bid    
                    // get buyerid from latest bid
                        // get buyer name from users

                        


                //$watch_arr[''] = $row[''];

                array_push($watches, $watch_arr);
            }
            echo("Received watchlist item successfully.");
        } else {
            $watch_arr['watchID'] = 0; 
            $watch_arr['productID'] = 0;
            $watch_arr['buyerID'] = 0;
            array_push($watches, $watch_arr);
        }
    return $watches;
}
?>