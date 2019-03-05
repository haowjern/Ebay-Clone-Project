<!DOCTYPE html PUBLIC>
<?php
session_start();
?>

<!-- Troubleshooting / To dos:
1) How do I include multiple error messages for each input box? WITHOUT CLEARING ENTIRE FORM
2) ***** SQL injection preventative changes - use USERID since its been instantiated. ##### NOTE: UserID IS NOT instantiated for regNewUser NOR loginpage
3) SEPARATE PAGES for the individual fields the User can modify

// Separate the modifications they are to make
User can choose to modify -- ON SEPARATE PAGES
- Email Address (Enter new email TWICE to confirm, Enter current password to confirm)
- Password (Enter Old, Enter new password TWICE to confirm)
-->

<?php

    // Need to include connection as a SEPARATE php file - 
    $connection = mysqli_connect('localhost', 'root', '','dummy') or die(mysqli_error()); 
    if (mysqli_connect_errno()){
        echo 'Failed to connect to the MySQL server: '. mysqli_connect_error();
    }
    else {
        echo "Successfully connected to server"."<br>";
    }

    // Instantiate from SESSION variables
    $_SESSION["userID"] = 7; // this is fixed for example only - DELETE
    $_SESSION["username"] = 'abc2'; // this is fixed for example only - DELETE
    $userID = $_SESSION["userID"];
    $username = $_SESSION["username"];
    echo $userID ."<br>";
    echo $username ."<br>";
    echo "Successfully retrieved userID from SESSION variables";

    // Search for username in users table
    $sql_users = "SELECT * FROM users WHERE userID = ?";
    $result_users = mysqli_prepare($connection, $sql_users);
    mysqli_stmt_bind_param($result_users, 's', $userID);
    mysqli_stmt_execute($result_users);
    $result = mysqli_stmt_get_result($result_users);
    $rows_users = ($result->num_rows);
    echo "connected to users" . "<br>";

    if ($rows_users == 1) {
        echo ($username . " has been found in users table" . "<br>"); // Delete this ########
        while($row = $result->fetch_assoc()) {
            $password = $row["password1"]."<br>";
            $email = $row["email"] ."<br>";
            $DOB = $row["DOB"] ."<br>";
            $accountbalance = $row["accountbalance"] ."<br>";
            $phoneNo = $row["phone"] ."<br>";
        }
    }
    else {
        echo "Cannot locate $username in users table of database";
    }

