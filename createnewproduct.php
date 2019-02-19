<?php

function newproduct($details) {
    //connect to database
    include 'database.php';

    $productID=$details['productID'];
    $product_description="'".$details['product_description']."'";
    $price=$details['price'];
    $quantity=$details['quantity'];
    $categoryID=$details['categoryID'];
    $conditionID=$details['conditionID'];
    $sellerID="'".$details['sellerID']."'";
    $auctionable=$details['auctionable'];
    $enddate="'".$details['enddate']."'";



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


// $productID="";
// $product_description="mouse";
// $price=2.5;
// $quantity=10;
// $categoryID="01";
// $conditionID="01";
// $sellerID="c1";
// $auctionable=0;
// $enddate="30/4/2019";

// $details=array("productID"=>$productID,"product_description"=>$product_description,"price"=>$price,"quantity"=>$quantity,
// "categoryID"=>$categoryID,"conditionID"=>$conditionID,"sellerID"=>$sellerID,"auctionable"=>$auctionable,"enddate"=>$enddate);

// newproduct($details);

?>