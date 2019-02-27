<?php
session_start();

//script to remove listing from product table.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include "database.php";

    $productID=mysqli_real_escape_string($connection,$_POST["productID"]);

    $sql="DELETE FROM Product WHERE productID='$productID'";
            $result=$connection->query($sql);

            if ($result==TRUE){
                echo "Record deleted successfully";

            } else {
            echo "Error: ". $sql . "<br>" . $connection->error;
            }

    $connection->close();
    header("Location: sellershop.php"); 

}
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'sellershop.php';
    $link="http://".$host.$uri."/".$extra;

//                 header("Location: http://$host$uri/$extra");
?>
<html>
<body>
    <button onclick="window.location.href = '<?php echo $link; ?>'">Go back to myshop</button>
</body>
</html>