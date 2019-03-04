<?php
session_start();

//fetch all the archive listing related to this seller
$_SESSION["userID"]=1;

unset($_SESSION["archive_search_criteria"]);
$_SESSION["archive_search_criteria"]="sellerID";

include 'fetcharchive.php';

$count=count($_SESSION["all_archive_listings"]);
if ($count==0){
    echo "no result found";
}

?>

<html>
<head>
</head>

<body>
<p>My Selling History</p>
<p id="t"></p>


<!-- create table header -->
<table id=archive_listing_table width="device-width,initial-scale=1">
    <tr>
        <th>Image</th>
        <th>Product Details</th>
        <th>Deal Price (£)</th>
        <th>Deal Date</th>
        <th>Buyer</th>
    </tr>
</table>

<script>
var count="<?php echo $count?>";
document.getElementById("t").innerHTML = "No. of archive listings: "+count;

//copy the php array into javascript array
var each_listing=<?php echo json_encode($_SESSION['all_archive_listings'],JSON_PRETTY_PRINT)?>;

<?php unset($_SESSION["all_archive_listings"]);?>;

for (i=0;i<count;i++){
    //for each archive listing, create a row in the table.
    var table=document.getElementById("archive_listing_table");
    var row=table.insertRow(-1);
    var cell_image=row.insertCell(0);
    var cell_details=row.insertCell(1);
    var cell_dealprice=row.insertCell(2);
    var cell_dealdate=row.insertCell(3);
    var cell_buyer=row.insertCell(4);
 
    //insert image iin the 1st column (image)
    cell_image.style.textAlign="center";
    cell_image.innerHTML="(image)";

    //insert product details into the 2nd column (Details)
    cell_details.style.textAlign="center";

    cell_details.innerHTML=each_listing[i]["product_description"]+
                                "<br> deal price: "+each_listing[i]["dealprice"]+
                                "<br>quantity: "+each_listing[i]["quantity"]+
                                "<br>category: "+each_listing[i]["categoryname"]+
                                "<br>condition: "+each_listing[i]["conditionname"]+"<br><br>";

    //insert buyer into the 3rd column (deal price)
    cell_dealprice.style.textAlign="center";
    cell_dealprice.innerHTML=each_listing[i]["dealprice"];
    
    
    //insert deal date into the 4th column (deal date)
    cell_dealdate.style.textAlign="center";
    cell_dealdate.innerHTML=each_listing[i]["dealdate"];

    //insert buyer into the 5th column (buyer)
    cell_buyer.style.textAlign="center";

    //insert function/query to get buyername

    cell_buyer.innerHTML=each_listing[i]["buyerID"];

    
}

function warning_msg(){
    return confirm("confirm remove this listing?");
    }

</script>



</body>

</html>
