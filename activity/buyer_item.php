<?php session_start(); 
include "../header.php";
include '../database.php';
include "photos_interface.php";
include_once "bid_product_interface.php";

$productID = $_POST["productID"];
$name = $_POST["product_name"];
$description = $_POST["product_description"];
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
            <h3>Product Name: <?php echo $name?></h3>
            <p>Description: <?php echo $description ?></p>
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
            <br>
            Item Rating(0-10): <input name="rating" id="rating" type="number" step="1" min="1" max="10" value="5" required>

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
                            <th><span class="text">Bid Date</span></th>
                            <th><span class="text">Bid Time</span></th>
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

        <?php 
        include "./probability_diff_interface.php";
        $userID = $_SESSION["userID"];
        $n = 10; 
        
        $g_rec = get_general_recommendations($productID, $n);
        $g_suggestions = array_keys($g_rec);
        $g_suggestions = array_values(array_diff($g_suggestions, array($productID)));

        unset($_SESSION["product_search_criteria"]);
        $_SESSION["product_search_criteria"]=["productID", $g_suggestions];
        include './fetchactivelisting.php';

        if (!empty($g_rec)) {
            $count=count($_SESSION["all_active_listings"]);
            if ($count==0){
                echo "no result found";
            } else {
        ?> 
            <h2> Customers who bought this have also bought: </h2>
            <p id="t"></p>
            <!-- create table header -->
            <table id=active_listing_table width="device-width,initial-scale=1">
                <tr>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Product Details
                    <br><br>
                    </th>
                    <th>Start Price (£)
                    <br><br>
                    </th>
                    <th>Latest Bid (£)
                    <br><br>
                    </th>
                    <th>Listing starts on
                    <br><br>
                    <br><span style="font-size:12px">(on/after)</span>
                    </th>
                    <th>Listing ends on
                    <br><br>
                    <br><span style="font-size:12px">(on/before)</span>
                    </th>
                    <th>Auctionable?</th>
                    <th>Action</th>
                </tr>   
            </table>

            <script>
            var count="<?php echo $count?>";
            document.getElementById("t").innerHTML = "No. of active listings: "+count;

            //copy the php array into javascript array
            var each_listing=<?php echo json_encode($_SESSION['all_active_listings'],JSON_PRETTY_PRINT)?>;

            <?php unset($_SESSION["all_active_listings"]);?>;

            var table=document.getElementById("active_listing_table");
            for (i=0;i<count;i++){
                //for each active listing, create a row in the table.
                var row=table.insertRow(-1);
                var cell_image=row.insertCell(0);
                var cell_productname=row.insertCell(1);
                var cell_details=row.insertCell(2);
                var cell_startprice=row.insertCell(3);
                var cell_latest_bid_price=row.insertCell(4);
                var cell_startdate=row.insertCell(5);
                var cell_enddate=row.insertCell(6);
                var cell_auctionable=row.insertCell(7);
                var cell_action=row.insertCell(8);


                //insert image iin the 1st column (image)
                cell_image.style.textAlign="center";
                cell_image.innerHTML=`<img src=${each_listing[i]['photos'][0]['file_path']} alt='Image' style=max-height:100%; max-width:100%>`
                cell_image.height=100; // scale size
                cell_image.width=100; // scale size 

                //insert product name into the 2nd column (Product Name)
                cell_productname.style.textAlign="center";

                cell_productname.innerHTML=each_listing[i]["product_name"];

                //insert product details into the 3rd column (Details)
                cell_details.style.textAlign="center";

                cell_details.innerHTML=each_listing[i]["product_description"]+
                                            "<br>quantity: "+each_listing[i]["quantity"]+
                                            "<br>category: "+each_listing[i]["categoryname"]+
                                            "<br>condition: "+each_listing[i]["conditionname"]+"<br><br>";

                //insert start price into the 4th column (start price)
                cell_startprice.style.textAlign="center";
                cell_startprice.innerHTML=each_listing[i]["start_price"];

                //insert latest bid price into the 5th column (latest bid price)
                cell_latest_bid_price.style.textAlign="center";
                let bid_price = each_listing[i]["latest_bid"]["0"]["bidPrice"];
                if (each_listing[i]["auctionable"]==="1"){
                    if (bid_price) {
                        cell_latest_bid_price.innerHTML= bid_price;
                    } else {
                        cell_latest_bid_price.innerHTML= each_listing[i]["start_price"];
                    }
                } else {
                    cell_latest_bid_price.innerHTML="N/A";
                }

                //insert listing startdate into the 6th column (listing startdate)
                cell_startdate.style.textAlign="center";
                cell_startdate.innerHTML=each_listing[i]["startdate"];

                //insert listing enddate into the 7th column (listing enddate)
                cell_enddate.style.textAlign="center";
                cell_enddate.innerHTML=each_listing[i]["enddate"]+" "+each_listing[i]["endtime"];

                //insert listing auction status into the 8th column (auction)
                cell_auctionable.style.textAlign="center";

                if (each_listing[i]["auctionable"]==="0"){
                    each_listing[i]["auctionable"]="No";
                    cell_auctionable.innerHTML="No";
                } else {
                    cell_auctionable.innerHTML="Yes";
                }

                //insert forms with buttons in 9th column (action)
                cell_action.style.textAlign="center";

                //create the form to see more details of item
                var fm_go_details=document.createElement('form');
                //name the form with productID
                fm_go_details.setAttribute("name","form_go_details"+each_listing[i]["productID"]);
                fm_go_details.setAttribute("method","post");
                fm_go_details.setAttribute("action","activity/buyer_item.php");


                for (var index in each_listing[i]){
                    //console.log(each_listing[i]);
                    var hiddenField=document.createElement("input");
                    hiddenField.setAttribute("type","hidden");
                    hiddenField.setAttribute("name",index);
                    hiddenField.setAttribute("value",each_listing[i][index]);
                    fm_go_details.appendChild(hiddenField);

                    
                    if (index == "latest_bid") { 
                        var hiddenField=document.createElement("input");
                        hiddenField.setAttribute("type","hidden");
                        hiddenField.setAttribute("name", "bidPrice");
                        hiddenField.setAttribute("value",each_listing[i][index][0]["bidPrice"]); // some error with each_listing[i]["latest_bid"] - talk to HJ if u have errors with any
                        fm_go_details.appendChild(hiddenField);
                    }
                }

                var go_details_button=document.createElement("input");
                go_details_button.setAttribute("type","submit");
                go_details_button.setAttribute("value","Details");
                fm_go_details.appendChild(go_details_button);

                cell_action.appendChild(fm_go_details);
                
            }
            </script>


        <?php     
            }
        } else {
            echo "no result found cause g_rec is empty";
        }
        ?>

       

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

