<?php session_start(); 

$_SESSION["product"]["name"] = "Burger";
$_SESSION["product"]["photos"] = "./uploads/1.jpg";
$_SESSION["product"]["price"] = 12.00;
$_SESSION["product"]["auctionable"] = "";
$_SESSION["product"]["id"] = 1;

$is_bidding = TRUE; // this is for price
if (isset($_SESSION["product"]["auctionable"]) && $_SESSION["product"]["auctionable"] === TRUE) {
    $_SESSION["bid"] = $_SESSION["product"];
    $is_bidding = TRUE; 
}

// be able to enter a price and submit 
// javascript hoisting
?>



<html>
    <head>
        <h1>Buy an Item</h1>

        <script>
            function validateForm(submit_button) {
                if (submit_button.value == "Bid") {
                    let new_price = document.forms["buyer_item"]["price"].value;
                    let current_price = '<?php echo $_SESSION["product"]["price"]?>';
                    let error_msg = document.getElementById('error_price');

                    if (new_price > current_price) {
                        error_msg.innerText = "";
                        return true;
                       
                    } else {
                        error_msg.innerText = "Bid must be higher than current price.";
                        return false;
                    }
                }
                elseif (submit_button.value == "Watch") {
                    error_msg.innerText = "";
                    return true;
                }
            }
        </script>
    </head>

    <body>
        <form name="buyer_item">
            <img src="./uploads/1.jpg" alt="Picture of item">
            <h3>Item Name</h3>
            <p>Description of item</p>
            Quantity:
            <input type="number" name="quantity" placeholders="1" min="1" max="10" required>
            <?php if ($is_bidding) {?>
                Bid Price: <input name="price" id="price" type="number" step="0.01" min="0" max="10000" required> <span id="error_price"></span>
                Current Bid:
                <?php echo $_SESSION["product"]['price']?>
            <?php } else {?>
                Price:
            <?php echo $_SESSION["product"]["price"]?>
            <?php } ?>

            <h3>Seller Details</h3>
            <p>Name: Seller Name</p>

            <input type="submit" value="Bid" onclick="return validateForm(this)" formaction="./bid_product.php" formmethod="post">
            <input type="submit" value="Cart">
            <input type="submit" value="Watch" onclick="return validateForm(this)" formaction="./watch_product.php" formmethod="post">
            

        </form>
    </body>
</html>
