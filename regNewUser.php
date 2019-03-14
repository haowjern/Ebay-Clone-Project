<!DOCTYPE html PUBLIC>
<?php
session_start();
?>

<?php
// Troubleshooting - PHP:
// 1) ***** Error checking with javascript messages? - DON'T WANT IT TO WIPE MY ENTIRE SHEET each time

$emailErr=$DOBErr=$phoneErr=$usernameErr='';

if (!isset($_POST["regNewUser"])) {
    // Do nothing since no $_POST variables created i.e. no signIn form submission
}
else {

 include './database.php';

 // Instantiate user account details from POST variables
 $username = trim($_POST["userName"]);
 $password = trim($_POST["passWord"]);
 $email = trim($_POST["email"]);
 $phoneNo = trim($_POST["phoneNo"]);
 $DOB = trim($_POST["DOB"]);

//  echo $username ."<br>";

// Sanitise username
$username = mysqli_real_escape_string($connection, $username);

// Check for duplicate username in users table
$sql_users = "SELECT username FROM users WHERE username = ?";
$result_users = mysqli_prepare($connection, $sql_users);
mysqli_stmt_bind_param($result_users, 's', $username);
mysqli_stmt_execute($result_users);
$result = mysqli_stmt_get_result($result_users);
$rows_users = ($result->num_rows);
// echo "connected to users" . "<br>";

if ($rows_users == 0) {
    // echo ($username . " is available" . "<br>"); // Delete this ########
}
else {
    $usernameErr = $username . " - duplicate found. Please choose another username" . "<br>";
    //echo ($username . " - duplicate found. Please choose another username" . "<br>"); // Delete this ######## 
}


// Sanitize email
if (!isset($_POST['email']) or !isset($_POST['confEmail'])) {
    $emailErr = "Please enter matching valid email addresses";
    // echo "Please enter matching valid email addresses";
} elseif (trim($_POST['email']) !== trim($_POST['confEmail'])) {
    $emailErr = "Please enter matching valid email addresses";
    // echo "Please enter matching valid email addresses";
} else {
    $orig_email = trim($_POST['email']);
    $clean_email = filter_var(trim($_POST['confEmail']), FILTER_SANITIZE_EMAIL);
    
    if ($orig_email == $clean_email and filter_var($orig_email, FILTER_VALIDATE_EMAIL)) {
        // User-entered email address is safe for storage
    }
    else {
        $emailErr = "Please enter valid email addresses";
        // echo "User-entered email address NOT SAFE!";
    }
}

// Hash plaintext password for storage in database
$hash = password_hash($password, PASSWORD_BCRYPT);


// // Sanitise Date of Birth
// if (!empty($_POST["DOB"]) && date_create_from_format("Y-m-d",$_POST["DOB"])){
//     $DOB=date_create_from_format("Y-m-d",$_POST["DOB"]);  
//     $DOB_year = (integer)date_format($DOB,"Y");
//     $DOB_month = (integer)date_format($DOB,"m");
//     $DOB_day = (integer)date_format($DOB,"d");
//     $DOB_str = (string)$DOB_year . "-" . (string)$DOB_month . "-" . (string)$DOB_day;
// }
// else {
//     $DOBErr = "Please enter a valid date in YYYY-MM-DD format";
//     // echo "User-entered date of birth is NOT SAFE!";
// }

 // newDOB
 if (!isset($_POST['DOB']) or trim($_POST['DOB']) == '') {
    // Nothing entered for DOB
}
elseif (!empty($_POST["DOB"]) && date_create_from_format("Y-m-d",trim($_POST["DOB"]))){
    
    $maxdate=date("Y-m-d",strtotime('-18 year'));
    
    if ($_POST["DOB"]>$maxdate){
        $DOBErr= "You must be over 18 years old to use this website.";
    }else{
        $newDOB=date_create_from_format("Y-m-d", $_POST["DOB"]);
        $DOB_year = (integer)date_format($newDOB,"Y");
        $DOB_month = (integer)date_format($newDOB,"m");
        $DOB_day = (integer)date_format($newDOB,"d");
        $DOB_str = (string)$DOB_year . "-" . (string)$DOB_month . "-" . (string)$DOB_day;

        
    }

}
else {
    $DOBErr = "Please enter a valid date in YYYY-MM-DD format";
    // return FALSE;
}


// Check phone number
if (!empty($_POST["phoneNo"]) && is_numeric($_POST["phoneNo"])){
    $phoneNo = trim($_POST["phoneNo"]);
}
else {
    $phoneErr = "Please enter a valid phone number (all numeric input)";
    // echo "User-entered Phone Number is NOT SAFE!";
}

if ($emailErr === "" and $DOBErr === "" and $phoneErr === "" and $usernameErr === "") {
// INSERT new row to database
echo("Trying to instantiate session variables -- ."."<br>");
$sql_insert = "INSERT INTO users (username, password1, email, phone, accountbalance, DOB) VALUES (?, ?, ?, ?, ?, ?)";
$insert_user = mysqli_prepare($connection, $sql_insert);
$zero = 0;
mysqli_stmt_bind_param($insert_user, 'ssssis', $username, $hash, $email, $phoneNo, $zero, $DOB_str);
$result = mysqli_stmt_execute($insert_user);
 
// INSTANTIATE SESSION variables
// echo("Trying to instantiate session variables."."<br>");
$sql_users = "SELECT userID FROM users WHERE username = ?";
$result_users = mysqli_prepare($connection, $sql_users);
mysqli_stmt_bind_param($result_users, 's', $username);
mysqli_stmt_execute($result_users);
$result = mysqli_stmt_get_result($result_users);
$rows_users = ($result->num_rows);
// echo "$rows_users";
    if ($rows_users == 1) {
        // echo("Successfully inserted new user."."<br>");

        while($row = $result->fetch_assoc()) {
            $userID = $row['userID'];
            $_SESSION["userID"] = $userID;
            $_SESSION["userName"] = $username;
            // echo("Successfully created SESSION variables."."<br>");

            $host  = $_SERVER['HTTP_HOST'];
            $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'index.php';
            $link="http://".$host.$uri."/".$extra;
        
            header("Location: http://$host$uri/$extra");
        }
    }
}
else {
    // echo("Error: " . $sql_insert . "<br>" . $connection->error);
    // echo("Please provide valid inputs to register as a new user");
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
        <br>
        <br>
        <br>
        <div align="center">
        <h3> New User Registration </h3>
        <form name="register_form" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" onsubmit="return validateForm()" method="post">
        <label for="userName">Username:</label><br>
        <input type="text" name="userName" placeholder="Username" pattern=".{3,}" required title="Minimum 3 characters or more" maxlength=15>
        <br>
        <p><?php echo $usernameErr?><p>
        <br>
        <label for="email">Email Address:</label><br>
        <input type="email" name="email" placeholder="Email" required>
        <br>
        <br>
        <label for="confEmail">Confirm Email Address:</label><br>
        <input type="email" name="confEmail" placeholder="Re-Enter Email" required>
        <br>
        <p><?php echo $emailErr?><p>
        <br>
        <label for="passWord">Password:</label><br>
        <input type="password" name="passWord" placeholder="Password" pattern=".{6,}" required title="Minimum 6 characters or more">
        <br>
        <br>
        <label for="confPassWord">Confirm Password:</label><br>
        <input type="password" name="confPassWord" placeholder="Re-Enter Password" pattern=".{6,}" required title="Minimum 6 characters or more"> 
        <br>
        <p><p>
        <br>
        <label for="DOB">Date of Birth:</label><br>
        <input type="date" name="DOB" required>
        <br>
        <p><?php echo $DOBErr?><p>
        <br>
        <label for="phoneNo">Phone Number:</label><br>
        + <input type="text" name="phoneNo" pattern="(.*\d)" placeholder="Phone Number" maxlength=15 required title="Numeric characters only, up to 15 characters">
        <br>
        <p><?php echo $phoneErr?><p>
        <br>
        <p><input type="submit" name="regNewUser" value="Register"></p>
        <br>
        <br>
        </form>
        </div>

    </body>
</html>
