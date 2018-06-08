<!DOCTYPE html>
<html>
<head>
    <title>Management</title>
</head>

<?php
$conn = new mysqli("localhost", "user", "12345678", "hy360project");
if ($conn->connect_errno) {
    echo "SQL connect error: $conn->connect_errno, $conn->connect_error. ";
    exit;
}
$sql = $_POST["query"];
echo "QUERY: " .$sql. "<br>";
if ($result = $conn->query($sql)) {
	echo "Querry Success!";
} else echo "Query failed! #$conn->errno: $conn->error<br>";
?>
<br>
<input type="button" value="Return" onclick="location.href='manage.php'">
<?php
$conn->close();
?>
</html>