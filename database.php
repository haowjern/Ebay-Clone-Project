<!DOCTYPE html>
<html>
<body>

<p>Connect to Dummy database</p>
 
  <?php
  #$connection = mysqli_connect("localhost", "ebaydb");
  
  $connection = mysqli_connect("https://ebaydatabasegithub.azurewebsites.net/", "ebaydb");
  

  if (mysqli_connect_errno()){
    echo 'Failed to connect to the MySQL server: '. mysqli_connect_error();}

?>

</body>
</html>