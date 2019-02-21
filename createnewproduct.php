<?php
session_start();

// [$productID,$product_description,$price,$quantity,$conditionname,$categoryname,$sellerID,$auctionable,$enddate]=include "listing.php";

if (isset($_SESSION["listing"])) {

    //connect to database
    include 'database.php';

    //set the variables with session values and escape mysql characters
    $productID=mysqli_real_escape_string($connection,$_SESSION["listing"]["productID"]);
    $product_description=mysqli_real_escape_string($connection,$_SESSION["listing"]["product_description"]);
    $price=mysqli_real_escape_string($connection,$_SESSION["listing"]["price"]);
    $quantity=mysqli_real_escape_string($connection,$_SESSION["listing"]["quantity"]);
    $conditionname=mysqli_real_escape_string($connection,$_SESSION["listing"]["conditionname"]);
    $categoryname=mysqli_real_escape_string($connection,$_SESSION["listing"]["categoryname"]);
    $sellerID=mysqli_real_escape_string($connection,$_SESSION["listing"]["sellerID"]);
    $auctionable=mysqli_real_escape_string($connection,$_SESSION["listing"]["auctionable"]);
    $enddate=mysqli_real_escape_string($connection,$_SESSION["listing"]["enddate"]);


    //look up the categoryID and conditionID based on user input
    $sql="SELECT categoryID FROM Category WHERE categoryname='$categoryname'";
        $result=$connection->query($sql);
        if ($result==TRUE){
            $row=$result->fetch_assoc();
            $categoryID=$row['categoryID'];
        } else {
        echo "Error: ". $sql . "<br>" . $connection->error;
        }

    $sql="SELECT conditionID FROM ConditionIndex WHERE conditionname='$conditionname'";
        $result=$connection->query($sql);
        if ($result==TRUE){
            $row=$result->fetch_assoc();
            $conditionID=$row['conditionID'];
        } else {
        echo "Error: ". $sql . "<br>" . $connection->error;
        }

    if (empty($productID)){
        //insert new row into product database with new productID
        $sql="INSERT INTO Product (product_description,price,quantity,categoryID,conditionID,sellerID,auctionable,enddate) 
        VALUES($product_description,$price,$quantity,$categoryID,$conditionID,$sellerID,$auctionable,$enddate)";
        if ($connection->query($sql)==TRUE){
            echo "New record successfully created for product";
        } else {
            echo "Error: ". $sql . "<br>" . $connection->error;
        }

    }else{
        // $productID="'".$details['productID']."'";
        //fetch the existing product
        $sql="SELECT productID,product_description FROM Product WHERE productID=$productID";
        $result=$connection->query($sql);
        if ($result==TRUE){
            $row=$result->fetch_assoc();
            echo $row['productID'].$row['product_description'];
        } else {
            echo "Error: ". $sql . "<br>" . $connection->error;
        }

    }

    $connection->close();

//add item to auction table if it is auctionable
  if ($auctionable==1){
    // call the query file to insert item into auction table
  }
}


function set_photo($photo_obj, $instr) {
    /* Add photo to database 
    Parameters: 
    - <$photo_obj>: Object with attributes - photoID, productID, file_path
    - <$instr>: 
    */ 

    // check object has the correct properties
    $properties = ["photoID", "productID", "file_path"];
    foreach ($properties as $value) {
        if (!property_exists ($photo_obj, $value)) {
            echo "Parameter is not an object with the correct properties"; 
        }
    }

    // add new photo 
    if ($instr = "insert") {
        $sql="INSERT INTO Photos (productID, file_path) VALUES ({$photo_obj['productID']}, {$photo_obj['file_path']})";

        if ($connection->query($sql)==TRUE) {
            echo("Inserted new photos.");
        } else {
            echo("Error: " . $sql . "<br>" . $connection->error);
        }

    // update photo
    } elseif ($instr = "update") {
        $sql="INSERT INTO Photos (productID, file_path) VALUES ({$photo_obj['productID']}, {$photo_obj['file_path']}) 
        WHERE photoID = {$photo_obj['photoID']}";

        if ($connection->query($sql)==TRUE) {
            echo("Updated photos.");
        } else {
            echo("Error: " . $sql . "<br>" . $connection->error);
        }

    // delete photo 
    } elseif ($instr = "delete") {
        $sql="DELETE FROM Photos WHERE photoID = {$photo_obj['photoID']}";

        if ($connection_->query($sql)==TRUE) {
            echo("Deleted photo successfully.");
        } else {
            echo("Error: " . $sql . "<br>" . $connection->error);
        }

    } else {
        echo("Error: Selected wrong instruction for set_photo.");
    }

    return $photo_obj;
}

//empty the session element and destroy session
unset($_SESSION["listing"]);
session_destroy();
?>
