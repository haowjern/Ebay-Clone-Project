<?php 



function set_rating($rating_arr, $instr) {
    include "../database.php";

    $productID = $rating_arr['productID'];
    $userID = $rating_arr['userID'];
    $ratingValue = $rating_arr['ratingValue'];

    // add new 
    if ($instr === "insert") {
        $sql = "INSERT INTO rating (productID, buyerID, ratingValue, datetimestamp) VALUES ('$productID', '$userID', '$ratingValue', CURRENT_TIMESTAMP)";
        $result = $connection->query($sql); 
        if ($result==TRUE) {
            echo("Inserted new ratings.\n");
        } else {
            echo("Error: " . $sql . "<br>" . $connection->error);
        }

    } else {
        echo("Error: Selected wrong instruction for set_rating.");
    }
    $connection->close();
}

// function get_rating($condition, $productID) {
//     include '../database.php';
    
//     $bids = []; 
//     if ($condition == "latest") {
//         $sql = "SELECT * FROM bidEvents WHERE bidPrice=(
//             SELECT MAX(bidPrice) FROM bidEvents WHERE productID = '$productID'
//             );";
//         $result = $connection->query($sql);
//         if ($result->num_rows>0) { 
//             while ($row=$result->fetch_assoc()) {
//                 $bid_arr['bidID'] = $row['bidID'];
//                 $bid_arr['productID'] = $row['productID'];
//                 $bid_arr['buyerID'] = $row['buyerID'];
//                 $bid_arr['payment'] = $row['payment'];
//                 $bid_arr['bidPrice'] = $row['bidPrice'];
    
//                 array_push($bids, $bid_arr);
//             }

//             echo("Received bid event successfully.");
//         } else {
//             $bid_arr['bidID'] = 0; 
//             $bid_arr['productID'] = 0;
//             $bid_arr['buyerID'] = 0;
//             $bid_arr['payment'] = 0;
//             $bid_arr['bidPrice'] = 0;
//             array_push($bids, $bid_arr);
//         }

//     } elseif ($condition == "all") {
//         $sql = "SELECT * FROM bidEvents WHERE productID = '$productID'
//                 ORDER BY bidID DESC";
//         $result = $connection->query($sql);
//         if ($result->num_rows>0) { 
//             while ($row=$result->fetch_assoc()) {
//                 $bid_arr['bidID'] = $row['bidID'];
//                 $bid_arr['productID'] = $row['productID'];
//                 $bid_arr['buyerID'] = $row['buyerID'];
//                 $bid_arr['payment'] = $row['payment'];
//                 $bid_arr['bidPrice'] = $row['bidPrice'];
//                 array_push($bids, $bid_arr);
//             }
//             echo("Received bid event successfully.");
//         } else {
//             $bid_arr['bidID'] = 0; 
//             $bid_arr['productID'] = 0;
//             $bid_arr['buyerID'] = 0;
//             $bid_arr['payment'] = 0;
//             $bid_arr['bidPrice'] = 0;
//             array_push($bids, $bid_arr);
//         }
//     }
//     return $bids;
//     $connection->close();
//}
?>