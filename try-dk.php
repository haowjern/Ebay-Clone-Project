<?php
$password = 'abc124';
$hash = password_hash($password, PASSWORD_BCRYPT);
echo $hash;
?>