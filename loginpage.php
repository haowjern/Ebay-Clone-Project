<!DOCTYPE html PUBLIC>
<?php session_start(); ?>

<?php
// Troubleshooting - PHP:
// 1) ***** How do I revert back to loginpage to give error message "Please enter correct username and password combination"
// 2) Change password1 field into password in the users and admins tables of the database
// 3) include connection as a SEPARATE php file -- Needs to connect to Azure database file
// 4) How do I suggest a NEW USER REGISTRATION? Register button doesn't link to other .php file -- IS THIS CORRECTLY DONE using an <a href>?

if(!isset($_POST["signIn"])) {
    // Do nothing since no $_POST variables created i.e. no signIn form submission
}
else {
    // Instantiate Session variables upon successful login
    function createSession($userID, $username) {
        $_SESSION["userID"] = $userID;
        $_SESSION["username"] = $username;
        echo $_SESSION["userID"]."<br>";
        echo $_SESSION["username"]."<br>";
    }        

    // Recognise user input
    function hasDataInput() {
        $errorMessage = null;
        if (!isset($_POST['userName']) or trim($_POST['userName']) == '') {
            $errorMessage = 'Please enter a valid username';
        }
        else if (!isset($_POST['passWord']) or trim($_POST['passWord']) == '') {
            $errorMessage = 'Please enter a valid password';
        }
        
        if ($errorMessage !== null) {
            echo "Server-side validation failed";
            return FALSE;
            }
        
        echo "Server-side validation passed"."<br>"; // Delete this ########
        return TRUE;
        }
        
    // Validation process
    if (hasDataInput() == TRUE) {
        // Need to include connection as a SEPARATE php file - 
        $connection = mysqli_connect('localhost', 'root', '','dummy') or die(mysqli_error()); 
        if (mysqli_connect_errno()){
            echo 'Failed to connect to the MySQL server: '. mysqli_connect_error();
        }
        else {
            echo "Successfully connected to server"."<br>";
        }
                                                                                                                                                                                                            

        // Step 1 - Sanitise and Instantiate from $_POST array
        $username = trim($_POST["userName"]);
        $user_pass = trim($_POST["passWord"]);
        echo $username."<br>";
        echo $user_pass."<br>";

        // Sanitise username - Do I need to sanitize? Unnecessary on Plaintext if bind_param is used correctly ==== DELETE ##########
        // $username = filter_var($username_to_sanitise, FILTER_SANITIZE_STRING);


        // Step 3 - Search for username and password in admins database and users database

        // Search for username in admins table
        $sql_admins = "SELECT username FROM admins WHERE username = ?"; 
        $result_admins = mysqli_prepare($connection, $sql_admins);
        mysqli_stmt_bind_param($result_admins, 's', $username);
        mysqli_stmt_execute($result_admins);
        $result = mysqli_stmt_get_result($result_admins);
        $rows_admins = ($result->num_rows);
        echo $rows_admins;
        echo "connected to admins" . "<br>";

        // Search for username in users table
        $sql_users = "SELECT username FROM users WHERE username = ?";
        $result_users = mysqli_prepare($connection, $sql_users);
        mysqli_stmt_bind_param($result_users, 's', $username);
        mysqli_stmt_execute($result_users);
        $result = mysqli_stmt_get_result($result_users);
        $rows_users = ($result->num_rows);
        echo $rows_users;
        echo "connected to users" . "<br>";

        // Username found in admins table
        if ($rows_admins == 1) {
            mysqli_stmt_execute($result_admins);
            echo ($username . " has been found in admins table" . "<br>"); // Delete this ########
            
            // Check if password matches in admins table
            $sql_admins_pw = "SELECT * FROM admins WHERE username = ?";
            $result_admins_pw = mysqli_prepare($connection, $sql_admins_pw);
            mysqli_stmt_bind_param($result_admins_pw, 's', $username);
            mysqli_stmt_execute($result_admins_pw);
            $result = mysqli_stmt_get_result($result_admins_pw);
            
            while($row = $result->fetch_assoc()) {
                if (password_verify($user_pass, $row["password1"])) {  
                    echo "Successfully logged in"."<br>";
                    $adminID = $row["adminID"];   
                    createSession($adminID, $username);
                    echo "Successfully created Session variable"."<br>";
                }
                else {
                    echo "Invalid password entered"."<br>";
                }
            }   
        }

        // Username found in users table
        elseif ($rows_users == 1) {
            echo ($username . " has been found in users table" . "<br>"); // Delete this ########
            
            // Check if password matches in users table
            $sql_users_pw = "SELECT * FROM users WHERE username = ?";
            $result_users_pw = mysqli_prepare($connection, $sql_users_pw);
            mysqli_stmt_bind_param($result_users_pw, 's', $username);
            mysqli_stmt_execute($result_users_pw);
            $result = mysqli_stmt_get_result($result_users_pw);
            
            while($row = $result->fetch_assoc()) {
                if (password_verify($user_pass, $row["password1"])) {
                    echo "Successfully logged in"."<br>";
                    $userID = $row["userID"]; 
                    createSession($userID, $username);
                    echo "Successfully created Session variable"."<br>";
                }
                else {
                    echo "Invalid password entered"."<br>";
                }
            }
        }
        else {
            echo ($username . " is not registered in the database. Do you wish to register as a new user?" . "<br>"); // Delete this ########
        }
    }
    else {
        echo "Data did not pass validation";
    }
}
?>

<!-- Troubleshooting / To Dos - HTML / JS: 
2) How to add javascript to return error messages from server side if incorrect input given
-->

<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="Content-type" content="text/html"; charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Team 10 eBay - Login</title>
    </head>
    <body>

        <h3> Please log in </h3>
        <form name="login_form" action="" method="post">
        <label for="userName">Username:</label><br>
        <input type="text" name="userName" placeholder="Username" pattern=".{3,}" required title="Minimum 3 characters or more"><span id="error_userName"></span>
        <br>
        <br>
        <label for="passWord">Password:</label><br>
        <input type="password" name="passWord" placeholder="Password" pattern=".{6,}" required title="Minimum 6 characters or more"><span id="error_passWord"></span>
        <br>
        <br>
        <p><input type="submit" onclick="return validateForm(this)" name="signIn" value="Sign in"></p>  <!-- Should I remove the onclick? -->
        <a href="./regNewUser.php"><input type="button" name="regNewUser" value="Register"></a> 
        <br>
        </form>
            
    </body>
</html>