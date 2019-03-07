<?php
session_start();

//script to remove listing from product table.
echo "This is the userID to be deleted: " . $_POST["userID"];

// Need to include connection as a SEPARATE php file - 
$connection = mysqli_connect('localhost', 'root', '','dummy') or die(mysqli_error()); 
if (mysqli_connect_errno()){
    echo 'Failed to connect to the MySQL server: '. mysqli_connect_error();
}
else {
    echo "Successfully connected to server\n";
}

$rem_userID=mysqli_real_escape_string($connection,$_POST["userID"]);
echo "This is the userID to be deleted: " . $rem_userID;

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     // include "database.php";
//     $rem_userID=mysqli_real_escape_string($connection,$_POST["userID"]);
// }

$sql="DELETE FROM users WHERE userID='$rem_userID'";
$result=$connection->query($sql);

if ($result==TRUE){
    echo "Record deleted successfully";
} else {
echo "Error: ". $sql . "<br>" . $connection->error;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $connection->close();

    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'manageUsers.php';
    $link="http://".$host.$uri."/".$extra;

    header("Location: http://$host$uri/$extra");
}
?>
<html>
<body>
    <!-- <button onclick="window.location.href = '<?php echo $link; ?>'">Go back to Manage Users</button> -->
</body>
</html>