if(!isset($_POST["newUserDetails"])) {
    // Do nothing since no $_POST variables created i.e. no signIn form submission
}
else {
// Step 1 - Validate user input
    
    function hasDataChanged($userID) {       
        // Need to include connection as a SEPARATE php file - 
        $connection = mysqli_connect('localhost', 'root', '','dummy') or die(mysqli_error()); 
        if (mysqli_connect_errno()){
            echo 'Failed to connect to the MySQL server: '. mysqli_connect_error();
        }
        else {
            echo "Successfully connected to server"."<br>";
        }

        // newEmail and newConfEmail
        if ((!isset($_POST['newEmail']) or trim($_POST['newEmail']) == '') and (!isset($_POST['newConfEmail']) or trim($_POST['newConfEmail']) == '')) {
            // Nothing entered for email
        }
        else {
            // Sanitise email input against SQL injection


            // Update database - email
            $newEmail = trim($_POST['newEmail']);
            $sql_Email = "UPDATE users SET email = '$newEmail' WHERE userID = ?";
            $result_Email = mysqli_prepare($connection, $sql_Email);
            mysqli_stmt_bind_param($result_Email, 's', $userID);
            mysqli_stmt_execute($result_Email);
            echo "Email Address has been modified in database"."<br>";
            return TRUE;
        }

        // newPassWord and newConfPassWord
        if ((!isset($_POST['newPassWord']) or trim($_POST['newPassWord']) == '') and (!isset($_POST['newConfPassWord']) or trim($_POST['newConfPassWord']) == '')) {
            // Nothing entered for passwords
        }
        else {
            // Sanitise password input against SQL injection - TO BE ADDED

            // Update database - password1 - hashed
            $passwordFromPost = trim($_POST['newPassWord']);
            $hash = password_hash($passwordFromPost, PASSWORD_BCRYPT);
            $sql_Password = "UPDATE users SET password1 = '$hash' WHERE userID = ?";
            $result_Password = mysqli_prepare($connection, $sql_Password);
            mysqli_stmt_bind_param($result_Password, 's', $userID);
            mysqli_stmt_execute($result_Password);
            echo "Password has been modified in database"."<br>";
            return TRUE;
        }
        
        // newDOB
        if (!isset($_POST['newDOB']) or trim($_POST['newDOB']) == '') {
            // Nothing entered for DOB
        }
        else {
            // Update database - DOB
            $newDOB = trim($_POST['newDOB']);
            $sql_DOB = "UPDATE users SET DOB = '$newDOB' WHERE userID = ?";
            $result_DOB = mysqli_prepare($connection, $sql_DOB);
            mysqli_stmt_bind_param($result_DOB, 's', $userID);
            mysqli_stmt_execute($result_DOB);
            echo "Date of Birth has been modified in database"."<br>";
            return TRUE;
        }


        // upAccBalance
        if (!isset($_POST['upAccBalance']) or trim($_POST['upAccBalance']) == '') {
            // Nothing entered for accountBalance
        }
        else {
            // Update database - accountBalance
            $sql_currBalance = "SELECT accountbalance FROM users WHERE userID = ?";
            $result_currBalance = mysqli_prepare($connection, $sql_currBalance);
            mysqli_stmt_bind_param($result_currBalance, 's', $userID);
            mysqli_stmt_execute($result_currBalance);
            $result = mysqli_stmt_get_result($result_currBalance);
            $rows_users = ($result->num_rows);
            echo "connected to users" . "<br>";
        
            if ($rows_users == 1) {
                echo ($username . "'s current Account Balance has been found in users table" . "<br>"); // Delete this ########
                while($row = $result->fetch_assoc()) {
                    $currAccBalance = $row["accountbalance"]."<br>";
                    $upAccBalance = trim($_POST['upAccBalance']);
                    $sumBalance = ((int)$currAccBalance + (int)$upAccBalance);

                    $sql_upAccBalance = "UPDATE users SET accountbalance = $sumBalance WHERE userID = ?";
                    $result_AccBalance = mysqli_prepare($connection, $sql_upAccBalance);
                    mysqli_stmt_bind_param($result_AccBalance, 's', $userID);
                    mysqli_stmt_execute($result_AccBalance);
                    echo "Account Balance has been topped up in database"."<br>";
                    return TRUE;
                }
            }
        }

        // newPhoneNo
        if (!isset($_POST['newPhoneNo']) or trim($_POST['newPhoneNo']) == '') {
            // Nothing entered for PhoneNo
        }
        else {
            // Update database - phone
            $newPhoneNo = trim($_POST['newPhoneNo']);
            $sql_Phone = "UPDATE users SET phone = '$newPhoneNo' WHERE userID = ?";
            $result_Phone = mysqli_prepare($connection, $sql_Phone);
            mysqli_stmt_bind_param($result_Phone, 's', $userID);
            mysqli_stmt_execute($result_Phone);
            echo "Phone Number has been modified in database"."<br>";
            return TRUE;
        }

        echo "Data HAS NOT changed. Server-side validation passed"."<br>"; // Delete this ########
        return TRUE;
        }

    
    if (hasDataChanged($userID) == TRUE) {
        echo "Successfully updated database"."<br>";
    }
}
?>

<html lang="en">
    <head>
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
        <h3> Current User Details </h3>
        <label>Username:</label><br>
        <?php echo $username; ?><br><br>
        <label>Password:</label><br>
        <?php echo $password; ?><br>
        <label>Email:</label><br>
        <?php echo $email; ?><br>
        <label>Date of Birth:</label><br>
        <?php echo $DOB; ?><br>
        <label>Account Balance:</label><br>
        £ <?php echo $accountbalance; ?><br>
        <label>Phone Number:</label><br>
        <?php echo $phoneNo; ?><br>

        <h3> Modify User Details </h3>
        <form name="newDetails_form" action="" onsubmit="return validateForm()" method="post">
        <label for="email">New Email Address:</label><br>
        <input type="email" name="newEmail" placeholder="New Email">
        <br>
        <br>
        <label for="newConfEmail">Confirm New Email Address:</label><br>
        <input type="email" name="newConfEmail" placeholder="Re-Enter New Email">
        <br>
        <br>
        <label for="newPassWord">Password:</label><br>
        <input type="password" name="newPassWord" placeholder="New Password" pattern=".{6,}" title="Minimum 6 characters or more">
        <br>
        <br>
        <label for="newConfPassWord">Confirm Password:</label><br>
        <input type="password" name="newConfPassWord" placeholder="Re-Enter New Password" pattern=".{6,}" title="Minimum 6 characters or more">
        <br>
        <br>
        <label for="newDOB">Date of Birth:</label><br>
        <input type="date" name="newDOB">
        <br>
        <br>
        <label for="upAccBalance">Top-Up Account Balance:</label><br>
        £ <input type="number" name="upAccBalance" min="0" max="1000" step="25">
        <br>
        <br>
        <label for="newPhoneNo">Phone Number:</label><br>
        + <input type="text" name="newPhoneNo" pattern="(.*\d)" placeholder="New Phone Number" maxlength=15 title="Numeric characters only, up to 15 characters">
        <br>
        <br>
        <p><input type="submit" name="newUserDetails" value="Submit Changes"></p>
        <br>
        </form>

    </body>
</html>