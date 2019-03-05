<?php 
function set_photo($photo_arr, $instr) {
    /* Add photo to database 
    Parameters: 
    - <$photo_obj>: Object with attributes - photoID, productID, file_path
    - <$instr>: 
    */ 

    include '../database.php';

    // check object has the correct properties
    $properties = ["photoID", "productID", "file_path"];
    foreach ($properties as $value) {
        if (!array_key_exists($value, $photo_arr)) {
            echo "Parameter is not an object with the correct properties\n"; 
        }
    }

    $pID = $photo_arr['productID'];
    $photoID = $photo_arr['photoID'];
    $file_path = $photo_arr['file_path'];

    // add new photo 
    if ($instr = "insert") {
        $sql = "INSERT INTO Photos (productID, file_path) VALUES ('$pID', '$file_path')";
        $result = $connection->query($sql); 
        if ($result==TRUE) {
            echo("Inserted new photos.\n");
            
            //fetch new photoID
            $sql="SELECT LAST_INSERT_ID()";
            $result=$connection->query($sql);
            if ($result->num_rows>0) {
                while($row = $result->fetch_assoc()){
                    $photo_arr['photoID'] = $row["LAST_INSERT_ID()"];    
                }
            } else {
                echo("Error: " . $sql . "<br>" . $connection->error);
            }

        } else {
            echo("Error: " . $sql . "<br>" . $connection->error);
        }

    // update photo
    } elseif ($instr = "update") {
        $sql = "INSERT INTO Photos (productID, file_path) VALUES ('$pID', '$file_path')
        WHERE photoID = '$photoID'";

        if ($connection->query($sql)==TRUE) {
            echo("Updated photos.");
        } else {
            echo("Error: " . $sql . "<br>" . $connection->error);
        }

    // delete photo 
    } elseif ($instr = "delete") {
        $sql="DELETE FROM Photos WHERE photoID = '$photoID'";
        
        if ($connection_->query($sql)==TRUE) {
            echo("Deleted photo successfully.");
        } else {
            echo("Error: " . $sql . "<br>" . $connection->error);
        }

    } else {
        echo("Error: Selected wrong instruction for set_photo.");
    }

    return $photo_arr;

    $connection->close();
};

function get_photo($productID) {
    /* Return result of SQL query for each row for photos of the same productID.

    */
    include '../database.php';

    // select and return photo array
    // there are two cases - select user ID, or u select photo ID - currently I just want to select for productID
    $sql = "SELECT * FROM Photos WHERE productID = ($productID) ";
    $result = $connection->query($sql);
    if ($result->num_rows>0) {
        

        $photos = [];
        while ($row = $result->fetch_assoc()){
            $photo_arr['photoID'] = $row['photoID'];
            $photo_arr['productID'] = $row['productID'];
            $photo_arr['file_path'] = $row['file_path']; 

            array_push($photos, $photo_arr);
        }

    } else {
        echo("Error: " . $sql . "<br>" . $connection->error);
    }

    echo("Returned new photos.\n");
    return $photos;

    $connection->close();
}
?>