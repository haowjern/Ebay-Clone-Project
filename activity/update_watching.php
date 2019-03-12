<?php
    include '../database.php';

    $bidID = $bid_arr['bidID'];
    $productID = $bid_arr['productID'];
    $bidderID = $bid_arr['buyerID'];
    $payment = $bid_arr['payment'];
    $bidPrice = $bid_arr['price'];

    // get username of user who placed the bid
    $sql="SELECT * FROM Users WHERE userID = '$bidderID'";
    $result = mysqli_query($connection, $sql);
    $bidderName=mysqli_fetch_array($result)['username'];


    // specify email subject, body, and altbody
    $subject = "Test email subject";
    $body = "Test email body...";
    $altbody = "Test email altbody......";

    // get emails of all users watching this product
    $sql="SELECT * FROM Watchlist WHERE productID = '$productID'";
    $result = mysqli_query($connection, $sql);

    // for each user who is watching this product:
    while ($row=mysqli_fetch_array($result)) {
        $watchingUserID = $row['buyerID'];

        $sql="SELECT * FROM Users WHERE userID = '$watchingUserID'";
        $result = mysqli_query($connection, $sql);
        $watchingUserEmail=mysqli_fetch_array($result)['email'];
        
        echo $watchingUserEmail;
        
        // send email to this user
        send_to_email($watchingUserEmail, $subject, $body, $altbody);
    }
       

    // get emails of all users who have made a bid on this product          .... for all vars when SQL querying, use single quote
    $sql="SELECT * FROM BidEvents WHERE productID = '$productID'";
    $result = mysqli_query($connection, $sql);
    
    // for each user who has made a bid on this product:
    while ($row=mysqli_fetch_array($result)) {
        $watchingUserID = $row['buyerID'];

        $sql="SELECT * FROM Users WHERE userID = '$watchingUserID'";
        $result = mysqli_query($connection, $sql);
        $watchingUserEmail=mysqli_fetch_array($result)['email'];
        
        echo $watchingUserEmail;

        // send email to this user
        send_to_email($watchingUserEmail, $subject, $body, $altbody);
       

    }



?>