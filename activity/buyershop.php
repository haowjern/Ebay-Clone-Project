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

if(isset($_POST["search_box"])){

    $search_words = explode(" ", $_POST["search_box"]);

        $set_words = [];
        foreach ($search_words as $word) {
            if (!empty($word)) {
                $set_words[$word]=1; // get unique set of words - no duplication
            }
        }

        $search_unique_words = array_keys($set_words);

};



//auction event search takes precedent 
if ($_POST["submit"]=="Search items on bid only"){
    $_SESSION["product_search_criteria"]=["auctionable",""]; 
    include "fetchactivelisting.php";
}

elseif (isset($_POST["search_box"])&&!empty($search_unique_words)) {
    // FIND ALL RELEVANT LISTINGS 
    $s_all_active_listings = []; // array has pID as its keys to identify unique items that has been searched

    foreach ($search_unique_words as $word) {
        // echo "<br>word: ".$word;
        $_SESSION["product_search_criteria"]=["keyword",$word]; 
        include 'fetchactivelisting.php';
        
        foreach ($_SESSION["all_active_listings"] as $listing) {
            $pID = $listing["productID"];

            if (array_key_exists($pID, $s_all_active_listings)) { 
                $s_all_active_listings[$pID]["search_count"] += 1;
            } else {
                $listing["search_count"] = 1; // initialise number of times searched (the higher the better for our search)
                $s_all_active_listings[$pID] = $listing; // force unique products only
            } 
        }
    }

    // SORT LISTING BY SEARCH_COUNT DESCENDING (to show the most relevant results first)
    $s_all_active_listings = array_values($s_all_active_listings);
    $_SESSION['all_active_listings'] = []; 

    foreach ($s_all_active_listings as $key=>$val) { 
        $key_count[$key] = $val["search_count"];
    }
    arsort($key_count); // sort by highest count 
    
    // RENAME KEYS TO 0, 1, 2, 3 ... 
    foreach ($key_count as $key=>$val) {
        array_push($_SESSION['all_active_listings'], $s_all_active_listings[$key]); 
    }
    
}
//search by category/condition

elseif (isset($_POST["conditionlist"])||isset($_POST["categorylist"])){

// elseif (isset($_POST["conditionlist"])||isset($_POST["categorylist"])){

    $checked=in_array($_POST["categorylist"],array("Category","Electronics","Food","Fashion","Home","Health & Beauty","Sports","Toys & Games","Art & Music","Miscellaneous"));
    $checked=in_array($_POST["conditionlist"],array("Condition","New","Refurbished","Used / Worn"));

    if ($checked){

            if (($_POST["categorylist"]!="Category") && ($_POST["conditionlist"]!="Condition")){

                //search based on category and condition

                $category=array_search($_POST["categorylist"],$_SESSION["category_all"]);
                $condition=array_search($_POST["conditionlist"],$_SESSION["condition_all"]);
                echo $condition;
                $_SESSION["product_search_criteria"]=["c&c",$category,$condition];

                include 'fetchactivelisting.php';

            }elseif (($_POST["categorylist"]!="Category") && ($_POST["conditionlist"]=="Condition")){

                //search based on category

                $category=array_search($_POST["categorylist"],$_SESSION["category_all"]);
                $_SESSION["product_search_criteria"]=["category",$category];

                include 'fetchactivelisting.php';

            }elseif (($_POST["categorylist"]=="Category") && ($_POST["conditionlist"]!="Condition")){

                //search based on condition

                $condition=array_search($_POST["conditionlist"],$_SESSION["condition_all"]);
                $_SESSION["product_search_criteria"]=["condition",$condition];
   
                include 'fetchactivelisting.php';
            }

        $_SESSION["product_search_criteria"]=["all",""];
        include 'fetchactivelisting.php';
            
    }
}

else {
    $_SESSION["product_search_criteria"]=["all",""];
    include 'fetchactivelisting.php';
}

$count=count($_SESSION["all_active_listings"]);
if ($count==0){
    echo "no result found";
}

unset($_SESSION["product_search_criteria"]);
?>


<html>
<head>

<style>

table{
    /* overflow: auto;
    white-space: nowrap; */
}
th {
    vertical-align: top;
    padding: 20px 20px;

}



input,form {
    margin:auto;
    display:block;
    text-align: center;
    width: 50%;
}

}

</style>

<h1>Go Shopping!!</h1>
</head>

<body>

<!-- search auction only-->

<div>
            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
            <input type="submit" name="submit" value="Search items on bid only"> 
</div>
<br>

<!-- search based on keywords -->
<div>
            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
                <input id="search_box" name="search_box" type="text" placeholder="Or type anything!! to start searching for products">
                <input type="submit" value="Search"> 
            </form>
</div>
<br>

<!-- search based on category -->

<div>
            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
            <span>Or search by category/condition<br></span>
            <select id="categorylist" name="categorylist">
            <option><?php if(isset($_POST["categorylist"])){echo $_POST["categorylist"];}else{echo "Category";}?></option>
            </select>

            <select id="conditionlist" name="conditionlist">
            <option><?php if(isset($_POST["conditionlist"])){echo $_POST["conditionlist"];}else{echo "Condition";}?></option>
            </select>
            <input type="submit" value="Search"> 
</div>



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
        <th>Action</th>
    </tr>   

</table>

<script>

//create the drop-down list for category

var category_all=<?php echo json_encode($_SESSION['category_all'],JSON_PRETTY_PRINT)?>;
category_all["0"]="Category";
var categorylist = document.getElementById("categorylist");
for (var i = 0 ; i < Object.keys(category_all).length; i++) {
    var eld = document.createElement("option");
    eld.textContent = category_all[i];
    eld.value = category_all[i];
    categorylist.appendChild(eld);
}

var condition_all=<?php echo json_encode($_SESSION['condition_all'],JSON_PRETTY_PRINT)?>;
condition_all["0"]="Condition";
var conditionlist = document.getElementById("conditionlist");
for (var i = 0 ; i < Object.keys(condition_all).length; i++) {
    var eld = document.createElement("option");
    eld.textContent = condition_all[i];
    eld.value = condition_all[i];
    conditionlist.appendChild(eld);
}

var count="<?php echo $count?>";
document.getElementById("t").innerHTML = "No. of items that fit your search: "+count;

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
    var cell_action=row.insertCell(7);

 
    //insert image iin the 1st column (image)
    cell_image.style.textAlign="center";
    if(each_listing[i]['photos'][0]!=null){
                cell_image.innerHTML=`<img src=${each_listing[i]['photos'][0]['file_path']} alt='Image' style=max-height:100%; max-width:100%>`
                cell_image.height=100; // scale size
                cell_image.width=100; // scale size
            
            } else{
                cell_image.innerHTML="No image";
            }

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
    cell_enddate.innerHTML=each_listing[i]["enddate"]+"<br>"+each_listing[i]["endtime"];



    //insert forms with buttons in 8th column (action)
    cell_action.style.textAlign="center";

    //create the form to see more details of item
    var fm_go_details=document.createElement('form');
    //name the form with productID
    fm_go_details.setAttribute("name","form_go_details"+each_listing[i]["productID"]);
    fm_go_details.setAttribute("method","post");
    fm_go_details.setAttribute("action","buyer_item.php");


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

    if (each_listing[i]["auctionable"]==="0"){
        go_details_button.setAttribute("value","Details");
    } else {
        go_details_button.setAttribute("value","Go to auction");
    }
    go_details_button.style.width = '100px';
    fm_go_details.appendChild(go_details_button);

    cell_action.appendChild(fm_go_details);
    
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
