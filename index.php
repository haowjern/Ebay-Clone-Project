<?php 
session_start(); 

include "header.php";
?>



<!DOCTYPE html>
<html>   

    <body>
        <h1>Team 10 EBAY SITE</h1>
        <h2>Home Page</h2>
        <input type="text" placeholder="Search... Whatever you write here will redirect to a SELECT ALL SQL statement"
            formmethod="post" formaction="search.php">
        <p>to be removed

            <button onclick="window.location.href = 'https://ebaydatabasegithub.azurewebsites.net/';">azure homepage</button>
           
            <a href="database.php" title="placeholder mouseover text">
                database.php
            </a> 
            <br>
            <a>print out all session variables: </a><br><br>
            <?php 
            
            foreach ($_SESSION as $key => $value){
                print_r($key."<br>");   
                print_r($value."<br>");
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
