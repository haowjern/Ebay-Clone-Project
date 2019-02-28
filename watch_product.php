<?php 

session_start(); 

include 'watch_product_interface.php';      #############
$_SESSION['userID'] = 11;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $_SESSION['watch']['price'] = $_POST['price']; // set new price     ############################ don't need a lot of this......
    
    // TODO: check this is correct WHEN darren puts in his user details into $_SESSION

    $_SESSION['watch']['buyerID'] = $_SESSION['userID']; // set the current user to be the buyer 
    $_SESSION['watch']['payment'] = 0; 
    $_SESSION['watch']['productID'] = $_SESSION['product']['id'];
    
    $_SESSION['watch']['watchID'] = "";                                                     ######   watchID 

    set_watchEvent($_SESSION['watch'], "insert");                 #################    set_watchEvent, insert!
} else {
    echo "Not posting to bidding.";         ##### alternative for watch?
}














?>



