<?php 
session_start(); 

include_once './bid_product_interface.php';
include_once './probability_diff_interface.php';
include_once './send_email.php';

// $_SESSION['userID'] = 1; // TODO: FOR TESTING ONLY - TO BE REMOVED

// bidding ONLY
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $_SESSION['bid']['price'] = $_POST['price']; // set new price
    $_SESSION['bid']['buyerID'] = $_SESSION['userID']; // set the current user to be the buyer 
    $_SESSION['bid']['payment'] = 0; // FALSE 
    $_SESSION['bid']['productID'] = $_SESSION['product']['productID'];
    $_SESSION['bid']['bidID'] = ""; 
    set_bidEvent($_SESSION['bid'], "insert");

    $productID = $_SESSION['bid']['productID'];
    // sql command to get all users that are bidding on this product

    $sql = "SELECT * 
            FROM Users u, bidEvents b,Product p 
            WHERE b.buyerID = u.userID AND p.productID=b.productID AND b.productID=$productID";
    
    
    $result = $connection->query($sql);
    if ($result->num_rows >0) {
        while ($row=$result->fetch_assoc()) {
            $email=$row["email"];
            
            // for each user, get their emails, and send this to them
            $subject = "New bid on ".$productName;
            $body = "Hey ".$row["username"]."!\nUser ".$latestBidderName." has made a new bid of ".$_POST["price"]." on ".$row["product_name"]."!\nYou are receiving this because this product is in your watchlist.\nHave a nice day!\nFake ebay";
            $altbody = "Someone has outbidded you!";
            $emailee_name = $row["username"];
            send_to_email($email, $subject, $body, $altbody, $emailee_name);
            
        }
    }
    

    $rating = $_POST['rating'];
    $productID = $_SESSION['product']['productID'];
    $userID = $_SESSION['userID'];
    set_ratings($userID, $productID, $rating);
    $array = [];
    $array["productID"] = $productID;
    $array["buyerID"] = $userID; 
    
    set_popularity_diff($array, "insert");
    echo "done";

    header('Location: ../index.php'); 

} else {
    echo "Not posting to bidding.";
}














?>



