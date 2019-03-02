<!DOCTYPE html>
<html>
<body>

<p>Connect to database</p>
<p>Database.php</p>
 

<?php
$connection = mysqli_connect("localhost", "root", "", "ebaydb");  
if (!$connection)
{
    echo "Failed to connect to the database.\n";
}
else
{
    echo "Successfully connected to the database.\n";
}
?>
</body>
</html>