<?php

$photo_arr = array(
    "photoID" => 0,
    "productID" => 0,
    "file_path" => ""
);

function add_random_photos($photo_obj, $instr) {
    include 'database.php'; // connect to database 
    include 'product.php';

    $sql = "SELECT * FROM Product"; 
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $pID = $row['productID'];
            $path = "./images/dummy_data/{$pID}";
            $photo_arr["productID"] = $pID;
            $photo_arr["file_path"] = $path;

            set_photo($photo_arr, $instr);
        }

    } else {
        echo ("Can't add dummy photos. Can't {$sql}.");
    }
}

class Photo {
    public function __construct($photoID=0, $productID=0, $file_path="")
    {
        $this->photoID = $photoID;
        $this->productID = $productID;
        $this->file_path = $file_path;
    }
}

$photo_obj = new Photo();
$instr = "insert";
add_random_photos($photo_obj, $instr);

?> 