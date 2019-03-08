<?php
session_start();

include '../header.php';

//fetch all the active listing related to this seller
//$_SESSION["userID"]=1;

/*
if ($_POST["search_box"] === "") {
    // change criteria 
    // pass in as an argument
}

*/ 
unset($_SESSION["product_search_criteria"]);
$_SESSION["product_search_criteria"]=["all",""]; // change this criteria when query is more personalised

include 'fetchactivelisting.php';

$count=count($_SESSION["all_active_listings"]);
if ($count==0){
    echo "no result found";
}

?>


<html>
<head>

<style>
th {
    vertical-align: top;
    padding: 20px 20px;
}
</style>

<h1>Seller's Shop</h1>
</head>

<body>


<p id="t"></p>
<button onclick="reset_filter()">Reset all filters</button>

<!-- create table header -->
<table id=active_listing_table width="device-width,initial-scale=1">
    <tr>
        <th>Image</th>
        <th>Product Name</th>
        <th>Product Details
        <br><br>
        <input type="text" id="search_details" onkeyup="search_filter('search_details',2,'text')" placeholder="type something" title="Type something">
        </th>
        <th>Start Price (£)
        <br><br>
        <input type="number" id="search_s_price" onkeyup="search_filter('search_s_price',3,'price')" placeholder="higher than..." title="Type something">
        </th>
        <th>Latest Bid (£)
        <br><br>
        <input type="number" id="search_lb_price" onkeyup="search_filter('search_lb_price',4,'price')" placeholder="higher than..." title="Type something">
        </th>
        <th>Listing starts on
        <br><br>
        <input type="date" id="search_startdate" onchange="search_filter('search_startdate',5,'startdate')" >
        <br><span style="font-size:12px">(on/after)</span>
        </th>
        <th>Listing ends on
        <br><br>
        <input type="date" id="search_enddate" onchange="search_filter('search_enddate',6,'enddate')">
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
    fm_go_details.setAttribute("action","buyer_item.php");


    for (var index in each_listing[i]){
        var hiddenField=document.createElement("input");
        hiddenField.setAttribute("type","hidden");
        hiddenField.setAttribute("name",index);
        hiddenField.setAttribute("value",each_listing[i][index]);

        fm_go_details.appendChild(hiddenField);
    }

    var go_details_button=document.createElement("input");
    go_details_button.setAttribute("type","submit");
    go_details_button.setAttribute("value","Details");
    fm_go_details.appendChild(go_details_button);

    cell_action.appendChild(fm_go_details);
    
}

function warning_msg(){
    return confirm("confirm remove this listing?");
    }

//remove all filtering
function reset_filter(){
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        tr[i].style.display="";
    }
}

//do filtering
function search_filter(inputid,col,type) {
    var filter, tr, td, i, txtValue;
    filter = document.getElementById(inputid).value;
    tr = table.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {

        td = tr[i].getElementsByTagName("td")[col];

            if (td) {

                    switch (type){
                        case 'text':
                                txtValue = td.innerText.toLowerCase();
                                var filter_l = (filter.toLowerCase()).split(" ");

                                for (j=0;j<filter_l.length;j++){
                                    if (txtValue.indexOf(filter_l[j]) > -1) {
                                    tr[i].style.display = "";
                                } else {
                                    tr[i].style.display = "none";
                                }
                                }
                                
                                 break;

                        case 'price':
                            
                                txtValue = parseFloat(td.innerText);
                                filter = parseFloat(filter);

                                if (txtValue>=filter) {
                                    tr[i].style.display = "";
                                } else {
                                    tr[i].style.display = "none";
                                }
                                break;
                        
                        case 'startdate':
                            
                                txtValue = new Date(td.innerText);
                                filter=new Date(filter);
                    
                                if ((txtValue>=filter)) {
                                    tr[i].style.display = "";
                                } else {
                                    tr[i].style.display = "none";
                                }
                                break;
                        
                        case 'enddate':
                            
                                txtValue = new Date(td.innerText);
                                filter=new Date(filter);
                    
                                if ((txtValue<=filter)) {
                                    tr[i].style.display = "";
                                } else {
                                    tr[i].style.display = "none";
                                }
                                break;
                    }       
            } 
    }
}


</script>



</body>

</html>

<?php

include '../footer.php';

?>
