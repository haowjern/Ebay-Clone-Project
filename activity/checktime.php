<?php
session_start();
include 'email_sellers_autobidextend.php';
//this script runs every hour (from cron-jobs.txt) to search for listings that are expiring

$connection = mysqli_connect("localhost", "root", "", "ebaydb");

$_SESSION["expired_listings"]=array();

//set $t to the current hour, $today to today
$t=ltrim(date("H",time()),"0").":00:00";
$today=date("Y-m-d"); 

<<<<<<< HEAD
//select all the products (non-bidding) that expires at current hour today
=======
//select all the products that expire at current hour today
>>>>>>> addd6f05215f72f3036ff88b394b52f771a7baef

$sql="SELECT p.product_name,p.product_description,p.startdate,p.enddate,p.endtime,u.username,u.email
        FROM Product p,Users u
        WHERE p.sellerID=u.userID AND p.auctionable===0 AND p.enddate='$today' AND endtime='$t'" ;

$result=$connection->query($sql);


if ($result->num_rows>0){
            
    $_SESSION["expired_nonauctionlistings"]=array();

    //output data of each row in table
    while($row=$result->fetch_assoc()){
        $v=array();

        foreach ($row as $key => $value){
            $v[$key]=$value; 
        }
        
        array_push($_SESSION["expired_nonauction_listings"],$v);
        
    }

    // print_r($_SESSION["expired_listings"]);
    $newdate=date('Y-m-d', strtotime('+1 months'));

    //update the enddate in product table per productID
    foreach ($_SESSION["expired_nonauction_listings"]as $value){
        $productID=$value["productID"];
        echo $productID;

        $sql="UPDATE Product 
        SET enddate='$newdate'
        WHERE productID=$productID";

        $connection->query($sql);

        //send_email_updating_sellers(...)
    }


 //send email to the sellers involved, and notify them that the expiry date is now extended by a month by default. They can log in to view/change the date.


}

//select all the products (bidding) that expires at current hour today

$sql="SELECT p.product_name,p.product_description,p.startdate,p.enddate,p.endtime,u.username,u.email,b.buyerID
        FROM Product p,Users u,BidEvents b
        WHERE p.sellerID=u.userID AND p.auctionable===1 AND p.enddate='$today' AND endtime='$t'" ;

$result=$connection->query($sql);

if ($result->num_rows>0){
            
    $_SESSION["expired_auctionlistings"]=array();

    //output data of each row in table
    while($row=$result->fetch_assoc()){
        $v=array();

        foreach ($row as $key => $value){
            $v[$key]=$value; 
        }
        
        array_push($_SESSION["expired_auction_listings"],$v);
        
    }

    //award the bid to the highest bidder in the bidding table
    
    //remove the item from product table per productID
    $_SESSION["remove_productID"]=0;
    foreach ($_SESSION["expired_auction_listings"]as $value){
        $_SESSION["remove_productID"]=$value["productID"];
        
        include removelisting.php;

        $connection->query($sql);
    }
}


$connection->close();

unset($_SESSION["expired_nonauction_listings"]);
unset($_SESSION["expired_auction_listings"]);
unset($_SESSION["remove_productID"]);

?>