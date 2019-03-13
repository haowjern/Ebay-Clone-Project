<?php 
session_start(); 

include_once './bid_product_interface.php';
include_once './rating_interface.php';

$_SESSION['userID'] = 1; // TODO: FOR TESTING ONLY - TO BE REMOVED

// bidding ONLY
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $_SESSION['bid']['price'] = $_POST['price']; // set new price
    $_SESSION['bid']['buyerID'] = $_SESSION['userID']; // set the current user to be the buyer 
    $_SESSION['bid']['payment'] = 0; // FALSE 
    $_SESSION['bid']['productID'] = $_SESSION['product']['productID'];
    $_SESSION['bid']['bidID'] = ""; 
    set_bidEvent($_SESSION['bid'], "insert");

    $_SESSION['rating']['productID'] = $_SESSION['product']['productID'];
    $_SESSION['rating']['userID'] = $_SESSION['userID'];
    $_SESSION['rating']['ratingValue'] = $_POST['rating'];
    set_rating($_SESSION['rating'], "insert");

    header('Location: ../index.php'); 

} else {
    echo "Not posting to bidding.";
}














?>



