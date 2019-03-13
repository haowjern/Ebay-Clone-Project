<?php session_start(); 
include "../header.php";
include '../database.php';
include "photos_interface.php";
include "bid_product_interface.php";

$productID = $_POST["productID"];
$name = $_POST["product_name"];
$photos = $_POST["photos"]; 
$start_price = max($_POST["bidPrice"], $_POST["start_price"]);
$reserve_price = $_POST["reserve_price"];
$quantity = $_POST["quantity"];
$sellerID = $_POST["sellerID"];
$auctionable = $_POST["auctionable"];
$startdate = $_POST["startdate"];
$enddate = $_POST["enddate"];
$endtime = $_POST["endtime"];
$categoryname = $_POST["categoryname"];
$conditionname = $_POST["conditionname"]; 
$userID = $_SESSION['current_user']; 

// do not allow the seller to buy/bid their own items.
$cannot_buy = false;
if (!empty($userID)) {
    if ($userID == $sellerID) {
        $cannot_buy = true; 
    } 
}

unset($_SESSION['product']);
$_SESSION['product'] = array_merge([], $_POST); // create session variable so bid_product can use this 


$is_bidding = FALSE; // this is for price
if (strtolower($auctionable) == 1) {
    $is_bidding = TRUE;
    // get latest bid price from bid event; 
}

/*  if condition to check whether user is watching product

    get sql view: this user, watchlist
    and check if: this product in that view

    SELECT watchID IN watchlist WHERE userID = $_SESSION['userID'] AND productID = $_SESSION['product']['id'] 
        
        $sql = "SELECT watchID IN watchlist WHERE userID = $_SESSION['userID'] AND productID = $_SESSION['product']['id']"
        $result = $connection->query($sql); 
        if ($result==TRUE) {
            echo("Inserted new watchlist item.\n");
        } else {
            echo("Error: " . $sql . "<br>" . $connection->error);
        }

    if so, userwatching = 1
    else, userwatching = 0
    and down below,
     watch button is "watch" if userwatching == 1
     and is "stop watching" if userwatching == 0
*/



?>



<html>
    <head>
        <h1>Buy an Item</h1>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script>
            function validateForm(submit_button) {
                if (submit_button.value == "Bid") {
                    let new_price = document.forms["buyer_item"]["price"].value;
                    let current_price = '<?php echo $start_price?>';
                    let error_msg = document.getElementById('error_price');

                    if (new_price > current_price) {
                        error_msg.innerText = "";
                        return true;
                    } else {
                        error_msg.innerText = "Bid must be higher than current price.";
                        return false; 
                    }
                } else if (submit_button.value == "Watch") {
                    error_msg.innerText = "";
                    return true;
                } 
            }
        </script>

        <style>
        #container { 
            overflow:auto; 
        }

        .image { 
            width:150px;
            height:150px;
            float:left;
            position:relative; 
            background-size:cover
        }

        #table-wrapper {
            position:relative;
        }

        #table-scroll {
            height:150px;
            overflow:auto;  
            margin-top:20px;
        }

        #table-wrapper table {
            width:25%;
        }

        #table-wrapper table * {
            color:black;
        }

        #table-wrapper table thead th .text {
            position:absolute;   
            top:-20px;
            z-index:2;
            height:20px;
            width:35%;
        }
        
        #table-wrapper table tbody td {
            
        }
        
        </style>
    </head>

    <body>
        <form name="buyer_item">
            <div id="container">
                <?php 
                $photos = get_photo($productID); // list of photos with attributes as keys 
                foreach ($photos as $photo_index=>$photo_attr) {
                    $file_path = $photo_attr['file_path'];  
                    $photo_id = $photo_attr['photoID'];
                ?>
                    <div class="image" id="'<?php echo $file_path;?>'" style="background-image:url('<?php echo $file_path;?>');">
                    <img src="<?php echo $file_path?>" width=150 height=150>
                </div>
                <?php } ?>
            </div>
            <h3>Item Name</h3>
            <p>Description of item</p>
            Quantity:
            <input id='quantity' type="number" name="quantity" placeholders="1" min="1" max="10" required>
            <?php if ($is_bidding) {?>
                Bid Price: <input name="price" id="price" type="number" step="0.01" min="0" max="10000" required> <span id="error_price"></span>
                Current Bid:
                <?php echo $start_price?>
            <?php } else {?>
                Price:
            <?php echo $start_price?>
            <?php } ?>

            <h3>Seller Details</h3>
            <p>Name: Seller Name</p>
            <?php // have to wait to see how we get userID, is it form sessions? ?>
            <input id='bid' type="submit" value="Bid" onclick="return validateForm(this)" formaction="./bid_product.php" formmethod="post" <?php if (!$is_bidding || $cannot_buy) {echo "disabled";} ?>>
            <input id='buy' type="submit" value="Buy" onclick="return validateForm(this)" formaction="" formmethod="post" <?php if ($is_bidding || $cannot_buy) {echo "disabled";} ?>>

            
            <input type="submit" value="Cart" <?php if ($is_bidding) {echo "disabled";} ?>>     

            <?php
                // code for watch/stop_watching button switch
                $buyerID = $_SESSION['userID'];

                $sql = "SELECT COUNT(*) FROM watchlist WHERE productID = $productID AND buyerID = $buyerID";
                $result = $connection->query($sql); 
                $row = mysqli_fetch_row($result);
                if (implode(null,$row)==0) {
                    echo('<input type="submit" value="Watch" onclick="return validateForm(this)" formaction="../watch_product.php" formmethod="post">');
                } else {
                    echo('<input type="submit" value="Stop Watching" onclick="return validateForm(this)" formaction="../stop_watching_product.php" formmethod="post">');
                }       
            ?>
        </form>
        <div id="table-wrapper">
            <div id="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th><span class="text">Buyer</span></th>
                            <th><span class="text">Bid</span></th>
                        </tr>
                    </thead>
                    <tbody id="refresh-table">
                        <?php
                            include_once("refreshable_bidtable.php"); // include first table, later use script to update
                        ?> 
                    </tbody>
                </table>
            </div>
        </div>

        <!-- refresh table every 2 seconds-->
        <script type="text/javascript">
            $(document).ready (function() {
                setInterval(function() {
                    let transfer_data = {"productID": <?php echo $productID ?>};
                    $('#refresh-table').load("refreshable_bidtable.php", transfer_data);
                    
                }, 2000);
            });
        </script>

        <!-- refresh table periodically -->
        <!-- <script>
            var table = $('#refresh-table');
            var refresher = setInterval(function(){
                table.load("refreshable_bidtable.php");
            }, 2000);
            setTimeout(function() {
                clearInterval(refresher);
            }, 1800000);
        </script> -->
    </body>
</html>

<?php
include "../footer.php";
?>

