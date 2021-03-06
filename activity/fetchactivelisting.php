<?php 
if(session_id() == ''){
    //session has not started
    session_start();
}

include_once "bid_product_interface.php";

//obtain all the active listing items of the seller

//call getc&c.php to store all category and condition indices and names in session variables
if (!isset($_SESSION["category_all"])||!isset($_SESSION["condition_all"])){
    include "getc&c.php";}

    if (file_exists('../database.php')){
        include '../database.php';
    } else {
        include './database.php';
    }

    include_once "photos_interface.php"; // this is to get the get photo function 


    //$_session[product search criteria]=["category",1  ]

    unset($_SESSION["all_active_listings"]);

    if (isset($_SESSION["product_search_criteria"])){
        $criteria=$_SESSION["product_search_criteria"][0];
        $value=$_SESSION["product_search_criteria"][1];
        if (!is_array($value)) {
            $value=mysqli_real_escape_string($connection,$value);
        }

    // write the query according to search criteria
        if ($criteria=="sellerID"){

            $sellerID=mysqli_real_escape_string($connection,$_SESSION['userID']);

            $sql="SELECT * FROM Product WHERE sellerID='$sellerID'";

        } elseif ($criteria=="category"){

            $sql="SELECT * FROM Product WHERE categoryID='$value'";

        } elseif ($criteria=="condition"){

            $sql="SELECT * FROM Product WHERE conditionID='$value'";

        } elseif ($criteria=="c&c"){

            $condition=mysqli_real_escape_string($connection,$_SESSION["product_search_criteria"][2]);

            $sql="SELECT * FROM Product WHERE categoryID=$value AND conditionID=$condition";

        } elseif ($criteria=="auctionable"){

            $sql="SELECT * FROM Product WHERE auctionable=1";

        } elseif ($criteria=="all"){

            $sql="SELECT * FROM Product";
        
        } elseif ($criteria=="keyword"){
            $sql = "SELECT * FROM Product as p
                    WHERE p.product_name LIKE '%$value%'
                    OR p.product_description LIKE '%$value%'
                    OR p.categoryID = (
                        SELECT c.categoryID FROM Category as c
                        WHERE c.categoryname LIKE '%$value%' 
                    )
                    OR p.conditionID = (
                        SELECT con.conditionID FROM Conditionindex as con
                        WHERE con.conditionID LIKE '%$value$'
                    )";

        } elseif ($criteria=="productID") {
            $values = join("','" , $value);
            $sql = "SELECT * FROM Product WHERE productID IN ('$values')";
        }

        $result=$connection->query($sql);

        if ($result->num_rows>0){
            
            $_SESSION["all_active_listings"]=array();
        
            //output data of each row in table
            while($row=$result->fetch_assoc()){
                $v=array();

                foreach ($row as $key => $value){
                    $v[$key]=$value; 
                    
                    // getting photos, and associating all photos with one key
                    if ($key == "productID") {
                        $v["photos"] = get_photo($value); // $v["photos"] is a LIST of associative arrays with keys "productID", "photoID", "file_path"
                        $v["latest_bid"] = get_bidEvent("latest", $value); 
                    }
                }

                //obtain the category and condition from sessionv variables
                $v["categoryname"]=$_SESSION["category_all"][$v["categoryID"]];
                $v["conditionname"]=$_SESSION["condition_all"][$v["conditionID"]];

                unset($v["categoryID"]);
                unset($v["conditionID"]);
                
                array_push($_SESSION["all_active_listings"],$v);
            }

        // print_r($_SESSION["all_active_listings"]);
        } else {
            echo "no result found";
        }
    
        $connection->close();
    }

unset($_SESSION["product_search_criteria"]);

?>
</html>
