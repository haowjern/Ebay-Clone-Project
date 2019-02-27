<?php 

session_start(); 

include 'bid_product_interface.php';
$_SESSION['userID'] = 11;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['bid']['price'] = $_POST['price']; // set new price
    // TODO: check this is correct WHEN darren puts in his user details into $_SESSION
    $_SESSION['bid']['buyerID'] = $_SESSION['userID']; // set the current user to be the buyer 
    $_SESSION['bid']['payment'] = 0; 
    $_SESSION['bid']['productID'] = $_SESSION['product']['id'];
    $_SESSION['bid']['bidID'] = ""; 

    set_bidEvent($_SESSION['bid'], "insert");
} else {
    echo "Not posting to bidding.";
}














?>



