<?php 
function show_watchlist($current_user, $instr) {
    include "database.php";
    if ($instr = "show") {
        $sql="SELECT * FROM Watchlist";
        $result = mysqli_query($connection, $sql); 
        echo "<table>";
        if ($result->num_rows>0) {
            echo "<tr><th>ProductID</th><th>BuyerID</th><th>WatchID</th></tr>";
            while($row = mysqli_fetch_array($result)) {
                    # more here - need a table that displays product-desc/name, seller-id/name, enddate, endtime
                    # also want buttons that link to that product's buyer_item page
                $productID = $row['productID'];
                $buyerID = $row['buyerID'];
                $watchID = $row['watchID'];
                echo "<tr><td style='width: 200px;'>".$productID."</td><td style='width: 600px;'>".$buyerID."</td><td>".$watchID."</td></tr>";
            } 
        echo "</table>";
            
        return $current_user;
        $connection->close();
        }
    }
}
?>