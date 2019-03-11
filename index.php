<?php 
session_start(); 

include "header.php";
?>



<!DOCTYPE html>
<html>   
<head>
<style>
input {
    margin:auto;
    display:block;
    text-align: center;
    width: 50%;
}
</style>

</head>

    <body>
        <h1>Team 10 EBAY SITE</h1>
        <h2>Home Page</h2>
        <div>
            <form action="/activity/buyershop.php" method="post">
                <input id="search_box" name="search_box" type="text" placeholder="Search... Whatever you write here will redirect to a SELECT ALL SQL statement">
                <input type="submit" value="Search"> 
            </form>
        </div>
        <p>to be removed

            <button onclick="window.location.href = 'https://ebaydatabasegithub.azurewebsites.net/';">azure homepage</button>
           
            <a href="database.php" title="placeholder mouseover text">
                database.php
            </a> 
            <br>
            <a>print out all session variables: </a><br><br>
            <?php 

            // print_r($_SESSION);
            
            foreach ($_SESSION as $key => $value){
                print_r("session variable name: ".$key."<br>");  
                print_r("session variable value: <br>"); 

                if (is_array($value)){
                    foreach ($value as $v){
                        print_r($v."<br>");
                    }
                } else{
                    print_r($value."<br>");
                }
                print_r("<br>");
                
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
