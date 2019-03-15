<?php
session_start();

include_once "../header.php";
include_once "../database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //select item in product table

    $productID=$_SESSION['product']['productID'];

    $buyerID=mysqli_real_escape_string($connection,$_SESSION["userID"]);
    $dealdate=mysqli_real_escape_string($connection,date('Y-m-d'));
    $dealprice=mysqli_real_escape_string($connection,$_POST['price']);
    $quantity=(integer)mysqli_real_escape_string($connection,$_POST['quantity']);

    $sql="SELECT * FROM Product WHERE productID=$productID";
    $result=$connection->query($sql);

    if ($result->num_rows>0){
        $row=$result->fetch_assoc();
        $sellerID=$row["sellerID"];
        $product_name=$row["product_name"];
        $product_description=$row["product_description"];
        $categoryID=$row["categoryID"];
        $conditionID=$row["conditionID"];
        $auctionable=(string)$row["auctionable"];
        $inventory=(integer)$row["quantity"];

        //check account balance
        $sql="SELECT accountbalance,username FROM users WHERE userID=$buyerID";
        $result1=$connection->query($sql);

        $row1=$result1->fetch_assoc();
        $accountbalance=$row1["accountbalance"];

        $price_total=$dealprice*$quantity;

        if ($accountbalance<$price_total){
            $message="You don't have enough money in your balance to complete this transaction. Top up your account balance in your profile.";
            $checked="invalid";
            $accountbalance_new=$accountbalance;
        } else{
            $message="You have enough balance.";
            $checked="valid";
        }

        if ($checked=="valid"){

            $accountbalance_new=$accountbalance-$price_total;

            //reduce account balance
            $sql="UPDATE users
                        SET accountbalance='$accountbalance_new'
                        WHERE userID=$buyerID";
                $connection->query($sql);

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
                $connection->query($sql);
            }

            //insert item in archive table
            $sql="INSERT INTO Archive (productID, product_name,product_description,dealprice,quantity,categoryID,conditionID,buyerID,sellerID,auctionable,dealdate) 
            VALUES($productID,'$product_name','$product_description',$dealprice,$quantity,$categoryID,$conditionID,$buyerID,$sellerID,$auctionable,'$dealdate')";
            
            if ($connection->query($sql)==TRUE){
            $message1="New archive record successfully created";

            } else {
            echo "Error: ". $sql . "<br>" . $connection->error;
            }
        }   



    } else{
        echo "Error: ". $sql . "<br>" . $connection->error;}
}

?>

<html>
<head>  
</head>
<body>
    <h1>Your are completing the transaction...</h1>

    <p>Product Name: <?php echo $product_name?></p>
    <p>Product Description: <?php echo $product_description?></p>
    <p>Deal Price (£): <?php echo $price_total?></p>
    <p>Quantity: <?php echo $quantity?></p>
    <p>Deal Date: <?php echo $dealdate?></p>
    <br>
    <p>My Account balance (£): <?php echo $accountbalance?></p>

    <p><?php echo $message?></p>

    <p><?php echo $message1?></p>

    <p>My New Account balance (£): <?php echo $accountbalance_new?></p>

</body>
</html>

<?php
//move an item from product table to archive table when it is bought / enddate has passed


 $connection->close();

 include "../footer.php";


?>

