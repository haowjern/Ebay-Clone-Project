<?php
session_start();

include "../header.php";

//script to remove listing from product table.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include "../database.php";

    $productID=mysqli_real_escape_string($connection,$_POST["productID"]);

}else{
    
    $productID=mysqli_real_escape_string($connection,$_SESSION["remove_productID"]);
}

$sql="DELETE FROM Product WHERE productID='$productID'";
        $result=$connection->query($sql);

        if ($result==TRUE){
            echo "Record deleted successfully";

        } else {
        echo "Error: ". $sql . "<br>" . $connection->error;
        }


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $connection->close();

    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'sellershop.php';
    $link="http://".$host.$uri."/".$extra;

    header("Location: http://$host$uri/$extra");
}

include "../footer.php";
?>


