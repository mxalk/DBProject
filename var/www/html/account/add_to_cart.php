<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<?php
$conn = new mysqli("localhost", "user", "12345678", "hy360project");
if ($conn->connect_errno) {
    echo "SQL connect error: $conn->connect_errno, $conn->connect_error. ";
    exit();
}
if (empty($username = $_POST["username"]) OR empty($password = $_POST["password"])) {
    echo "Must fill both username and password! <br>";
    exit();
}
$sql = "SELECT * FROM customers WHERE username='$username' && password='$password'";
if (!$result = $conn->query($sql)) {
    echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
    exit();
} else if ($result->num_rows == 0) {
    echo "Username or password incorrect!<br>";
    exit();
}
$id_customer = $result->fetch_assoc()[id_customer];
$amount = $_POST[amount];
$id_wine = $_POST[id_wine];
$sql = "SELECT * FROM wines WHERE id_wine='$id_wine'";
if (!$result = $conn->query($sql)) {
    echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
    exit();
} else if ($result->num_rows == 0) {
    echo "Couldn't find wine in DataBase!<br>";
    exit();
}
$cost = $result->fetch_assoc()[price] * $amount;


$sql = "SELECT * FROM cart WHERE id_customer='$id_customer' && id_wine='$id_wine'";
if (!$result = $conn->query($sql)) {
    echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
    exit();
} else if ($result->num_rows == 0) {
    $sql = "INSERT INTO cart(id_customer, id_wine, amount, cost) VALUES ($id_customer, $id_wine, $amount, $cost)";
} else {
	$sql = "UPDATE cart SET amount=amount+$amount, cost=cost+$cost WHERE id_customer='$id_customer' && id_wine='$id_wine'";
}
if (!$result = $conn->query($sql)) {
    echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
    exit();
}
$conn->close();
?>
<head>
    <meta charset="utf-8" />
        <title>??Fine Greek Wines</title>
<link rel="stylesheet" type="text/css" href="mainPage.css" /></head>
<body>
 SUCCESS!
 	<form action='logged.php' method='post'>
		<input type='hidden' name='username' value=<?php echo $username; ?>>
		<input type='hidden' name='password' value=<?php echo $password; ?>>
		<input type='submit' value='Return'>
	</form>
</body>
</html>