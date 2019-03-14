<?php 
session_start(); 

include_once './bid_product_interface.php';
include_once './probability_diff_interface.php';

// $_SESSION['userID'] = 1; // TODO: FOR TESTING ONLY - TO BE REMOVED

// bidding ONLY
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $_SESSION['bid']['price'] = $_POST['price']; // set new price
    $_SESSION['bid']['buyerID'] = $_SESSION['userID']; // set the current user to be the buyer 
    $_SESSION['bid']['payment'] = 0; // FALSE 
    $_SESSION['bid']['productID'] = $_SESSION['product']['productID'];
    $_SESSION['bid']['bidID'] = ""; 
    set_bidEvent($_SESSION['bid'], "insert");
    echo "done";

    $rating = $_POST['rating'];
    echo "test";
    echo $rating;
    $productID = $_SESSION['product']['productID'];
    $userID = $_SESSION['userID'];
    set_ratings($userID, $productID, $rating);
    $array = [];
    $array["productID"] = $productID;
    $array["buyerID"] = $userID; 
    
    set_popularity_diff($array, "insert");
    echo "done";

    //header('Location: ../index.php'); 

} else {
    echo "Not posting to bidding.";
}














?>



