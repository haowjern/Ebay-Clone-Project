<?php session_start();?>
<head>
<style>
button {
    font-size:10px;
    padding: 10px 20px;
    margin: 15px;
    justify-content: center; 
}
h1,h2 {
    text-align: center;
}

table {
    width:100%;
}

body{
    margin:auto;
    width:90%;
}

#general,#seller_menu, #buyer_menu {
float: left;
}


</style>
    <div id="general">
    <button onclick="window.location.href = '/signout.php';" type="button" id="sign_in"></button>
    <button onclick="window.location.href = '/index.php';"type="button" formaction=index.php>Home</button>
    <button onclick="window.location.href = '/yourProfile.php';" type="button">My Profile</button>
    </div>
    <div id="seller_menu">
    I'm selling:
    <button onclick="window.location.href = '/activity/sellershop.php';" type="button">My Shop</button>
    <button onclick="window.location.href = '/activity/sellinghistory.php';" type="button">Selling History</button>
    </div>
    <div id="buyer_menu">
    I'm buying:
    <button onclick="window.location.href = '/show_my_watchlist.php';" type="button">My Watchlist</button>
    <button onclick="window.location.href = '/activity/purchasehistory.php';"type="button">Purchase History</button>
    <button onclick="window.location.href = '/activity/buyer_item.php';" type="button">Buyer Item </button>
    <button type="button">Cart</button>
    </div>
    <br><br>
    <div>
    <p>You are signed in as: <?php echo $_SESSION["username"] ;?></p>
    </div>

<script>
if (<?php echo isset($_SESSION["userID"]);?>){
    document.getElementById("sign_in").innerHTML="Sign Out";
} else{
    document.getElementById("sign_in").innerHTML="Sign In";
}

</script>


</head>