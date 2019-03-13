<?php session_start(); 
include "../header.php";
include '../database.php';
//$_SESSION['userID']=11;
?>
<html>
    <head>
        <h1>Watchlist</h1>

        <style>
        #container { 
            overflow:auto; 
        }

        .image { 
            width:150px;
            height:150px;
            float:left;
            position:relative; 
            background-size:cover
        }

        #table-wrapper {
            position:relative;
        }

        #table-scroll {
            height:150px;
            overflow:auto;  
            margin-top:20px;
        }

        #table-wrapper table {
            width:25%;
        }

        #table-wrapper table * {
            color:black;
        }

        #table-wrapper table thead th .text {
            position:absolute;   
            top:-20px;
            z-index:2;
            height:20px;
            width:35%;
        }
        
        #table-wrapper table tbody td {
            
        }
        
        </style>

    </head>
    <body>

        <div id="table-wrapper">
            <div id="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <!-- <th><span class="text">ProductName</span></th> -->
                            <th><span class="text">BuyerID</span></th>
                            <th><span class="text">ProductID</span></th>
                        </tr>
                    </thead>
                    <tbody id="watchlist-table">
                        <?php
                            include_once("watchlist_table.php"); 
                        ?> 
                    </tbody>
                </table>
            </div>
        </div>

    </body>
</html>

