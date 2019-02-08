<?php include 'database.php';?>
<?php
$categoryID=01;

$sql="SELECT * FROM Product WHERE categoryID=$categoryID";
$result=$connection->query($sql);

echo "Result related to Electronics: <br>";

if ($result->num_rows>0){
    #create table header
    echo "<table><tr><th>product ID</th><th>product details</th><th>price</th></tr>";
    //output data of each row in table
    while($row=$result->fetch_assoc()){
        echo "<tr><td>". $row["productID"]."</td><td>".$row["product_description"] . "</td><td>".$row["price"]. "</td></tr>";
    }
    echo "</table>";
} else {
    echo "no result found";
}

$connection->close();

?>