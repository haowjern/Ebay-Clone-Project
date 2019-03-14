<!DOCTYPE html PUBLIC>
<?php
session_start();
include_once "header.php";

// $_SESSION["userID"]=1;
?>

        
<?php

include 'database.php';

// Instantiate from SESSION variables
$userID = $_SESSION["userID"];
// $username = $_SESSION["username"];
// echo $userID ."<br>";
// echo $username ."<br>";
// echo "Successfully retrieved userID from SESSION variables";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Instantiate error messages
                $emailErr=$passwordErr=$DOBErr=$upAccBalanceErr=$phoneErr=$usernameErr="";
                $successMsg="";
                $updated = "";
                
                // newEmail and newConfEmail
                if ((!isset($_POST['newEmail']) or trim($_POST['newEmail']) == '') and (!isset($_POST['newConfEmail']) or trim($_POST['newConfEmail']) == '')) {
                    // Nothing entered for email
                }
                elseif (trim($_POST['newEmail']) !== trim($_POST['newConfEmail'])) {
                    $emailErr = "Please enter matching emails";
                    // return FALSE;

                }
                else {
                    // Sanitise email input
                    $newEmail = trim($_POST['newEmail']);
                    $clean_email = filter_var(trim($_POST['newConfEmail']), FILTER_SANITIZE_EMAIL);
                    if ($newEmail == $clean_email and filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {

                    // Update database - email
                    $sql_Email = "UPDATE users SET email = '$newEmail' WHERE userID = ?";
                    $result_Email = mysqli_prepare($connection, $sql_Email);
                    mysqli_stmt_bind_param($result_Email, 's', $userID);
                    mysqli_stmt_execute($result_Email);

        
                    echo "Email Address has been modified in database"."<br>";
                    $updated = TRUE;
                    }
                }

                // newPassWord and newConfPassWord
                if ((!isset($_POST['newPassWord']) or trim($_POST['newPassWord']) == '') and (!isset($_POST['newConfPassWord']) or trim($_POST['newConfPassWord']) == '')) {
                    // Nothing entered for passwords
                }
                elseif (trim($_POST['newPassWord']) !== trim($_POST['newConfPassWord'])) {
                    $passwordErr = "Please enter matching passwords";
                    // return FALSE;
                }
                else {
                    // Update database - password1 - hashed
                    $passwordFromPost = trim($_POST['newPassWord']);
                    $hash = password_hash($passwordFromPost, PASSWORD_BCRYPT);
                    $sql_Password = "UPDATE users SET password1 = '$hash' WHERE userID = ?";
                    $result_Password = mysqli_prepare($connection, $sql_Password);
                    mysqli_stmt_bind_param($result_Password, 's', $userID);
                    mysqli_stmt_execute($result_Password);
                    echo "Password has been modified in database"."<br>";
                    $updated = TRUE;
                }
                
                // newDOB
                if (!isset($_POST['newDOB']) or trim($_POST['newDOB']) == '') {
                    // Nothing entered for DOB
                }
                elseif (!empty($_POST["newDOB"]) && date_create_from_format("Y-m-d",trim($_POST["newDOB"]))){
                    
                    $maxdate=date("Y-m-d",strtotime('-18 year'));

                    // $maxdate=new Date($maxdaate);
                
                    
                    echo "trying";

                    $newDOB_checked="";
                    echo gettype($newDOB_checked);
                    echo gettype($maxdate);
                    // $newDOB_checked = date_format(date_create_from_format("Y-m-d", $_POST["DOB"]),"Y-m-d");
                    //$a= date_create_from_format("Y-m-d", $_POST["newDOB"]);
                    //$newDOB_checked = date("Y-m-d", $a);

                    $newDOB_checked = new Datetime($_POST["newDOB"]);                    
                    $maxdate = new Datetime($maxdate);
                    
     
     
                    
                    if ($newDOB_checked>$maxdate){
                        $DOBErr= "You must be over 18 years old to use this website.";
                    }else{
                        $newDOB=date_create_from_format("Y-m-d", $_POST["newDOB"]);
                        $DOB_year = (integer)date_format($newDOB,"Y");
                        $DOB_month = (integer)date_format($newDOB,"m");
                        $DOB_day = (integer)date_format($newDOB,"d");
                        $newDOB_str = (string)$DOB_year . "-" . (string)$DOB_month . "-" . (string)$DOB_day;

                        // Update database - DOB
                        $sql_DOB = "UPDATE users SET DOB = '$newDOB_str' WHERE userID = ?";
                        $result_DOB = mysqli_prepare($connection, $sql_DOB);
                        mysqli_stmt_bind_param($result_DOB, 's', $userID);
                        mysqli_stmt_execute($result_DOB);
                        // echo "Date of Birth has been modified in database"."<br>";
                        $updated = TRUE;
                    }
                }
                else {
                    $DOBErr = "Please enter a valid date in YYYY-MM-DD format";
                    // return FALSE;
                }


                // upAccBalance
                if (!isset($_POST['upAccBalance']) or trim($_POST['upAccBalance']) == '') {
                    // Nothing entered for accountBalance
                }
                elseif (is_numeric(trim($_POST['upAccBalance']))) {
                    // Sanitise number input
                    $upAccBalance = trim($_POST['upAccBalance']);
                    
                    // Update database - accountBalance
                    $sql_currBalance = "SELECT accountbalance FROM users WHERE userID = ?";
                    $result_currBalance = mysqli_prepare($connection, $sql_currBalance);
                    mysqli_stmt_bind_param($result_currBalance, 's', $userID);
                    mysqli_stmt_execute($result_currBalance);
                    $result = mysqli_stmt_get_result($result_currBalance);
                    $rows_users = ($result->num_rows);
                    // echo "connected to users" . "<br>";
                
                    if ($rows_users == 1) {
                        echo ($userID . "'s current Account Balance has been found in users table" . "<br>"); // Delete this ########
                        while($row = $result->fetch_assoc()) {
                            $currAccBalance = $row["accountbalance"]."<br>";
                            $sumBalance = ((int)$currAccBalance + (int)$upAccBalance);

                            $sql_upAccBalance = "UPDATE users SET accountbalance = $sumBalance WHERE userID = ?";
                            $result_AccBalance = mysqli_prepare($connection, $sql_upAccBalance);
                            mysqli_stmt_bind_param($result_AccBalance, 's', $userID);
                            mysqli_stmt_execute($result_AccBalance);
                            echo "Account Balance has been topped up in database"."<br>";
                            $updated = TRUE;
                        }
                    }
                }
                else {
                    $upAccBalanceErr = "Please enter a valid number";
                    // return FALSE;

                }

                // newPhoneNo
                if (!isset($_POST['newPhoneNo']) or trim($_POST['newPhoneNo']) == '') {
                    // Nothing entered for PhoneNo
                }
                elseif (is_numeric(trim($_POST['newPhoneNo']))) {
                    // Update database - phone
                    $newPhoneNo = trim($_POST['newPhoneNo']);
                    $sql_Phone = "UPDATE users SET phone = '$newPhoneNo' WHERE userID = ?";
                    $result_Phone = mysqli_prepare($connection, $sql_Phone);
                    mysqli_stmt_bind_param($result_Phone, 's', $userID);
                    mysqli_stmt_execute($result_Phone);
                    echo "Phone Number has been modified in database"."<br>";
                    $updated = TRUE;
                }
                else {
                    $phoneErr = "Please enter a valid phone number (all numeric input)";
                    // return FALSE;

                }

                if ($updated == TRUE) {
                    // echo "Data HAS changed. Server-side validation passed"."<br>"; // Delete this ########
                    // return TRUE;
                    echo "Your account details have been successfully updated";
            
                } else {
                    // echo "Data HAS NOT changed. Server-side validation passed"."<br>"; // Delete this ########
                    // return FALSE;
                }
}


