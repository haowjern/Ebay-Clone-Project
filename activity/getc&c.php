<?php
session_start();

//script to fetch all category and condition indices and names in session variables.
if (isset($_SESSION["category_all"])){
    unset($_SESSION["category_all"]);
};

if (isset($_SESSION["condition_all"])){
    unset($_SESSION["condition_all"]);
};

include "../database.php";


$sql="SELECT * FROM Category";
        $result=$connection->query($sql);

        if ($result->num_rows>0){

            while($row=$result->fetch_assoc()){

                $_SESSION["category_all"][$row["categoryID"]]=$row["categoryname"];

            }
            
        } else {
        echo "Error: ". $sql . "<br>" . $connection->error;
        }
        
        // print_r($_SESSION["category_all"]);

$sql="SELECT * FROM ConditionIndex";
        $result=$connection->query($sql);

        if ($result->num_rows>0){

            while($row=$result->fetch_assoc()){

                $_SESSION["condition_all"][$row["conditionID"]]=$row["conditionname"];}

        } else {
        echo "Error: ". $sql . "<br>" . $connection->error;
        }

        // print_r($_SESSION["condition_all"]);

$connection->close();

?>
