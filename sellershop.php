<?php
session_start();

$_SESSION["userID"]=1;
//fetch all the active listing related to this seller
include 'fetchactivelisting.php';
$count=count($_SESSION["all_active_listings"]);
if ($count==0){
    echo "no result found";
}

?>

<html>
<head>
</head>

<body>


<p>My Shop</p>
<p id="t"></p>

<!-- create table header -->
<table id=active_listing_table width="1000">
    <tr>
        <th>Image</th>
        <th>Product details</th>
        <th>Listing ends on: </th>
        <th>Auctionable?</th>
        <th>Action</th>
        <th>listing created on</th>
    </tr>
</table>

<script>
var count="<?php echo $count?>";
document.getElementById("t").innerHTML = "No. of active listings: "+count;

for (i=0;i<count;i++){
    //for each active listing, create a row in the table.
    var table=document.getElementById("active_listing_table");
    var row=table.insertRow(-1);
    var cell_image=row.insertCell(0);
    var cell_details=row.insertCell(1);
    var cell_enddate=row.insertCell(2);
    var cell_auctionable=row.insertCell(3);
    var cell_action=row.insertCell(4);
 
    //insert image iin the 1st column
    cell_image.style.textAlign="center";
    cell_image.innerHTML="(image)";

    //insert product details into the 2nd column
    cell_details.style.textAlign="center";


    //copy the php array into javascript array
    var each_listing=<?php echo json_encode($_SESSION['all_active_listings'],JSON_PRETTY_PRINT)?>;


    cell_details.innerHTML=each_listing[i]["product_description"]+
                                "<br> price: "+each_listing[i]["price"]+
                                "<br>quantity: "+each_listing[i]["quantity"]+
                                "<br>category: "+each_listing[i]["categoryname"]+
                                "<br>condition: "+each_listing[i]["conditionname"]+"<br><br>";

    //insert listing enddate into the 3rd column
    cell_enddate.style.textAlign="center";
    cell_enddate.innerHTML=each_listing[i]["enddate"];

    //insert listing auction status into the 4th column
    cell_auctionable.style.textAlign="center";

    if (each_listing[i]["auctionable"]=="0"){
        each_listing[i]["auctionable"]="No";}
        else{
            each_listing[i]["auctionable"]="Yes";}

    cell_auctionable.innerHTML=each_listing[i]["auctionable"];
    

    //insert forms with buttons in 5th column
    cell_action.style.textAlign="center";

    //create the form to edit item
    var fm_edit=document.createElement('form');
    //name the form with productID
    fm_edit.setAttribute("name","form_edit"+each_listing[i]["productID"]);
    fm_edit.setAttribute("method","post");
    fm_edit.setAttribute("action","editlisting.php");


    for (var index in each_listing[i]){
        var hiddenField=document.createElement("input");
        hiddenField.setAttribute("type","hidden");
        hiddenField.setAttribute("name",index);
        hiddenField.setAttribute("value",each_listing[i][index]);

        fm_edit.appendChild(hiddenField);
    }

    var edit_button=document.createElement("input");
    edit_button.setAttribute("type","submit");
    edit_button.setAttribute("value","edit");
    fm_edit.appendChild(edit_button);

    cell_action.appendChild(fm_edit);

    //create the form to remove item
    var fm_remove=document.createElement('form');
    //name the form with productID
    fm_remove.setAttribute("name","form_remove"+each_listing[i]["productID"]);
    fm_remove.setAttribute("method","post");
    fm_remove.setAttribute("action","testing1.php");
    // fm_remove.setAttribute("onsubmit","alert('confirm delete!');return false")

    var hiddenField_remove=document.createElement("input");
        hiddenField_remove.setAttribute("type","hidden");
        hiddenField_remove.setAttribute("name","productID");
        hiddenField_remove.setAttribute("value",each_listing[i]["productID"]);

        fm_remove.appendChild(hiddenField_remove);

    var remove_button=document.createElement("input");
    remove_button.setAttribute("type","submit");
    remove_button.setAttribute("value","remove");
    remove_button.setAttribute("onclick","return warning_msg();");
    fm_remove.appendChild(remove_button);
    cell_action.appendChild(fm_remove);

    
}

function warning_msg(){
    return confirm("confirm remove this listing?");
        // var confirm_remove=confirm("Confirm removing this item?");
        // if (confirm_remove){
        //     f.submit();
        // }
    }

</script>



</body>

</html>