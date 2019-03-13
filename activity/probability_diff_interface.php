<?php 

function set_popularity_diff($some_arr, $instr) {
    include '../database.php';
    $productID = $some_arr["productID"]; // item that was just rated 
    $userID = $some_arr["userID"]; // user that rated the item

    if ($instr === "insert") {
        // get all of the users' rating pairs
        $sql = "SELECT DISTINCT r1.productID, r2.ratingValue - r1.ratingValue as rating_diff
                FROM rating as r1, rating as r2
                WHERE r1.buyerID = $userID AND r2.buyerID = $userID AND r2.productID=$productID"; 
        $result = $connection->query($sql); 
        if ($result->num_rows>0) {
            echo("Obtained user ratings.\n");
            
            // for every one of the users' rating pairs, update probability_diff table
            while ($row=$result->fetch_assoc()) {
                $other_productID = $row["productID"];
                $rating_diff = $row["rating_diff"]; 

                // if the pair of products are already in the probability_diff table
                $sql = "SELECT productID1 FROM probability_diff 
                        WHERE productID1=$productID AND productID2=$other_productID";
                $result_pair = $connection->query($sql);

                if ($result_pair->num_rows>0) {
                    // update the first row for this pair
                    $sql = "UPDATE probability_diff SET count=count+1, sum=sum+$rating_diff
                            WHERE productID1=$productID AND productID2=$other_productID";
                    $result_update = $connection->query($sql);

                    // update the second row for this pair 
                    // we only want to update if the items are different 
                    // why? (I think because to only have 1 of the same pair, and also by having
                    // the same pair, we are also able to see the 'count' value which will help
                    // i think)
                    if ($productID != $other_productID) {
                        $sql = "UPDATE probability_diff SET count=count+1, sum=sum-$rating_diff
                                WHERE productID1=$other_productID AND productID2=$productID";
                        $result_update = $connection->query($sql);
                    }
                } else {
                    // insert first row for this pair 
                    $sql = "INSERT INTO probability_diff VALUES ($productID, $other_productID, 1, $rating_diff)";
                    $result_insert = $connection->query($sql);

                    // insert second row for this pair if they are not the same 
                    if ($productID != $other_productID) {
                        $sql = "INSERT INTO probability_diff VALUES ($other_productID, $productID, 1, -$rating_diff)";
                        $result_insert = $connection->query($sql);
                    }
                }
            }
        } else {
            echo("Error: " . $sql . "<br>" . $connection->error);
        }
    } else {
        echo("Error: Selected wrong instruction for set_popularity_diff");
    }
    $connection->close();
}

function get_general_recommendations($current_productID) {
    include '../database.php';
    // select 10 items that is the most popular
    // sum/count means, the better the item is compared to the rest (rating diff), the higher the sum
    // the more people bid on the same item, the higher the count 
    // sum/count acts like a normalisation
    $sql = "SELECT productID2, (sum/count) AS average
            FROM probability_diff 
            WHERE count > 2  AND productID1 = $current_productID
            ORDER BY (sum/count) DESC
            LIMIT 10 "; 
    $result = $connection->query($sql);
    $list = [];
    if ($result->num_rows>0) {
        while ($row->fetch_assoc()) {
            $_list[$row["productID2"]] = $row["average"];
        }
    } 
    return $list;
    $connection->close();
}

function get_personalised_recommendations($userID, $n) {//$current_productID) { //
    include  '../database.php';
    // $denom = 0.0; // denominator 
    // $numer = 0.0; // numerator
    // $sql = "SELECT r.productID, r.ratingValue
    //         FROM rating r
    //         WHERE r.buyerID=$userID AND r.productID != $current_productID";
    // $result = $connection->query($sql);

    // // for all items that the user has rated
    // while ($row=$result->fetch_assoc()) {
    //     $fetched_productID = $row["productID"];
    //     $ratingValue = $row["ratingValue"];

    //     // get the number of times both products have been rated by the user
    //     $sql = "SELECT pd.count, pd.sum 
    //             FROM probability_diff pd 
    //             WHERE productID1=$current_productID and productID2=$fetched_productID"; 
    //     $result_count = $connection->query($sql);

    //     if ($result_count->num_rows>0) {
    //         $row = $result->fetch_assoc(); // get only the first row 
    //         $count = $row["count"]; 
    //         $sum = $row["sum"];
            
    //         // get average
    //         $average = $sum/$count; 

    //         $denom += $count;

    //         $numer += $count * ($average + $ratingValue);
            
    //     }
    // }

    // if ($denom == 0) {
    //     return 0;
    // } else {
    //     return ($numer/$denom); 
    // }

    $sql = "SELECT pd.productID1, sum(pd.sum + pd.count*r.ratingValue)/sum(pd.count) as avg_rating
            FROM rating as r, probability_diff as pd 
            WHERE r.buyerID=$userID 
            AND pd.productID1 != r.productID 
            AND pd.productID2 = r.productID
            GROUP BY pd.productID1 ORDER BY avg_rating DESC LIMIT $n";
    $result = $connection->query($sql);

    $list = [];
    if ($result->num_rows>0) {
        while ($row->fetch_assoc()) {
            $productID = $row["productID1"];
            $avg_rating = $row["avg_rating"];
            $list[$productID] = $avg_rating;
        }

    } else {
        echo "<br>no personalised recommendations<br>";
    }

    return $list;
    
}   

?> 