<!DOCTYPE html>

<html lang='en' xmlns='http://www.w3.org/1999/xhtml'>
<head>
    <meta charset='utf-8' />
        <title>??Fine Greek Wines</title>
<link rel="stylesheet" type="text/css" href="mainPage.css" />
</head>
<body>
    <h1>Transactions</h1>
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
} else if ($result->num_rows > 1) {
    echo "Username error! Contact to fix!<br>";
    exit();
}
$id_customer = $result->fetch_assoc()[id_customer];

$id_transaction = $_POST[id_transaction];
$sql = "SELECT * FROM transactions WHERE id_transaction='$id_transaction' && id_customer=$id_customer";
if (!$result = $conn->query($sql)) {
    echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
    exit();
} else if ($result->num_rows == 0) {
    echo "Dont try to cheat.<br>";
    exit();
}
$transaction = $result->fetch_assoc();
$sql = "UPDATE customers SET balance=balance+$transaction[paid] WHERE id_customer=$id_customer";
if (!$conn->query($sql)) {
	echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
	exit();
}
$sql = "DELETE FROM transactions WHERE id_transaction=$id_transaction";
if (!$conn->query($sql)) {
	echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
	exit();
}
$sql = "SELECT * FROM orders WHERE id_transaction=$id_transaction";
if (!$result = $conn->query($sql)) {
    echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
    exit();
}
$sql = "DELETE FROM orders WHERE id_transaction=$id_transaction";
if (!$conn->query($sql)) {
	echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
	exit();
}
while ($row = $result->fetch_assoc()) {
	$sql = "UPDATE wines SET sold=sold-$row[amount] WHERE id_wine=$row[id_wine]";
	if (!$conn->query($sql)) {
		echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
		exit();
	}
}
$conn->close();
?>
Refund Success!
<form action='transactions.php' method='post'>
	<input type='hidden' name='username' value=<?php echo $username; ?>>
	<input type='hidden' name='password' value=<?php echo $password; ?>>
	<input type='submit' value='Back'>
</form>
<form action='logged.php' method='post'>
	<input type='hidden' name='username' value=<?php echo $username; ?>>
	<input type='hidden' name='password' value=<?php echo $password; ?>>
	<input type='submit' value='Return'>
</form>
</body>
</html>