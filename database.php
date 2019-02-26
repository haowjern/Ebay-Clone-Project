<!DOCTYPE html>
<html>
<body>

<p>Connect to Dummy database</p>
 

<?php
#$connection = mysqli_connect("localhost", "ebaydb");  
$connectstr_dbhost = '';
$connectstr_dbname = '';
$connectstr_dbusername = '';
$connectstr_dbpassword = '';
foreach ($_SERVER as $key => $value)
{
    if (strpos($key, "MYSQLCONNSTR_") !== 0)
    {
        continue;
    }
    $connectstr_dbhost = preg_replace("/^.*Data Source=(.+?);.*$/", "\\1", $value);
    $connectstr_dbname = preg_replace("/^.*Database=(.+?);.*$/", "\\1", $value);
    $connectstr_dbusername = preg_replace("/^.*User Id=(.+?);.*$/", "\\1", $value);
    $connectstr_dbpassword = preg_replace("/^.*Password=(.+?)$/", "\\1", $value);
    echo $value;
}
define('DB_NAME', $connectstr_dbname);
define('DB_USER', $connectstr_dbusername);
define('DB_PASSWORD', $connectstr_dbpassword);
define('DB_HOST', $connectstr_dbhost);
// Custom testing
echo $connectstr_dbname."    \n";
echo $connectstr_dbusername."    \n";
echo $connectstr_dbpassword."    \n";
echo $connectstr_dbhost."    \n";
$connection = $dbLink = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
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