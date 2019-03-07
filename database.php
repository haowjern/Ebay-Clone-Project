<!DOCTYPE html>
<html>
<body>

<p>Connect to database</p>
<p>Database.php</p>
 
<?php

  $host  = $_SERVER['HTTP_HOST'];

  if (strpos($host,"localhost")===0){

      $connection = mysqli_connect("localhost", "root", "", "ebaydb");

      if (mysqli_connect_errno()){
        echo 'Failed to connect to the MySQL server: '. mysqli_connect_error();}

  }else{



    $serverName = "ebaydatabasegithub.azurewebsites.net"; // update me
    $connectionOptions = array(
        "Database" => "localdb", // update me
        "Uid" => "azure", // update me
        "PWD" => "6" // update me
    );
    
    //Establishes the connection
    $conn = sqlsrv_connect($serverName, $connectionOptions);
    
    // test (update if doing)
    /*$tsql= "SELECT TOP 20 pc.Name as CategoryName, p.name as ProductName
         FROM [SalesLT].[ProductCategory] pc
         JOIN [SalesLT].[Product] p
         ON pc.productcategoryid = p.productcategoryid";
    $getResults= sqlsrv_query($conn, $tsql);
    echo ("Reading data from table" . PHP_EOL);
    if ($getResults == FALSE)
        echo (sqlsrv_errors());
    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
     echo ($row['CategoryName'] . " " . $row['ProductName'] . PHP_EOL);
    }
    sqlsrv_free_stmt($getResults);*/



   /* 
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
      }
  */
?>
  
</body>
</html>