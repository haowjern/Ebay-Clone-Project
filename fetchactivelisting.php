<?php 
session_start();

//obtain all the active listing items of the seller

//call getc&c.php to store all category and condition indices and names in session variables
include "getc&c.php";
include 'database.php';

unset($_SESSION["all_active_listings"]);

$sellerID=mysqli_real_escape_string($connection,$_SESSION['userID']);


$sql="SELECT * FROM Product WHERE sellerID='$sellerID'";
$result=$connection->query($sql);

if ($result->num_rows>0){
    $_SESSION["all_active_listings"]=array();
 
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
        
        array_push($_SESSION["all_active_listings"],$v);
     }

    // print_r($_SESSION["all_active_listings"]);

    

} else {
    echo "no result found";
}

$connection->close();

?>
</html>