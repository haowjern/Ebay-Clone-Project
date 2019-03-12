<?php include "../header.php"?>
<html>
    <head>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <style>
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
                top:-20px;
                z-index:2;
                height:20px;
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
                            <th><span class="text">Buyer</span></th>
                            <th><span class="text">Bid</span></th>
                        </tr>
                    </thead>
                    <tbody id="refresh-table">
                        <?php
                            include_once("refreshable_bidtable.php"); // include first table, later use script to update
                        ?> 
                    </tbody>
                </table>
            </div>
        </div>

        <!-- refresh table every 2 seconds-->
        <script type="text/javascript">
            $(document).ready (function() {
                setInterval(function() {
                    let transfer_data = {"productID": <?php echo $productID ?>};
                    $('#refresh-table').load("refreshable_bidtable.php", transfer_data);
                    
                }, 2000);
            });
        </script>
    </body>
</html>
<?php include "../footer.php"?>