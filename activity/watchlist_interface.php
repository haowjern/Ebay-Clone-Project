<?php 
// THIS FILE MAY BE UNNECESSARY ...
// haow puts things into a list of arrays and then foreach list in the array, echo 
// see refreshable_bidtable and buyer_item
function show_watchlist($current_user) {
    include "database.php";
    $sql="SELECT * FROM Watchlist";
                # more here - need a table that displays product-desc/name, seller-id/name, enddate, endtime
                # also want buttons that link to that product's buyer_item page
                        # product name
                        # seller name
                            # enddate
                            # endtime
                                # latest bid
                                # latest bidder

    $result = mysqli_query($connection, $sql); 
    echo "<table>";
    if ($result->num_rows>0) {
        echo "<tr><th>ProductName</th><th>BuyerId</th><th>ProductID</th></tr>";
        #echo "<tr><th>ProductName</th><th>BuyerId</th><th>ProductID</th><th>LatestBidder</th><th>LatestBidPrice</th></tr>";
        # change sellerID to sellerName later... once userNames available...!  <th>LatestBid</th><th>LatestBidder</th>
        while($row = mysqli_fetch_array($result)) {
            $buyerID = $row['buyerID'];
            $watchID = $row['watchID'];

            $productID = $row['productID'];
                # get product name, enddate, endtime, (sellerID)
            $sql="SELECT * FROM Product WHERE productID = $productID";
            $result1 = mysqli_query($connection, $sql);
            $productName=mysqli_fetch_array($result1)['product_name'];

                # enddate and endtime don't work for productID=1 ??? are they there? seem to be null??
            $endDate=mysqli_fetch_array($result1)['enddate'];  
            $endTime=mysqli_fetch_array($result1)['endtime'];

            $sellerID=mysqli_fetch_array($result1)['sellerID'];


                # get seller username       [ doesn't work yet ]
            $sql="SELECT * FROM Users WHERE userID = $sellerID";
            $result1 = mysqli_query($connection, $sql);
            $sellerName=mysqli_fetch_array($result1)['username'];


                # get latest bid & latest bidder(ID->username)
            $sql="SELECT * FROM BidEvents WHERE productID = $productID";
            $result1 = mysqli_query($connection, $sql);
            $bidderID=mysqli_fetch_array($result1)['bidID'];  # make sure selects most recent bid?? different fetch array?
            $bidPrice=mysqli_fetch_array($result1)['bidPrice']; # same as "
            
            $sql="SELECT * FROM Users WHERE userID = $bidderID";
            $result1 = mysqli_query($connection, $sql);
            $bidderName=mysqli_fetch_array($result1)['username'];


                

            echo "<tr><td>".$productName."</td><td>".$buyerID."</td><td>".$productID."</td></tr>";
            #echo "<tr><td>".$productName."</td><td>".$buyerID."</td><td>".$productID."</td><td>".$bidderName."</td><td>".$bidPrice."</td></tr>";


            #echo "<tr><td style='width: 200px;'>".$productName."</td><td style='width: 600px;'>".''."</td><td>".$watchID."</td><td>".$endDate."</td><td>".$endTime."</td></tr>";
        } 
    echo "</table>";
        
    return $current_user;
    $connection->close();
    }
}
?>