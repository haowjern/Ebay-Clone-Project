<?php
function send_email_updating_watchers($bid_arr){
    include '../database.php';
    include 'send_email.php';

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
 
    // for each user who is watching this product:
    $email_to_arr = [];
    // get names and emails of all users watching this product
    $sql="SELECT * FROM Watchlist WHERE productID = '$productID'";
    $result1 = $connection->query($sql);
    if ($result1->num_rows>0) { 
        while ($row=$result1->fetch_assoc()) {
            $watcherID = $row['buyerID'];
            $sql = "SELECT * FROM Users WHERE userID = '$watcherID'";
            $result2 = $connection->query($sql);
            if ($result2){
                while ($row=$result2->fetch_assoc()) {
                    $email_to_arr['watcherName'] = $row['username'];
                    $email_to_arr['watcherEmail'] = $row['email'];
                }
            }
        }
    }
    
    foreach ($email_to_arr as $email_to=>$val) {
        // want to tell users that User has made BidPrice on Product
        $subject = "New bid on ".$productName;
        $body = "Hey ".$email_to_arr['watcherName']."!\nUser ".$latestBidderName." has made a new bid of ".$bidPrice." on ".$productName."!\nYou are receiving this because this product is in your watchlist.\nHave a nice day!\nFake ebay";
        $altbody = "Someone has made a bid on a product that you are watching!";
        $watchingUserEmail = $email_to_arr['watcherEmail'];
        $emailee_name = $email_to_arr['watcherName'];
        send_to_email($watchingUserEmail, $subject, $body, $altbody, $emailee_name);
    }

    // for each user who has made a bid on this product:
    // get names and emails of all users who have made a bid on this product
    $sql="SELECT * FROM BidEvents WHERE productID = '$productID'";
    $result3 = $connection->query($sql);
    if ($result3->num_rows>0) { 
        while ($row=$result3->fetch_assoc()) {
            $bidderID = $row['buyerID'];
            $sql = "SELECT * FROM Users WHERE userID = '$bidderID'";
            $result4 = $connection->query($sql);
            if($result4){
                while ($row=$result4->fetch_assoc()) {
                    $email_to_arr['bidderName'] = $row['username'];
                    $email_to_arr['bidderEmail'] = $row['email'];
                }
            }
        }
    }

    foreach ($email_to_arr as $email_to=>$val) {
        // want to tell users that User has made BidPrice on Product
        $subject = "New bid on ".$productName;
        print_r($email_to);
        $body = "Hey ".$email_to_arr['watcherName']."!\nUser ".$latestBidderName." has made a new bid of ".$bidPrice." on ".$productName."!\nYou are receiving this because you have an active bid on this product.\nHave a nice day!\nFake ebay";
        $altbody = "Someone has made a bid on a product that you currently have a bid on!";
        $bidderEmail = $email_to_arr['bidderEmail'];
        $emailee_name = $email_to_arr['bidderName'];
        send_to_email($bidderEmail, $subject, $body, $altbody, $emailee_name);
    }
}
?>