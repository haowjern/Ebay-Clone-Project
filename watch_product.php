<?php 
session_start(); 

include './activity/watch_product_interface.php';     

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['watch']['buyerID'] = $_SESSION['userID']; // set the current user to be the buyer 
    $_SESSION['watch']['productID'] = $_SESSION['product']['productID'];
    set_watch($_SESSION['watch'], "insert");  
} else {
    echo "Not posting to watchlist.";         
}
?>