// Search for username in users table
$sql_users = "SELECT * FROM users WHERE userID = ?";
$result_users = mysqli_prepare($connection, $sql_users);
mysqli_stmt_bind_param($result_users, 's', $userID);
mysqli_stmt_execute($result_users);
$result = mysqli_stmt_get_result($result_users);
$rows_users = ($result->num_rows);
// echo "connected to users" . "<br>";


if ($rows_users == 1) {
    // echo ($username . " has been found in users table" . "<br>"); // Delete this ########
    while($row = $result->fetch_assoc()) {
        $username= $row["username"]."<br>";
        $password = $row["password1"]."<br>";
        // echo ($password);
        $email = $row["email"] ."<br>";
        // echo ($email);
        $DOB = $row["DOB"] ."<br>";
        // echo ($DOB);
        $accountbalance = $row["accountbalance"] ."<br>";
        // echo ($accountbalance);
        $phoneNo = $row["phone"] ."<br>";
        // echo ($phoneNo);
    }
}
else {
    echo "Cannot locate $username in users table of database";
}


?>

<html lang="en">
    <head>

    <style>
    div.centered
    {
        text-align: center;
        margin: 0 auto;
    }

    div.centered table
    {
    margin:0 auto;
    }

    .column {
        margin: 0 auto;
        float: left;
        width: 50%;
    }

    /* Clear floats after the columns */
    .row:after 
    {
        margin: 0 auto;
        content: "";
        display: table;
        clear: both;
    }
    </style>
    
    <script language='javascript' type='text/javascript'>
        // Basic validation on client-side
        function validateForm() {
            var newEmail = document.forms["newDetails_form"]["newEmail"].value;
            var newConfEmail = document.forms["newDetails_form"]["newConfEmail"].value;
            var newPassword = document.forms["newDetails_form"]["newPassWord"].value;
            var newConfPassword = document.forms["newDetails_form"]["newConfPassWord"].value;
            

            if (newEmail != newConfEmail) {
                window.alert("Entered email addresses must match.");
                newConfEmail.focus();
                return false;
            }

            if (newPassword != newConfPassword) {
                window.alert("Entered passwords must match.");
                newConfPassword.focus();
                return false;
            }
        return true;
        }
    </script>    
    
    <title>Team 10 eBay - Your Profile</title>

    </head>
    <body>
        <div class="row">
        <div class="column">
        <h3> Current User Details </h3>
        <label>Username:</label><br>
        <?php echo $username; ?><br><br>
        <label>Password:</label><br>
        <?php echo "XXXXXXXXX"; ?><br><br>
        <label>Email:</label><br>
        <?php echo $email; ?><br>
        <label>Date of Birth:</label><br>
        <?php echo $DOB; ?><br>
        <label>Account Balance:</label><br>
        £ <?php echo $accountbalance; ?><br>
        <label>Phone Number:</label><br>
        <?php echo $phoneNo; ?><br>
        <br>
        <?php echo $successMsg;?><br>
        </div>

        <div class="column">
        <h3> Modify User Details </h3>
        <form name="newDetails_form" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
                <label for="email">New Email Address:</label><br>
                <input type="email" name="newEmail" placeholder="New Email">
                <br>
                <br>
                <label for="newConfEmail">Confirm New Email Address:</label><br>
                <input type="email" name="newConfEmail" placeholder="Re-Enter New Email"> <span class="error"><?php echo $emailErr;?></span>
                <br>
                <br>
                <label for="newPassWord">Password:</label><br>
                <input type="password" name="newPassWord" placeholder="New Password" pattern=".{6,}" title="Minimum 6 characters or more">
                <br>
                <br>
                <label for="newConfPassWord">Confirm Password:</label><br>
                <input type="password" name="newConfPassWord" placeholder="Re-Enter New Password" pattern=".{6,}" title="Minimum 6 characters or more"> <span class="error"><?php echo $passwordErr;?></span>
                <br>
                <br>
                <label for="newDOB">Date of Birth:</label><br>
                <input type="date" name="newDOB"><span class="error"><?php echo $DOBErr;?></span>
                <br>
                <br>
                <label for="upAccBalance">Top-Up Account Balance:</label><br>
                £ <input type="number" name="upAccBalance" min="0" max="1000" step="25"> <span class="error"><?php echo $upAccBalanceErr;?></span>
                <br>
                <br>
                <label for="newPhoneNo">Phone Number:</label><br>
                + <input type="text" name="newPhoneNo" pattern="(.*\d)" placeholder="New Phone Number" maxlength=15 title="Numeric characters only, up to 15 characters"> <span class="error"><?php echo $phoneErr;?></span>
                <br>
                <br>
                <p><input type="submit" onclick="return validateForm()" name="newUserDetails" value="Submit Changes"></p>
                <br>
        </form>
        </div>
        </div>

    </body>
</html>
<?php
include_once "footer.php";
?> 