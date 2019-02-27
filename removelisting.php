<?php
session_start();

//script to remove listing from product table.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include "database.php";

    $productID=mysqli_real_escape_string($connection,$_POST["productID"]);

    $sql="DELETE FROM Product WHERE productID='$productID'";
            $result=$connection->query($sql);

            if ($result==TRUE){
                echo "Record deleted successfully";
            } else {
            echo "Error: ". $sql . "<br>" . $connection->error;
            }

    $connection->close();
}
?>