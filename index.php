<?php session_start(); ?>

<!DOCTYPE html>
<html>    
<head>
        <button type="button">Sign In</button>
        <button type="button" formaction=index.php>Home</button>
        <input type="text" placeholder="Search... Whatever you write here will redirect to a SELECT ALL SQL statement"
            formmethod="post" formaction="search.php">
        <button type="button">My Shop</button>
        <button type="button">My Profile</button>
        <button type="button">Purchase History</button>
        <button type="button">Selling History</button>
        <button type="button">Carts</button>
    </head> 

    <body>
        <h1>Links:</h1>
           
        <p>
            <a> href="https://ebaydatabasegithub.azurewebsites.net/">
                    azure homepage
            </a/>
            
            <a href="buyer_item.php" title="placeholder mouseover text">
                BUYER_ITEM ,
            <a href="database.php" title="placeholder mouseover text">
                database.php ,
            </a>
            <a href="/activity/editlisting.php" title="placeholder mouseover text">
                EDIT_LISTING ,
            </a>
            <a href="/activity/sellershop.php" title="placeholder mouseover text">
                SELLER_SHOP ,
            </a>
            <a href="/activity/sellinghistory.php" title="placeholder mouseover text">
                SELLING_HISTORY ,
            </a>
            <a href="showlistings.php" title="placeholder mouseover text">
                SHOW_LISTINGS ,
            </a>

            <?php
            echo "hello world from php"
            ?>
        </p>
    </body>

    <footer>
        <button type="button">About Us</button>
        <button type="button">Contact Us</button>
    </footer>
</html>
