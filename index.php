<?php 
session_start(); 

include "header.php";
include "activity/probability_diff_interface.php";

$userID = $_SESSION["userID"];
$n = 10; 

$list_of_archive = get_archive(); // get list of items to predict what will $userID rate these items for
$list_of_bought_archive = get_archive($userID);
$suggestions = [];
foreach ($list_of_archive as $item) {
    $value = get_personalised_recommendations($userID, $item, $n); 
    $suggestions[$item] = $value;
}
arsort($suggestions);
$v_suggestions = array_keys($suggestions);
$p_new_suggestions = array_values(array_diff($v_suggestions, $list_of_bought_archive));
$p_buy_again_suggestions = array_values(array_intersect($v_suggestions, $list_of_bought_archive));
// get products to be displayed

unset($_SESSION["product_search_criteria"]);
$_SESSION["product_search_criteria"]=["productID", $p_new_suggestions];

include 'activity/fetchactivelisting.php';

$count=count($_SESSION["all_active_listings"]);
if ($count==0){
    echo "no result found";
}


?>



<!DOCTYPE html>
<html>   
<head>
<style>
input {
    margin:auto;
    display:block;
    text-align: center;
    width: 50%;
}
#aligncentre{
    text-align: center;
    font-size:14px;
    color:blue;
}
th {
    vertical-align: top;
    padding: 20px 20px;
}
</style>

</head>

    <body>
        <h1>Team 10 EBAY SITE</h1>
        <h2>Home Page</h2>
        <div>
            <form action="/activity/buyershop.php" method="post">
                <input id="search_box" name="search_box" type="text" placeholder="Type anything!! to start searching for products">
                <input type="submit" value="Search"> 
            </form>
        </div>

        <h2> Personalised Recommendations NEW </h2>
        <p id="aligncentre">Here are some products you might be interested based on what you've bought with us and your ratings:)</p>
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

        

        <h2> Personalised Recommendations BUY AGAIN </h2>
        <p id="aligncentre">Here are some products you might be interested to buy again:)</p>
        <?php
        // get products to be displayed

        unset($_SESSION["product_search_criteria"]);
        $_SESSION["product_search_criteria"]=["productID", $p_buy_again_suggestions];

        include 'activity/fetchactivelisting.php';

        $count=count($_SESSION["all_active_listings"]);
        if ($count==0){
            echo "no result found";
        }
        ?> 

        <p id="t2"></p>
        <!-- create table header -->
        <table id=active_listing_table2 width="device-width,initial-scale=1">
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
        document.getElementById("t2").innerHTML = "No. of active listings: "+count;

        //copy the php array into javascript array
        var each_listing=<?php echo json_encode($_SESSION['all_active_listings'],JSON_PRETTY_PRINT)?>;

        <?php unset($_SESSION["all_active_listings"]);?>;

        var table=document.getElementById("active_listing_table2");
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


        <br> 
        <br>
        <p>to be removed

            <button onclick="window.location.href = 'https://ebaydatabasegithub.azurewebsites.net/';">azure homepage</button>
            <a href="database.php" title="placeholder mouseover text">
                database.php
            </a> 
            <br>
            <a>print out all session variables: </a><br><br>
            <?php 

            // print_r($_SESSION);
            
            foreach ($_SESSION as $key => $val){
                print_r("session variable name: ".$key."<br>");  
                print_r("session variable value: <br>"); 

                if (is_array($val)){
                    foreach ($val as $v){
                        print_r($v);
                        echo "<br>";
                    }
                } else{
                    print_r($val."<br>");
                }
                print_r("<br>");
                
            }
            

            ?>

    
        </p>
    </body>

    <!-- <footer>
        <button type="button">About Us</button>
        <button type="button">Contact Us</button>
    </footer> -->
</html>

<?php
include "footer.php";
?>
