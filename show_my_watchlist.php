<?php 
session_start(); 
include './activity/watchlist_interface.php';     
$_SESSION['userID'] = 11;
 
$_SESSION['current_user'] = $_SESSION['userID']; // set the current user to be the buyer 
    
show_watchlist($_SESSION['current_user'], "show");  
?>