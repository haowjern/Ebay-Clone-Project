<?php 
// Admin to delete users - need DYNAMIC Javascript from Annie

// Manage users link on page redirects to this
// Display all userIDs from users table in database
// Admin to delete users by clicking on button

// javascript to create form
// form per row
// delete button in form 

// datatables.net
// jquery to filter and sort the data intable

// json_encode to send from php to javascript


?>

<!DOCTYPE html PUBLIC>
<?php
session_start();
?>

<?php
// Confirm adminID from SESSION variables
$_SESSION["adminID"] = 1; // this is fixed for example only - DELETE
$_SESSION["username"] = 'admin1'; // this is fixed for example only - DELETE
$adminID = $_SESSION["adminID"];
$username = $_SESSION["username"];
echo $adminID ."<br>";
echo $username ."<br>";
echo "Successfully retrieved userID from SESSION variables";

// Connect to database
$connection = mysqli_connect('localhost', 'root', '','dummy') or die(mysqli_error()); 
if (mysqli_connect_errno()){
    echo 'Failed to connect to the MySQL server: '. mysqli_connect_error();
}
else {
    echo "Successfully connected to server"."<br>";
}

// Query to fetch all users rows from users table
$sql_all_users = "SELECT * FROM users";
$result_users = mysqli_prepare($connection, $sql_all_users);
mysqli_stmt_execute($result_users);
$result = mysqli_stmt_get_result($result_users);
$rows_users = ($result->num_rows);
echo "connected to users" . "<br>";

// Store query result in array
if ($rows_users>0) {
    echo ("Users retrieved from users table" . "<br>"); // Delete this ########
    $all_users = array();
    
    while($row = $result->fetch_assoc()) {
        $userID = $row["userID"];
        $username = $row["username"];
        $password = $row["password1"];
        $email = $row["email"];
        $DOB = $row["DOB"];
        $accountbalance = $row["accountbalance"];
        $phoneNo = $row["phone"];

        // create key : value pair for each row, store in array
        ${$userID} = array($username, $password, $email, $phoneNo, $accountbalance, $DOB);
        array_push($all_users, ${$userID});
    }
    
    // Check -- print out all keys
    foreach($all_users as $key => $val) {
        print($key)."<br>"; 
        print_r($val)."<br>";
    }
}
else {
    echo "Cannot locate any users in users table of database";
}

// Instantiate into session variable 
$_SESSION["allUsers"]=$all_users;

// Session variable to convert into json_encode
$count = count($_SESSION["allUsers"]);
echo "<br>" . "Number of users found: $count";
if ($count==0){
    echo "No users found";
}
?>

<html lang="en">
    <head>
    
    <title>Team 10 eBay - Manage Users</title>

    </head>
    <body>
        <h3> All Current Users Details </h3>
        <!-- <button onclick="window.location.href = '<?php echo $link_newlisting; ?>'">Create new listing</button> -->
        <p id="t"></p>


        <!-- create table header -->
        <table id=currUsers_table width="device-width,initial-scale=1">
            <tr>
                <th>UserID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Account Balance</th>
                <th>DOB</th>
                <th>Delete?</th>
            </tr>   
        </table>

        <!-- Javascript to display json_encode into replicating HTML form -->
        <!-- ##### How to delete entry if delete button clicked??? How do I capture in javascript? -->
        <script language='javascript' type='text/javascript'>
        var count="Number of users: <?php echo $count?>";
        document.getElementById("t").innerHTML = "No. of current users: "+count;
        //copy the php array into javascript array
        var each_listing=<?php echo json_encode($_SESSION['allUsers'],JSON_PRETTY_PRINT)?>;
        <?php unset($_SESSION["allUsers"]);?>;
        for (i=0;i<count;i++){
            //for each active listing, create a row at the bottomw of the table.
            var table=document.getElementById("currUsers_table");
            var row=table.insertRow(-1);
            var cell_userID=row.insertCell(0);
            var cell_username=row.insertCell(1);
            var cell_email=row.insertCell(2);
            var cell_phone=row.insertCell(3);
            var cell_accBalance=row.insertCell(4);
            var cell_DOB=row.insertCell(5);
            var cell_action=row.insertCell(6);
         
            //insert UserID iin the 1st column (userID)
            cell_userID.style.textAlign="center";
            cell_userID.innerHTML=each_listing[i]["userID"];
            
            //insert Username into the 2nd column (username)
            cell_username.style.textAlign="center";
            cell_username.innerHTML=each_listing[i]["username"];
            
            //insert Email into the 3rd column (email)
            cell_email.style.textAlign="center";
            cell_email.innerHTML=each_listing[i]["email"];
            
            //insert Phone Number into the 4th column (Phone)
            cell_phone.style.textAlign="center";
            cell_phone.innerHTML=each_listing[i]["phone"];
            
            //insert Account Balance into the 5th column (Account Balance)
            cell_accBalance.style.textAlign="center";
            cell_accBalance.innerHTML=each_listing[i]["accountbalance"];
            
            //insert Date of Birth into the 6th column (DOB)
            cell_DOB.style.textAlign="center";
            cell_DOB.innerHTML=each_listing[i]["DOB"];
            
            //insert forms with delete buttons in 7th column (Delete?)
            cell_action.style.textAlign="center";


            // Annie what happens here? I'm not able to create the form???


            //create the form to edit item
            var fm_edit=document.createElement('form');
            //name the form with productID
            fm_edit.setAttribute("name","form_edit"+each_listing[i]["productID"]);
            fm_edit.setAttribute("method","post");
            // fm_edit.setAttribute("action","editlisting.php"); -- DO I NEED THIS? Just want to delete, give alert to confirm deletion and refresh the page?
            for (var index in each_listing[i]){
                var hiddenField=document.createElement("input");
                hiddenField.setAttribute("type","hidden");
                hiddenField.setAttribute("name",index);
                hiddenField.setAttribute("value",each_listing[i][index]);
                fm_edit.appendChild(hiddenField);
            }
            var edit_button=document.createElement("input");
            edit_button.setAttribute("type","submit");
            edit_button.setAttribute("value","edit");
            fm_edit.appendChild(edit_button);
            cell_action.appendChild(fm_edit);
            //create the form to remove item
            var fm_remove=document.createElement('form');
            //name the form with productID
            fm_remove.setAttribute("name","form_remove"+each_listing[i]["productID"]);
            fm_remove.setAttribute("method","post");
            fm_remove.setAttribute("action","removelisting.php");
            var hiddenField_remove=document.createElement("input");
                hiddenField_remove.setAttribute("type","hidden");
                hiddenField_remove.setAttribute("name","productID");
                hiddenField_remove.setAttribute("value",each_listing[i]["productID"]);
                fm_remove.appendChild(hiddenField_remove);
            var remove_button=document.createElement("input");
            remove_button.setAttribute("type","submit");
            remove_button.setAttribute("value","remove");
            //alert pop up prior to deletion
            remove_button.setAttribute("onclick","return warning_msg();");
            fm_remove.appendChild(remove_button);
            cell_action.appendChild(fm_remove);
            
        }
        function warning_msg(){
            return confirm("confirm remove this listing?");
            }
    


    </body>
</html>