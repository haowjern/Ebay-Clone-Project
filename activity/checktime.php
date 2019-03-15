<?php
session_start();
include 'email_sellers_autobidextend.php';
include 'send_email.php';
//this script runs every hour (from cron-jobs.txt) to search for listings that are expiring
$myfile = fopen("/Applications/MAMP/anniebranch/ebay-database-system-project/cron-test.txt", "w");
fwrite($myfile, mktime());
$connection = mysqli_connect("localhost", "root", "", "ebaydb");
$_SESSION["expired_listings"]=array();
//set $t to the current hour, $today to today
$t=date("H",time());
if ($t!="00"){
    $t=ltrim(date("H",time()),"0").":00:00";
} else{
    $t=$t.":00:00";
}
$today=new DateTime();
$today_str = $today->format('Y-m-d');
//select all the products (non-bidding) that expires at current hour today
$sql="SELECT p.productID, p.product_name,p.product_description,p.startdate,p.enddate,p.endtime,u.username,u.email
        FROM Product p,Users u
        WHERE p.sellerID=u.userID AND p.auctionable=0 AND p.enddate='$today_str' AND endtime='$t'" ;
$result=$connection->query($sql);
if ($result->num_rows>0){
    $_SESSION["expired_nonauction_listings"]=array();
    //output data of each row in table
    while($row=$result->fetch_assoc()){
        $v=array();
        foreach ($row as $key => $value){
            $v[$key]=$value; 
        }
        
        array_push($_SESSION["expired_nonauction_listings"],$v);
    }
    print_r($_SESSION["expired_nonauction_listings"]);
    $newdate=date('Y-m-d', strtotime('+1 months'));
    foreach ($_SESSION["expired_nonauction_listings"]as $value){
        $current_productID = $value["productID"];
        $current_product_name = $value["product_name"];
        $current_description = $value["description"];
        $seller_name = $value["username"];
        $seller_email = $value["email"];
        //update the enddate in product table per productID
        $productID=$value["productID"];
        echo $productID;
        $sql="UPDATE Product 
        SET enddate='$newdate'
        WHERE productID=$productID";
        $connection->query($sql);
    }
 //send email to the sellers involved, and notify them that the expiry date is now extended by a month by default. They can log in to view/change the date.
}
// //select all the products (bidding) that expires at current hour today
// $sql="SELECT p.productID, p.product_name,p.product_description,p.quantity, p.categoryID, p.conditionID, p.sellerID, p.auctionable, p.startdate,p.enddate,p.endtime,b.buyerID
//         FROM Product p,BidEvents b
//         WHERE p.auctionable=1";
// $result_all=$connection->query($sql);
// if ($result_all->num_rows>0){
//     while ($value = $result_all->fetch_assoc()) {
//         $productID = $value["productID"];
//         $product_name = $value["product_name"];
//         $product_description = $value["product_description"];
//         $quantity = $value["quantity"];
//         $categoryID = $value["categoryID"];
//         $conditionID = $value["conditionID"];
//         $sellerID = $value["sellerID"];
//         $auctionable = $value["auctionable"];
//         $dealdate = $value["enddate"]; // no buyer comment and seller comment
//         $enddate = $value["enddate"];
//         $endtime = $value["endtime"];
        
//         // first check if buyer enddate has ended
//         if ($today >= new DateTime($enddate." ".$endtime)) {
//             $sql = "SELECT * FROM BidEvents 
//                 WHERE productID = '$productID'
//                 ORDER BY bidPrice DESC";
//             $result = $connection->query($sql);
//             if ($result->num_rows>0) {
//                 // get the highest bid payer
//                 while ($row = $result->fetch_assoc()) {
//                     $buyerID = $row["buyerID"];
//                     $payment = $row["payment"];
//                     $bidPrice = $row["bidPrice"];
//                     $bidDate = $row["bidDate"];
//                     $bidTime = $row["bidTime"]; 
//                     $dealprice = $bidPrice; 
//                     //get the user's email address
//                     $sql = "SELECT username, email, accountbalance FROM Users WHERE userID = $buyerID"; 
//                     $result_user=$connection->query($sql); 
//                     // if found
//                     if ($result_user->num_rows>0) {
//                         while ($row_user=$result_user->fetch_assoc()) {
//                             $buyer_name = $row_user["username"];
//                             $buyer_email = $row_user["email"];
//                             $accountbalance = $row_user["accountbalance"];
//                             // if there is enough in the bank account
//                             if ($accountbalance >= $bidPrice) {
//                                 $subject = "Congratulations! You have won the auction for '$product_name'!";
//                                 $body = "Description: ".$product_description;
//                                 $altbody = $body;
//                                 $emailee_name = "DatabaseCW";
//                                 send_to_email($buyer_email, $subject, $body, $altbody, $emailee_name);
            
//                                 // deduct balance from user
//                                 $remainingbalance = $accountbalance - $bidPrice;
//                                 $sql = "UPDATE Users SET accountbalance=$remainingbalance WHERE userID = $buyerID"; 
//                                 $result_user_update = $connection->query($sql); // should be true
//                                 //get the seller's email address
//                                 $sql = "SELECT email FROM Users WHERE userID = $sellerID"; 
//                                 $result_seller=$connection->query($sql); 
//                                 $row = $result_seller->fetch_assoc();
//                                 $seller_email = $row["email"];
//                                 // send email to notify seller too
//                                 $subject = "Congratulations! Your auction for '$product_name' has finished!";
//                                 $body = "Description: '$product_description'\n Awarded to: '$buyer_name'";
//                                 $altbody = $body;
//                                 send_to_email($seller_email, $subject, $body, $altbody, $emailee_name);
//                                 // move the item away from product to archive
//                                 $sql = "INSERT INTO Archive (
//                                         productID,
//                                         product_name, 
//                                         product_description, 
//                                         dealprice,
//                                         quantity,
//                                         categoryID, 
//                                         conditionID, 
//                                         buyerID, 
//                                         sellerID,
//                                         auctionable,
//                                         dealdate
//                                         ) VALUES (
//                                         $productID,
//                                         '$product_name', 
//                                         '$product_description', 
//                                         $dealprice,
//                                         $quantity,
//                                         $categoryID, 
//                                         $conditionID, 
//                                         $buyerID, 
//                                         $sellerID,
//                                         $auctionable,
//                                         '$dealdate'
//                                         )";
//                                 $result_move = $connection->query($sql);
//                                 // delete item from product
//                                 $sql = "DELETE FROM Product WHERE productID=$productID";
//                                 $result_del = $connection->query($sql);
//                             } // else give to the next payer 
//                         }
//                     } // else give to the next payer
//                 }
//             } else {
//                 $sql="UPDATE Product SET enddate='$newdate' WHERE productID=$productID";
//                 $connection->query($sql);
//             }
//         }
//     }
// }
// $connection->close();
// unset($_SESSION["expired_nonauction_listings"]);
// unset($_SESSION["expired_auction_listings"]);
// unset($_SESSION["remove_productID"]);
?>