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
$name = $_POST[name];
$price = $_POST[price];
$year = $_POST[year];
$color = $_POST[color];
$winery = $_POST[winery];
$image = $_POST[image];
$sql = "INSERT INTO wines (name, price, year, color, winery, image) VALUES ('$name', $price, $year, '$color', '$winery', '$image')";
if (!$result = $conn->query($sql)) {
	echo "Query failed! #" .$conn->errno. ": " .$conn->error. "<br>";
	exit();
}
?>
<input type='button' value='Return' onclick=location.href='manage.php'>
<?php
$conn->close();
?>
</html>