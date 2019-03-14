<?php
session_start();

include "../header.php";


//fetch all the archive listing related to this buyer
// $_SESSION["userID"]=1;

unset($_SESSION["archive_search_criteria"]);
$_SESSION["archive_search_criteria"]="buyerID";

include 'fetcharchive.php';

$count=count($_SESSION["all_archive_listings"]);
if ($count==0){
    echo "no result found";
}


//check the seller comments which have been submitted by form
$buyer_comment_err=$buyer_comment=$ratings_err=$ratings=$checked="";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

//validate archiveID and productID

    $archiveID=$_POST["archiveID"];
    $productID=$_POST["productID"];

    if (!is_numeric($archiveID)||!is_numeric($productID)){
        $checked="invalidated";
    }else{
        $checked="validated";
    }

    $ratings=$_POST["ratings"];

//validate buyer comment before database query

    $buyer_comment=$_POST["buyer_comment"];

    if (strlen($_POST["buyer_comment"])>150){
        $buyer_comment_err="comment cannot exceed 150 characters.";
        $buyer_comment="";
        $checked="invalidated";
    } else{
        //trim all sensitive html special characters
        $buyer_comment=$_POST["buyer_comment"];
        $buyer_comment = trim($buyer_comment);
        $buyer_comment = stripslashes($buyer_comment);
        $buyer_comment = htmlspecialchars($buyer_comment);
        $buyer_comment_err="";
        $checked="validated";
    }

//validate ratings before database query

        if (!(is_numeric($_POST["ratings"]))) {
            $ratings_err = "ratings must be between 1 and 10.";
            $ratings="";
            $checked="invalidated";
          } elseif ((integer)$_POST["ratings"]<1||(integer)$_POST["ratings"]>10){
            $ratings_err="ratings must be between 1 and 10.";
            $ratings="";
            $checked="invalidated";
          }else {
            $ratings = (integer)$_POST["ratings"];
            $ratings_err="";
            $checked="validated";
          }

    if ($checked=="validated"){
        $_SESSION["aftersale_buyer"]=[$archiveID,$buyer_comment,$ratings,$productID,$_SESSION["userID"]];
        include "aftersale.php";
    }
}

?>

<html>
<head>
<h1>My Purchase History</h1>

<style>
th {
    vertical-align: top;
    padding: 20px 20px;
}
</style>

</head>

<body>

<p id="t"></p>
<p><font color="red"><?php echo $buyer_comment_err."<br>".$ratings_err?></p>


<!-- create table header -->
<table id=archive_listing_table width="device-width,initial-scale=1">
    <tr>
        <th>Product Name</th>
        <th>Product Details</th>
        <th>Deal Price (£)</th>
        <th>Deal Date</th>
        <th>Seller</th>
        <th>My Ratings and comment</th>
        <th>Seller's comment</th>
        

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
    var cell_seller=row.insertCell(4);
    var cell_buyer_ratings_comment=row.insertCell(5);
    var cell_seller_comment=row.insertCell(6);


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

    //insert seller into the 5th column (seller)
    cell_seller.style.textAlign="center";
    cell_seller.innerHTML=each_listing[i]["username"];

    //insert buyer comment and ratings into the 6th column (buyer comment and ratings)
    cell_buyer_ratings_comment.style.textAlign="left";

    //create the form to edit item
    var fm_edit=document.createElement('form');
    //name the form with archiveID
    fm_edit.setAttribute("name","form_edit"+each_listing[i]["archiveID"]);
    fm_edit.setAttribute("method","post");
    fm_edit.setAttribute("action","<?php echo htmlentities($_SERVER['PHP_SELF']); ?>");

    fm_edit.appendChild(document.createTextNode("ratings (1 to 10): "));
    fm_edit.appendChild(document.createElement("br"));

    //add the number field to edit ratings
    var ratings_field=document.createElement("input");
        ratings_field.setAttribute("type","number");
        ratings_field.setAttribute("name","ratings");
        ratings_field.setAttribute("value",each_listing[i]["ratings"]);

        if (each_listing[i]["archiveID"]=="<?php echo $_POST["archiveID"]?>"){
            var ratings_updated="<?php echo $_POST["ratings"]?>";
            if (ratings_updated!=""){
            ratings_field.setAttribute("value","<?php echo htmlentities($_POST["ratings"])?>");
            }
        }

        


    //add the hidden field: archiveID and productID
    var hiddenField_archiveID=document.createElement("input");
        hiddenField_archiveID.setAttribute("type","hidden");
        hiddenField_archiveID.setAttribute("name","archiveID");
        hiddenField_archiveID.setAttribute("value",each_listing[i]["archiveID"]);

        fm_edit.appendChild(hiddenField_archiveID);

    var hiddenField_productID=document.createElement("input");
        hiddenField_productID.setAttribute("type","hidden");
        hiddenField_productID.setAttribute("name","productID");
        hiddenField_productID.setAttribute("value",each_listing[i]["productID"]);

        fm_edit.appendChild(hiddenField_productID);

    var edit_button=document.createElement("input");
    edit_button.setAttribute("type","submit");
    edit_button.setAttribute("value","submit changes");
    //alert pop up prior to deletion
    edit_button.setAttribute("onclick","return warning_msg();");
    fm_edit.appendChild(document.createElement("br"));
    fm_edit.appendChild(edit_button);
    cell_buyer_ratings_comment.appendChild(fm_edit);


    //insert seller comment into the 8th column (seller comment)
    cell_seller_comment.style.textAlign="center";
    cell_seller_comment.innerHTML=each_listing[i]["seller_comment"];

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
