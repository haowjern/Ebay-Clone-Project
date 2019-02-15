<!DOCTYPE html>
<html>
<head>
<h1>New Listing</h1>

</head>
<body>

<?php

// validate the input
$desErr = $priceErr=$qErr=$caErr=$conErr=$auErr=$dateErr="";
$product_description = $price = $quantity = $categoryname =$conditionname = $auctionable = $enddate = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["product_description"])) {
    $desErr = "Description is required";
  } elseif (preg_match("/DROP TABLE/i",$_POST["product_description"])){
    $desErr="product description cannot contain Drop Table";
  }
  else {
    $product_description = test_input($_POST["product_description"]);
    $desErr="";
    
  }

  if (!empty($_POST["price"])&&is_numeric($_POST["price"])||$_POST["price"]==0) {
    if ($_POST["price"]==0 || (float)$_POST["price"]>=0.0 && (float)$_POST["price"]<=10000){
    $priceErr="";
    $price=(float)$_POST["price"];
    } else {
    $priceErr="price must be between $0 and $10000";
    }
  } else {
    $priceErr="price must be between $0 and $10000";
  }


  if (empty($_POST["quantity"])||!(is_numeric($_POST["quantity"]))) {
    $qErr = "quantity must be between 1 and 10000.";
  } elseif ((integer)$_POST["quantity"]<1||(integer)$_POST["quantity"]>10000){
    $qErr="quantity must be between 1 and 10000.";
  }else {
    $quantity = test_input($_POST["quantity"]);
    $qErr="";
  }

  if (empty($_POST["categoryname"])) {
    $caErr = "category is required";
  } elseif (!in_array($_POST["categoryname"],array("Electronics","Food","Fashion","Home","Health & Beauty","Sports","Toys & Games","Art & Music","Miscellaneous"))){
    $caErr="category is wrong";
  }else {
    $categoryname = test_input($_POST["categoryname"]);
    $caErr="";
  }

  if (empty($_POST["conditionname"])) {
    $conErr = "condition is required";
  } elseif (!in_array($_POST["conditionname"],array("New","Refurbished","Used / Worn"))){
    $conErr="condition is wrong";
  }else {
    $conditionname = test_input($_POST["conditionname"]);
    $conErr="";
  }

  if (empty($_POST["auctionable"])) {
    $auErr = "Select Yes / No";
  } elseif (!in_array($_POST["auctionable"],array("Yes","No"))){
    $auErr="only Yes/No";
  }else {
    $auctionable = test_input($_POST["auctionable"]);
    $auErr="";
  }

  $today=date("Y-m-d"); 
  if (empty($_POST["date_l"])){
    if (empty($_POST["endday"]) || empty($_POST["endmonth"]) ||  $_POST["endday"]=="Day"|| $_POST["endmonth"]=="Month") {
        $dateErr = "Listing end date is required";
        }else{
        date_default_timezone_set("Europe/London");
        $enddate_str=$_POST["endday"]." ".$_POST["endmonth"]." 2019";
        $enddate=date("Y-m-d",strtotime($enddate_str));
        if ($enddate<=$today){
            $dateErr="Listing must end in the future.";
            $enddate="";
        }else{
            $dateErr="";
        }
    }
  } else{
      $enddate=htmlentities($_POST["date_l"]);
  }


    //call the create new product function
    $er=true;
    foreach ($_POST as $key=>$value){
        if (empty($value)){
        $er=false;}
        
        if ($er==true){

                include "createnwproduct.php";
                $productID="";
                $product_description="mouse";
                $price=2.5;
                $quantity=10;
                $categoryID="01";
                $conditionID="01";
                $sellerID="c1";
                $auctionable=0;
                $enddate="30/4/2019";

                $details=array("productID"=>$productID,"product_description"=>$product_description,"price"=>$price,"quantity"=>$quantity,
                "categoryID"=>$categoryID,"conditionID"=>$conditionID,"sellerID"=>$sellerID,"auctionable"=>$auctionable,"enddate"=>$enddate);

                // newproduct($details);

        header('Location: http://localhost:8888/testing2.php');
        exit();
        }
    }
}
    
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

?>

Your inputs are: <br>
description: <?php echo $product_description; ?><br>
price: <?php echo $price; ?><br>
quantity:<?php echo $quantity; ?><br>
category:<?php echo $categoryname; ?><br>
condition:<?php echo $conditionname; ?><br>
auctionable:<?php echo $auctionable; ?><br>
end date:<?php echo $enddate; ?><br>



<form id="form1" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
<p>Product Description: <br></p>
<input name="product_description" type="text" placeholders="max 150 characters" maxlength="150"
value="<?php if(isset($_POST["product_description"])){echo htmlentities($_POST["product_description"]);}?>">

<span class="error"> <?php echo $desErr;?></span><br><br>
<button type="button" >Upload photos</button>

<p>Price:<br></p>
<input name="price" type="number" placeholders="1.0" step="0.01" min="0" max="10000"
value="<?php if(isset($_POST["price"])){echo htmlentities($_POST["price"]);}?>">
<span class="error"> <?php echo $priceErr;?></span><br><br>

<p>Quantity (must be at least one):<br></p>
<input name="quantity" type="number" placeholders="1" min="1" max="10000"
value="<?php if(isset($_POST["quantity"])){echo htmlentities($_POST["quantity"]);}?>">
<span class="error"> <?php echo $qErr;?></span><br><br>


