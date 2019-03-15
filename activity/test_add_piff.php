<?php

if (file_exists('../database.php')){
    include '../database.php';
} else {
    include './database.php';
}
include_once "probability_diff_interface.php"; 
$sql="SELECT * FROM Ratings";
$result = $connection->query($sql);

if ($result->num_rows>0) {
    while ($row=$result->fetch_assoc()) {
        $productID = $row["productID"];
        $userID = $row["userID"];

        // echo "<br>";
        // echo $productID;
        // echo "<br>";

        $array = [];
        $array["productID"] = $productID;
        $array["buyerID"] = $userID; 
        set_popularity_diff($array, "insert");
    }
}
?>