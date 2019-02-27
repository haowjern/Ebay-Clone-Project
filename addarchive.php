<?php
session_start();
//move an item from product table to archive table when it is bought / enddate has passed

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include "database.php";

    //select item in product table

    $productID=mysqli_real_escape_string($connection,$_POST['productID']);
    $buyerID=mysqli_real_escape_string($connection,$_POST['buyerID']);
    $dealdate=mysqli_real_escape_string($connection,date('Y-m-d'));
    $dealprice=mysqli_real_escape_string($connection,$_POST['dealprice']);
    $quantity=(integer)mysqli_real_escape_string($connection,$_POST['quantity']);

    $sql="SELECT * FROM Product WHERE productID='$productID'";
    $result=$connection->query($sql);

    if ($result->num_rows>0){
        $row=$result->fetch_assoc();
        $sellerID=$row["sellerID"];
        $product_description=$row["product_description"];
        $categoryID=$row["categoryID"];
        $conditionID=$row["conditionID"];
        $auctionable=(string)$row["auctionable"];
        $inventory=(integer)$row["quantity"];

        //reduce inventory in product table
        $inventory_new=$inventory-$quantity;

        //if inventory goes down to zero, remove the item; else update the inventory(quantity) in product table
        if ($inventory_new==0){
            //remove the item from product table
            $_SESSION["remove_productID"]=$productID;

            include "removelisting.php";

        }else{
            //update the quantity in product table
            $sql="UPDATE Product 
                    SET quantity='$inventory_new'
                    WHERE productID=$productID";
        }

        //insert item in archive table
        $sql="INSERT INTO Archive (productID, product_description,dealprice,quantity,categoryID,conditionID,buyerID,sellerID,auctionable,dealdate) 
        VALUES('$productID','$product_description','$dealprice','$quantity','$categoryID','$conditionID','$sellerID','$auctionable','$dealdate')";
        
        if ($connection->query($sql)==TRUE){
        echo "New archive record successfully created";

        } else {
        echo "Error: ". $sql . "<br>" . $connection->error;
        }


    }else{
        echo "Error: ". $sql . "<br>" . $connection->error;}

  

    $connection->close();

}
?>