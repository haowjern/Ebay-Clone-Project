<?php
function send_email_updating_watchers($bid_arr){
    include '../database.php';
    include 'send_email.php';

    // $bid_arr gives   bidID, productID, buyerID, price

    $bidID = $bid_arr['bidID'];
    $productID = $bid_arr['productID'];
    $bidderID = $bid_arr['buyerID'];
    $payment = $bid_arr['payment'];
    $bidPrice = $bid_arr['price'];

    // get username of user who placed the bid
    $sql="SELECT * FROM Users WHERE userID = '$bidderID'";
    $result = $connection->query($sql);
    if ($result->num_rows>0) { 
        while ($row=$result->fetch_assoc()) {
            $latestBidderName = $row['username'];
        }
    }
 
    // get name of product being bid on / watched
    $sql="SELECT * FROM Product WHERE productID = '$productID'";
    $result = $connection->query($sql);
    if ($result->num_rows>0) { 
        while ($row=$result->fetch_assoc()) {
            $productName = $row['product_name'];
        }
    }
 

    $email_to_arr = [];
    // get emails of all users watching this product
    $sql="SELECT * FROM Watchlist WHERE productID = '$productID'";
    $result1 = $connection->query($sql);
    if ($result1->num_rows>0) { 
        while ($row=$result1->fetch_assoc()) {
            $watcherID = $row['buyerID'];
            $sql = "SELECT * FROM User WHERE userID = '$watcherID'";
                $result2 = $connection->query($sql);
                while ($row=$result2->fetch_assoc()) {
                    $email_to_arr['watcherName'] = $row['username'];
                    $email_to_arr['watcherEmail'] = $row['email'];
            }
        }
    }
    
    foreach ($email_to_arr as $email_to) {
        // $productName     $email_to_arr['watcherName']     $bidPrice  $latestBidderName

        // want to tell users that User has made BidPrice on Product

        $subject = "New bid on ".$productName;
        $body = "Hey ".$email_to['watcherName']."!\nUser ".$latestBidderName." has made a new bid of ".$bidPrice." on ".$productName;
        $altbody = "Someone has made a bid on a product that you are watching!";

        $watchingUserEmail = $email_to['watcherEmail'];
        $emailee_name = $email_to['watcherName'];

        send_to_email($watchingUserEmail, $subject, $body, $altbody, $emailee_name);
    }


   
    // for each user who has made a bid on this product:
        // replicate above but for bid!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        
    

}

?>