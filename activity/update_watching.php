<?php
function send_email_updating_watchers($bid_arr) {
    include 'database.php';

    $bidID = $bid_arr['bidID'];
    $productID = $bid_arr['productID'];
    $bidderID = $bid_arr['buyerID'];
    $payment = $bid_arr['payment'];
    $bidPrice = $bid_arr['price'];

    // get username of user who placed the bid
    $sql="SELECT * FROM Users WHERE userID = $bidderID";
    $result = mysqli_query($connection, $sql);
    $bidderName=mysqli_fetch_array($result1)['username'];


    // specify email subject, body, and altbody
    $subject = "Test email subject";
    $body = "Test email body...";
    $altbody = "Test email altbody......";

    // get emails of all users watching this product
    $sql="SELECT * FROM Watchlist WHERE productID = $productID";
    $result = mysqli_query($connection, $sql);
    while ($row=mysqli_fetch_array($result1)) {
        $watchingUserID = $row['buyerID'];

        $sql="SELECT * FROM Users WHERE userID = $watchingUserID";
        $result = mysqli_query($connection, $sql);
        $watchingUserEmail=mysqli_fetch_array($result1)['email'];
        echo $watchingUserEmail;

        //send_to_email($watchingUserEmail, $subject, $body, $altbody);
       

    }


    
}

/*
input: productID (being bid on), bidPrice (being made), userID of person making bid
send an email to the emails of each userID in the watchlist table where productID matches the productID that is being bid on
*/


?>