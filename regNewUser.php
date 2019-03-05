<!DOCTYPE html PUBLIC>
<?php
session_start();
?>

<?php
// Troubleshooting - PHP:
// 1) ***** Error checking with javascript messages? - DON'T WANT IT TO WIPE MY ENTIRE SHEET each time


if(!isset($_POST["regNewUser"])) {
    // Do nothing since no $_POST variables created i.e. no signIn form submission
}
else {
 // Need to include connection as a SEPARATE php file - 
 $connection = mysqli_connect('localhost', 'root', '','dummy') or die(mysqli_error()); 
 if (mysqli_connect_errno()){
     echo 'Failed to connect to the MySQL server: '. mysqli_connect_error();
 }
 else {
     echo "Successfully connected to server\n";
 }

 // Instantiate username from POST variables
 $username = trim($_POST["userName"]);
 $password = trim($_POST["passWord"]);
 $email = trim($_POST["email"]);
 $phoneNo = trim($_POST["phoneNo"]);
 $DOB = trim($_POST["DOB"]);

 echo $username ."<br>";

// Sanitise username?? --- DELETE ##########


// Check for duplicate username in users table and admins table
$sql_users = "SELECT username FROM users WHERE username = ?";
$result_users = mysqli_prepare($connection, $sql_users);
mysqli_stmt_bind_param($result_users, 's', $username);
mysqli_stmt_execute($result_users);
$result = mysqli_stmt_get_result($result_users);
$rows_users = ($result->num_rows);
echo "connected to users" . "<br>";

$sql_admins = "SELECT username FROM admins WHERE username = ?";
$result_admins = mysqli_prepare($connection, $sql_admins);
mysqli_stmt_bind_param($result_admins, 's', $username);
mysqli_stmt_execute($result_admins);
$result = mysqli_stmt_get_result($result_admins);
$rows_admins = ($result->num_rows);
echo $rows_admins;
echo "connected to admins" . "<br>";

if (($rows_users == 0) and ($rows_admins == 0)) {
    echo ($username . " is available (users and admins - OK)" . "<br>"); // Delete this ########
    
}
else {
echo ($username . " - duplicate found. Please choose another username" . "<br>"); // Delete this ######## 

}


// Sanitize email
if (empty($_POST['email']) or empty($_POST['confEmail'])) {
    echo "Please enter a matching valid email address";
}

$orig_email = trim($_POST['email']);
$clean_email = filter_var(trim($_POST['confEmail']), FILTER_SANITIZE_EMAIL);
if ($orig_email == $clean_email and filter_var($orig_email, FILTER_VALIDATE_EMAIL)) {
    echo "User-entered email address is safe for storage!";
}
else {
    echo "User-entered email address NOT SAFE!";
}


// Hash plaintext password for storage in database
$hash = password_hash($password, PASSWORD_BCRYPT);

// INSERT new row to database
$sql_insert = "INSERT INTO users (username, password1, email, phone, accountbalance, DOB) VALUES (?, ?, ?, ?, ?, ?)";
$insert_user = mysqli_prepare($connection, $sql_insert);
$zero = 0;
mysqli_stmt_bind_param($insert_user, 'ssssis', $username, $hash, $email, $phoneNo, $zero, $DOB);
$result = mysqli_stmt_execute($insert_user);

if ($result==TRUE) {
    echo("Successfully inserted new user."."<br>");

    // INSTANTIATE SESSION variables
    $sql_users = "SELECT userID FROM users WHERE username = ?";
    $result_users = mysqli_prepare($connection, $sql_users);
    mysqli_stmt_bind_param($result_users, 's', $username);
    mysqli_stmt_execute($result_users);
    $result = mysqli_stmt_get_result($result_users);
    $rows_users = ($result->num_rows);

    if ($rows_users == 1) {
        while($row = $result->fetch_assoc()) {
            $userID = $row['userID'];
            $_SESSION["userID"] = $userID;
            $_SESSION["userName"] = $username;
            echo("Successfully created SESSION variables."."<br>");
        }
    }
} else {
    echo("Error: " . $sql_insert . "<br>" . $connection->error);
    }
}
?>


<!-- Troubleshooting / To dos:
1) How do I include DYNAMIC input error messages for each input box? ON THE SAME PAGE, whilst KEEPING the data in the FORM fields
-->

<html lang="en">
    <head>             
        <script language='javascript' type='text/javascript'>
            // Basic validation on client-side
            function validateForm() {
                var email = document.forms["register_form"]["email"].value;
                var confEmail = document.forms["register_form"]["confEmail"].value;
                var password = document.forms["register_form"]["passWord"].value;
                var confPassword = document.forms["register_form"]["confPassWord"].value;

                if (email != confEmail) {
                    window.alert("Entered email addresses must match.");
                    confEmail.focus();
                    return false;
                }

                if (password != confPassword) {
                    window.alert("Entered passwords must match.");
                    confPassword.focus();
                    return false;
                }
            return true;
            }
        </script>
        
        <title>Team 10 eBay - New User Registration</title>

    </head>
    <body>

        <h3> New User Registration </h3>
        <form name="register_form" action="" onsubmit="return validateForm()" method="post">
        <label for="userName">Username:</label><br>
        <input type="text" name="userName" placeholder="Username" pattern=".{3,}" required title="Minimum 3 characters or more" maxlength=15>
        <br>
        <br>
        <label for="email">Email Address:</label><br>
        <input type="email" name="email" placeholder="Email" required>
        <br>
        <br>
        <label for="confEmail">Confirm Email Address:</label><br>
        <input type="email" name="confEmail" placeholder="Re-Enter Email" required>
        <br>
        <br>
        <label for="passWord">Password:</label><br>
        <input type="password" name="passWord" placeholder="Password" pattern=".{6,}" required title="Minimum 6 characters or more">
        <br>
        <br>
        <label for="confPassWord">Confirm Password:</label><br>
        <input type="password" name="confPassWord" placeholder="Re-Enter Password" pattern=".{6,}" required title="Minimum 6 characters or more"> 
        <br>
        <br>
        <label for="DOB">Date of Birth:</label><br>
        <input type="date" name="DOB" required>
        <br>
        <br>
        <label for="phoneNo">Phone Number:</label><br>
        + <input type="text" name="phoneNo" pattern="(.*\d)" placeholder="Phone Number" maxlength=15 required title="Numeric characters only, up to 15 characters">
        <br>
        <br>
        <p><input type="submit" name="regNewUser" value="send"></p>
        <br>
        </form>

    </body>
</html>
