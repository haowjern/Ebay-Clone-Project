<!DOCTYPE html PUBLIC>
<?php
session_start();

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

// Instantiate into session variable 
$_SESSION["allUsers"]=array();


// Store query result in array
if ($rows_users>0) {
    echo ("Users retrieved from users table" . "<br>"); // Delete this ########
    $all_users = array();
    
    //output data of each row in table
    while($row=$result->fetch_assoc()){
        $v=array();
        foreach ($row as $key => $value){
            $v[$key]=$value;
        }
        array_push($_SESSION["allUsers"],$v);
    }
}
else {
    echo "Cannot locate any users in users table of database";
}

// Session variable to convert into json_encode
$count = count($_SESSION["allUsers"]);
echo "<br>" . "Number of users found: $count"; // -- DELETE THIS #######
if ($count==0){
    echo "No users found";
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
    
</style>
    
    <title>Team 10 eBay - Manage Users</title>

    </head>
    <body>
        <div class="centered">
        <h3> All Current Users Details </h3>
        <p id="t"></p>
        </div>

        <!-- create table header -->
        <div class="centered">
        <table id="currUsers_table" width="device-width,initial-scale=1" >
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
        </div>

        <!-- Javascript to display json_encode into replicating HTML form -->
        <script>
        var count="<?php echo $count?>";
        document.getElementById("t").innerHTML = "Number of current users: "+count;
        //copy the php array into javascript array
        var each_listing=<?php echo json_encode($_SESSION['allUsers'],JSON_PRETTY_PRINT);?>;
        <?php unset($_SESSION["allUsers"]);?>;
        for (i=0;i<11;i++){
            //for each active user account, create a row at the bottomw of the table.
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
            // cell_userID.innerHTML=each_listing[i]["userID"];
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
            
            //insert Account Balance into the 5th column (accountbalance)
            cell_accBalance.style.textAlign="center";
            cell_accBalance.innerHTML=each_listing[i]["accountbalance"];
            
            //insert Date of Birth into the 6th column (DOB)
            cell_DOB.style.textAlign="center";
            cell_DOB.innerHTML=each_listing[i]["DOB"];
            
            //insert forms with delete buttons in 7th column (Delete?)
            cell_action.style.textAlign="center";

            //create the form to remove item
            var fm_remove=document.createElement('form');
            //name the form with userID
            fm_remove.setAttribute("name","form_remove"+each_listing[i]["userID"]);
            fm_remove.setAttribute("method","post");
            fm_remove.setAttribute("action","removeUser.php");
            var hiddenField_remove=document.createElement("input");
            hiddenField_remove.setAttribute("type","hidden")
            hiddenField_remove.setAttribute("name","userID");
            hiddenField_remove.setAttribute("value",each_listing[i]["userID"]);
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
            return confirm("Please confirm removal of this user.");
        }
    </script>

    </body>
</html>