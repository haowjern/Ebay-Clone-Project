<?php 
session_start(); 

include './activity/watch_product_interface.php'; 
$_SESSION['userID'] = 1;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // TODO: check this is correct WHEN darren puts in his user details into $_SESSION

    $_SESSION['watch']['buyerID'] = $_SESSION['userID'];
    $_SESSION['watch']['productID'] = $_SESSION['product']['id'];
    $_SESSION['watch']['watchID'] = "";

    set_watch($_SESSION['watch'], "insert");                 #################    set_watchEvent, insert!
} else {
    echo "Not posting to bidding.";         ##### alternative for watch?
}



# can do an identical one (ish) for remove from watchlist with instruction "delete"


?>



