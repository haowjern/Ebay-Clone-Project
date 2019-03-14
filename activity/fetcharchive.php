<?php 
session_start();

//obtain all the archive listing items of the seller

//call getc&c.php to store all category and condition indices and names in session variables
if (!isset($_SESSION["category_all"])||!isset($_SESSION["condition_all"])){
    include "getc&c.php";}
    
include '../database.php';

unset($_SESSION["all_archive_listings"]);

if (isset($_SESSION["archive_search_criteria"])){

    $criteria=$_SESSION["archive_search_criteria"];

    // write the query according to search criteria
    if ($criteria=="sellerID"){

        $sellerID=mysqli_real_escape_string($connection,$_SESSION['userID']);


        $sql="SELECT a.archiveID, 
                    a.product_name,
                    a.product_description,
                    a.dealprice,
                    a.quantity,
                    a.categoryID,
                    a.conditionID,
                    a.auctionable,
                    a.dealdate,
                    a.ratings,
                    a.buyer_comment,
                    a.seller_comment,
                    u.username
        FROM Archive a,Users u
        WHERE a.buyerID=u.userID AND a.sellerID='$sellerID'";

        } elseif($criteria=="buyerID") {

        $buyerID=mysqli_real_escape_string($connection,$_SESSION['userID']);

        $sql="SELECT a.archiveID, 
                    a.product_name,
                    a.product_description,
                    a.dealprice,
                    a.quantity,
                    a.categoryID,
                    a.conditionID,
                    a.auctionable,
                    a.dealdate,
                    a.ratings,
                    a.buyer_comment,
                    a.seller_comment,
                    u.username
        FROM Archive a,Users u
        WHERE a.sellerID=u.userID AND a.buyerID='$buyerID'";
    }

    $result=$connection->query($sql);

    if ($result->num_rows>0){
        $_SESSION["all_archive_listings"]=array();
    
        //output data of each row in table
        while($row=$result->fetch_assoc()){
            $v=array();


            foreach ($row as $key => $value){
                $v[$key]=$value;
            }

            //obtain the category and condition from sessionv variables
            $v["categoryname"]=$_SESSION["category_all"][$v["categoryID"]];
            $v["conditionname"]=$_SESSION["condition_all"][$v["conditionID"]];

            unset($v["categoryID"]);
            unset($v["conditionID"]);
            
            array_push($_SESSION["all_archive_listings"],$v);
        }

        // print_r($_SESSION["all_archive_listings"]);

        

    } else {
        echo "no result found";
    }

    $connection->close();

}

?>
</html>
