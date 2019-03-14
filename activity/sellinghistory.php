<?php
session_start();

include "../header.php";

//fetch all the archive listing related to this seller
// $_SESSION["userID"]=1;

unset($_SESSION["archive_search_criteria"]);
$_SESSION["archive_search_criteria"]="sellerID";

include 'fetcharchive.php';

$count=count($_SESSION["all_archive_listings"]);
if ($count==0){
    echo "no result found";
}

//check the seller comments which have been submitted by form
$seller_comment_err=$seller_comment="";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

//validate seller comment before database query

    $archiveID=$_POST["archiveID"];
    $seller_comment=$_POST["seller_comment"];

    if (strlen($_POST["seller_comment"])>150){
        $seller_comment_err="comment cannot exceed 150 characters.";
        $seller_comment="";
        $checked="invalidated";
    } else{
        //trim all sensitive html special characters
        $seller_comment=$_POST["seller_comment"];
        $seller_comment = trim($seller_comment);
        $seller_comment = stripslashes($seller_comment);
        $seller_comment = htmlspecialchars($seller_comment);
        $checked="validated";
    }

    if ($checked=="validated"){
        $_SESSION["aftersale_seller"]=[$archiveID,$seller_comment];
        include "aftersale.php";
    }

    



}   

?>

<html>
<head>
<h1>My Selling History</h1>

<style>
th {
    vertical-align: top;
    padding: 20px 20px;
}
</style>

</head>

<body>

<p id="t"></p>
<p><font color="red"><?php echo $seller_comment_err?></p>


<!-- create table header -->
<table id=archive_listing_table width="device-width,initial-scale=1">
    <tr>
        <th>Product Name</th>
        <th>Product Details</th>
        <th>Deal Price (£)</th>
        <th>Deal Date</th>
        <th>Buyer</th>
        <th>Buyer's Ratings (1-10)</th>
        <th>Buyer's comment</th>
        <th>My comment</th>
        

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
    var cell_productname=row.insertCell(0);
    var cell_details=row.insertCell(1);
    var cell_dealprice=row.insertCell(2);
    var cell_dealdate=row.insertCell(3);
    var cell_buyer=row.insertCell(4);
    var cell_ratings=row.insertCell(5);
    var cell_buyer_comment=row.insertCell(6);
    var cell_seller_comment=row.insertCell(7);


    //insert product name into the 1st column (product name)
    cell_productname.style.textAlign="center";

    cell_productname.innerHTML=each_listing[i]["product_name"];

    //insert product details into the 2nd column (Details)
    cell_details.style.textAlign="center";

    cell_details.innerHTML=each_listing[i]["product_description"]+
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
    cell_buyer.innerHTML=each_listing[i]["username"];

    //insert ratings into the 6th column (ratings)
    cell_ratings.style.textAlign="center";
    cell_ratings.innerHTML=each_listing[i]["ratings"];

    //insert buyer comment into the 7th column (buyer comment)
    cell_buyer_comment.style.textAlign="center";
    cell_buyer_comment.innerHTML=each_listing[i]["buyer_comment"];

    //insert seller comment into the 8th column (seller comment)
    cell_seller_comment.style.textAlign="center";

    //create the form to edit item
    var fm_edit=document.createElement('form');
    //name the form with productID
    fm_edit.setAttribute("name","form_edit"+each_listing[i]["archiveID"]);
    fm_edit.setAttribute("method","post");
    fm_edit.setAttribute("action","<?php echo htmlentities($_SERVER['PHP_SELF']); ?>");

    //add the text field to edit comment
    var seller_comment_field=document.createElement("input");
        seller_comment_field.setAttribute("type","text");
        seller_comment_field.setAttribute("name","seller_comment");

        
        seller_comment_field.setAttribute("value",each_listing[i]["seller_comment"]);

        if (each_listing[i]["archiveID"]=="<?php echo $_POST["archiveID"]?>"){
            var seller_comment_updated="<?php echo $_POST['seller_comment']?>";
            if (seller_comment_updated!=""){
            seller_comment_field.setAttribute("value","<?php echo htmlentities($_POST["seller_comment"])?>");
            }
        }
        
        seller_comment_field.setAttribute("maxlength","150");
        seller_comment_field.setAttribute("size","50");

        fm_edit.appendChild(seller_comment_field);
        fm_edit.appendChild(document.createElement("br"));
    

    //add the hidden field: archiveID
    var hiddenField_archiveID=document.createElement("input");
        hiddenField_archiveID.setAttribute("type","hidden");
        hiddenField_archiveID.setAttribute("name","archiveID");
        hiddenField_archiveID.setAttribute("value",each_listing[i]["archiveID"]);

        fm_edit.appendChild(hiddenField_archiveID);

    var edit_button=document.createElement("input");
    edit_button.setAttribute("type","submit");
    edit_button.setAttribute("value","submit changes");
    //alert pop up prior to deletion
    edit_button.setAttribute("onclick","return warning_msg();");
    fm_edit.appendChild(edit_button);
    cell_seller_comment.appendChild(fm_edit);

    
}

function warning_msg(){
    return confirm("confirm submitting the changes?");
    }


</script>


</body>

</html>

<?php

include "../footer.php";
?>