<p>Category:<br></p>
  <input type="radio" name="categoryname"id="categoryname1"
  value="Electronics"
  <?php if (isset($_POST['categoryname']) && htmlentities($_POST['categoryname']) == 'Electronics'){echo "checked";}else{echo "unchecked";}?> />Electronics<br>
  <input type="radio" name="categoryname"id="categoryname2"
  value="Food"
  <?php if (isset($_POST['categoryname']) && htmlentities($_POST['categoryname']) == 'Food'){echo "checked";}else{echo "unchecked";}?> />Food<br>
  <input type="radio" name="categoryname"id="categoryname3"
  value="Fashion"
  <?php if (isset($_POST['categoryname']) && htmlentities($_POST['categoryname']) == 'Fashion'){echo "checked";}else{echo "unchecked";}?> />Fashion<br>
  <input type="radio" name="categoryname"id="categoryname4"
  value="Home"
  <?php if (isset($_POST['categoryname']) && htmlentities($_POST['categoryname']) == 'Home'){echo "checked";}else{echo "unchecked";}?> />Home<br>
  <input type="radio" name="categoryname"id="categoryname5"
  value="Health & Beauty"
  <?php if (isset($_POST['categoryname']) && htmlentities($_POST['categoryname']) == 'Health & Beauty'){echo "checked";}else{echo "unchecked";}?> />Health & Beauty<br>
  <input type="radio" name="categoryname"id="categoryname6"
  value="Sports"
  <?php if (isset($_POST['categoryname']) && htmlentities($_POST['categoryname']) == 'Sports'){echo "checked";}else{echo "unchecked";}?> />Sports<br>
  <input type="radio" name="categoryname"id="categoryname7"
  value="Toys & Games"
  <?php if (isset($_POST['categoryname']) && htmlentities($_POST['categoryname']) == 'Toys & Games'){echo "checked";}else{echo "unchecked";}?> />Toys & Games<br>
  <input type="radio" name="categoryname"id="categoryname8"
  value="Art & Music"
  <?php if (isset($_POST['categoryname']) && htmlentities($_POST['categoryname']) == 'Art & Music'){echo "checked";}else{echo "unchecked";}?> />Art & Music<br>
  <input type="radio" name="categoryname"id="categoryname9"
  value="Miscellaneous"
  <?php if (isset($_POST['categoryname']) && htmlentities($_POST['categoryname']) == 'Miscellaneous'){echo "checked";}else{echo "unchecked";}?> />Miscellaneous<br>
  <br>

<span class="error"> <?php echo $caErr;?></span><br><br>
  
<p>Condition:<br></p>
  <input type="radio" name="conditionname"id="conditionname" value="New"
  <?php if (isset($_POST['conditionname']) && htmlentities($_POST['conditionname']) == 'New'){echo "checked";}else{echo "unchecked";}?> />New<br>
  <input type="radio" name="conditionname"id="conditionname"value="Refurbished"
  <?php if (isset($_POST['conditionname']) && htmlentities($_POST['conditionname']) == 'Refurbished'){echo "checked";}else{echo "unchecked";}?> />Refurbished<br>
  <input type="radio" name="conditionname"id="conditionname"value="Used / Worn"
  <?php if (isset($_POST['conditionname']) && htmlentities($_POST['conditionname']) == 'Used / Worn'){echo "checked";}else{echo "unchecked";}?> />Used / Worn<br>
  <br>

  <span class="error"> <?php echo $conErr;?></span><br><br>

<p>Is your product auctionable?<br></p>
  <input type="radio" name="auctionable"id="auctionable1" value="Yes" 
  <?php if (isset($_POST['auctionable']) && $_POST['auctionable'] == 'Yes'){echo "checked";}else{echo "unchecked";}?> />Yes<br>
  <input type="radio" name="auctionable"id="auctionable2"value="No"
  <?php if (isset($_POST['auctionable']) && $_POST['auctionable'] == 'No'){echo "checked";}else{echo "unchecked";}?> />No<br>
  <br>

  <span class="error"> <?php echo $auErr;?></span><br><br>


<p>Listing ends on:<br></p>
<input type="date" name="date_l" id="date_l" max="2019-12-31" value="<?php if(isset($_POST["date_l"])){echo htmlentities($_POST["date_l"]);}?>"/>

<p>If calendar cannot be shown in the field above, use below to input:<br></p>

  <select id="endday" name="endday">
    <option><?php if(isset($_POST["endday"])){echo htmlentities($_POST["endday"]);}else{echo "Day";}?></option>
</select>
<select id="endmonth" name="endmonth">
    <option><?php if(isset($_POST["endmonth"])){echo htmlentities($_POST["endmonth"]);}else{echo "Month";}?></option>
</select>

<br>


<span class="error"> <?php echo $dateErr;?></span><br><br>


<input type="submit" name="submit" value="Submit">
</form>



<script>

//create the drop-down list for end date
var selectday = document.getElementById("endday");
for (var i = 1; i < 32; i++) {
    var eld = document.createElement("option");
    eld.textContent = i;
    eld.value = i;
    selectday.appendChild(eld);
}

var selectmonth = document.getElementById("endmonth");
var opt=["January","February","March","April","May","June","July","August","September","October","November","December"];
for(var i = 0; i < opt.length; i++) {
    var elm = document.createElement("option");
    elm.textContent = opt[i];
    elm.value = opt[i];
    selectmonth.appendChild(elm);}

var today=new Date();
var tomorrow=new Date();
tomorrow.setDate(today.getDate()+1);
date_l.min = tomorrow.toISOString().split("T")[0];


</script>

</body>
</html>