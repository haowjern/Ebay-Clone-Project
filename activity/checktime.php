<?php
session_start();

//this script runs every hour (from cron-jobs.txt) to search for listings that are expiring

$connection = mysqli_connect("localhost", "root", "", "ebaydb");

$_SESSION["expired_listings"]=array();

//set $t to the current hour, $today to today
$t=ltrim(date("H",time()),"0").":00:00";
$today=date("Y-m-d"); 

//select all the produc that expires at current hour today
$sql="SELECT * 
        FROM Product 
        WHERE enddate='$today' AND endtime='$t'
        ORDER BY sellerID";

// $sql="SELECT p.product_name,p.product_description,p.startdate,p.enddate,p.endtime,u.username,u.password1
//         FROM Product p,Users u
//         WHERE p.sellerID=u.userID AND p.enddate='$today' AND endtime='$t'";

$result=$connection->query($sql);


if ($result->num_rows>0){
            
    $_SESSION["expired_listings"]=array();

    //output data of each row in table
    while($row=$result->fetch_assoc()){
        $v=array();

        foreach ($row as $key => $value){
            $v[$key]=$value; 
        }
        
        array_push($_SESSION["expired_listings"],$v);
        
    }

    // print_r($_SESSION["expired_listings"]);
    $newdate=date('Y-m-d', strtotime('+1 months'));

    //update the enddate in product table per productID
    foreach ($_SESSION["expired_listings"]as $value){
        $productID=$value["productID"];
        echo $productID;

        $sql="UPDATE Product 
        SET enddate='$newdate'
        WHERE productID=$productID";

        $connection->query($sql);
    }

 //send email to the sellers involved, and notify them that the expiry date is now extended by a month by default. They can log in to view/change the date.


}


$connection->close();

?>