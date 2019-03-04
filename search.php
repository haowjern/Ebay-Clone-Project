<?php 

function search_product($photo_arr, $inst) { // simple search method - feel fre to change 
    include "database.php";

    $sql="SELECT * FROM Product";
    $result = mysqli_query($connection, $sql); 
    if ($result->num_rows>0) {
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $_SESSION["search"] = $row; 
        
    } else {
        echo("Error: " . $sql . "<br>" . $connection->error);
    }

}


?> 