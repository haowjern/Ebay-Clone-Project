<?php 
session_start(); 
include "header.php";
include './activity/watchlist_interface.php';     
include './activity/send_email.php';
$_SESSION['userID'] = 11;
 
$_SESSION['current_user'] = $_SESSION['userID']; // set the current user to be the buyer 
    
// testing email
$email = 'sergi.bray@gmail.com';
$subject = 'test';
$body = 'hello from database';
$altbody = 'hello from db (altbody)';
echo "testing mail...";
//mail($email, $subject, $body);

send_to_email($email, $subject, $body, $altbody);

show_watchlist($_SESSION['current_user']);  
?>




<?php
include "footer.php";
?>

