<?php
    session_start();
    session_unset();
    session_destroy();
    session_write_close();
    setcookie(session_name(),'',0,'/');
    session_regenerate_id(true);

    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'loginpage.php';
    $link="http://".$host.$uri."/".$extra;

    header("Location: http://$host$uri/$extra");
?>
