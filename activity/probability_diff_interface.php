<?php 

function set_popularity_diff($some_arr, $instr) {
    if (file_exists('../database.php')){
        include '../database.php';
    } else {
        include './database.php';
    }
    $productID = $some_arr["productID"]; // item that was just rated 
    $userID = $some_arr["userID"]; // user that rated the item

    if ($instr === "insert") {
        // get all of the users' rating pairs
        $sql = "SELECT DISTINCT a1.productID, a2.ratings - a1.ratings as rating_diff
                FROM archive as a1, archive as a2
                WHERE a1.buyerID = '$userID' AND a2.buyerID = '$userID' AND a2.productID='$productID'"; 
        $result = $connection->query($sql); 
        if ($result->num_rows>0) {        
            // for every one of the users' rating pairs, update popularity_diff table
            while ($row=$result->fetch_assoc()) {
                $other_productID = $row["productID"];
                $rating_diff = $row["rating_diff"]; 

                // if the pair of products are already in the popularity_diff table
                $sql = "SELECT productID1 FROM popularity_diff 
                        WHERE productID1='$productID' AND productID2='$other_productID'";
                
                $result_pair = $connection->query($sql);

                if ($result_pair->num_rows>0) {
                    // update the first row for this pair
                    $sql = "UPDATE popularity_diff SET count=count+1, sum=sum+$rating_diff
                            WHERE productID1='$productID' AND productID2='$other_productID'";
                    $result_update = $connection->query($sql);

                    // update the second row for this pair 
                    // we only want to update if the items are different 
                    // why? (I think because to only have 1 of the same pair, and also by having
                    // the same pair, we are also able to see the 'count' value which will help
                    // i think)
                    if ($productID != $other_productID) {
                        $sql = "UPDATE popularity_diff SET count=count+1, sum=sum-$rating_diff
                                WHERE productID1='$other_productID' AND productID2='$productID'";
                        $result_update = $connection->query($sql);

                        if ($result_update) {
                            echo "<br>updated 2 success"; 
                        } else {
                            echo "<br>updated 2 failed";
                            echo "<br>";
                            echo $sql;
                        }
                    }
                    
                } else {
                    echo "inserting"; 
                    // insert first row for this pair 
                    $sql = "INSERT INTO popularity_diff VALUES ('$productID', '$other_productID', 1, '$rating_diff')";
                    $result_insert = $connection->query($sql);

                    if ($result_insert) {
                        echo "<br>inserted success"; 
                    } else {
                        echo "<br>inserted failed";
                        echo "<br>";
                        echo $sql;
                    }

                    // insert second row for this pair if they are not the same 
                    if ($productID != $other_productID) {
                        $sql = "INSERT INTO popularity_diff VALUES ('$other_productID', '$productID', 1, -'$rating_diff')";
                        $result_insert = $connection->query($sql);

                        if ($result_insert) {
                            echo "<br>inserted 2 success"; 
                        } else {
                            echo "<br>inserted 2 failed";
                            echo "<br>";
                            echo $sql;
                        }
                    }
                }
            }
        } else {
            echo("None selected");
        }
    } else {
        echo("Error: Selected wrong instruction for set_popularity_diff");
    }
    $connection->close();
}

function get_general_recommendations($current_productID, $n) {
    if (file_exists('../database.php')){
        include '../database.php';
    } else {
        include './database.php';
    }
    // select 10 items that is the most popular
    // sum/count means, the better the item is compared to the rest (rating diff), the higher the sum
    // the more people bid on the same item, the higher the count 
    // sum/count acts like a normalisation
    $sql = "SELECT productID2, (sum/count) AS average
            FROM popularity_diff 
            WHERE count > 1  AND productID1 = '$current_productID'
            ORDER BY (sum/count) DESC
            LIMIT $n"; 
    $result = $connection->query($sql);
    $list = [];
    if ($result->num_rows>0) {
        while ($row=$result->fetch_assoc()) {
            $list[$row["productID2"]] = $row["average"];
        }
    } 
    return $list;
    $connection->close();
}

function get_personalised_recommendations($userID, $current_productID, $n) {//$current_productID) { //
    if (file_exists('../database.php')){
        include '../database.php';
    } else {
        include './database.php';
    }

    // $sql = "SELECT pd.productID1, sum(pd.sum + pd.count*a.ratings)/sum(pd.count) as avg_rating
    //         FROM archive as a, popularity_diff as pd WHERE a.buyerID = '$userID'
    //         AND pd.productID1 != a.productID 
    //         AND pd.productID2 = a.productID
    //         GROUP BY pd.productID1 ORDER BY avg_rating DESC LIMIT $n"; // do not change $n to '$n'! it is an error
    // $result = $connection->query($sql);

    // $list = [];
    // if ($result->num_rows>0) {
    //     while ($row=$result->fetch_assoc()) {
    //         $productID = $row["productID1"];
    //         $avg_rating = $row["avg_rating"];
    //         $list[$productID] = $avg_rating;
    //     }
    // } else {
    //     echo "<br>no personalised recommendations<br>";
    // }

    // return $list;

    $denom = 0.0; // denominator 
    $numer = 0.0; // numerator
    $sql = "SELECT a.productID, a.ratings
            FROM archive a
            WHERE a.buyerID=$userID AND a.productID != $current_productID";
    $result = $connection->query($sql);

    // for all items that the user has rated
    while ($row=$result->fetch_assoc()) {
        $fetched_productID = $row["productID"];
        $ratings = $row["ratings"];

        // get the number of times both products have been rated by the user
        $sql = "SELECT pd.count, pd.sum 
                FROM popularity_diff pd 
                WHERE productID1=$current_productID and productID2=$fetched_productID"; 
        $result_count = $connection->query($sql);

        if ($result_count->num_rows>0) {

            while ($row = $result_count->fetch_assoc()) {
                $count = $row["count"]; 
                $sum = $row["sum"];
                
                // get average
                $average = $sum/$count; 

                $denom += $count;

                $numer += $count * ($average + $ratings);
                break; 
            } 
        }
    }
    $connection->close();

    if ($denom == 0) {
        return 0;
    } else {
        return ($numer/$denom); 
    }

}   

function get_archive($userID="") {
    if (file_exists('../database.php')){
        include '../database.php';
    } else {
        include './database.php';
    }
    $array = [];
    if (empty($userID)) {
        
        $sql = "SELECT DISTINCT productID FROM Archive"; // find the items that are bought
        $result = $connection->query($sql);
        if ($result->num_rows>0) {
            while ($row = $result->fetch_assoc()) {
                array_push($array, $row["productID"]);
            }
        } else {
            "Get archive returns nothing";
        }
        
    } else { 
        $sql = "SELECT DISTINCT productID FROM Archive WHERE buyerID = '$userID'"; // find the items that are bought
        $result = $connection->query($sql);
        if ($result->num_rows>0) {
            while ($row = $result->fetch_assoc()) {
                array_push($array, $row["productID"]);
            }
        } else {
            "Get archive returns nothing";
        }
    }

    $connection->close();
    return $array;
}
?> 