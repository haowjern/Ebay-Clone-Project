<?php 
session_start(); 

include './activity/watch_product_interface.php';     
//$_SESSION['userID'] = 11;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $_SESSION['watch_action']['buyerID'] = $_SESSION['userID']; // set the current user to be the buyer 
    $_SESSION['watch_action']['productID'] = $_SESSION['product']['productID'];

    set_watch($_SESSION['watch_action'], "delete");  

} else {
    echo "Not posting to watchlist.";         
}

?>



