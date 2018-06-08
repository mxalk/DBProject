<!DOCTYPE html>

<html lang='en' xmlns='http://www.w3.org/1999/xhtml'>
<head>
    <meta charset='utf-8' />
        <title>??Fine Greek Wines</title>
<link rel="stylesheet" type="text/css" href="mainPage.css" />
</head>
<body>
    <h1>Delete Account</h1>
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
$id_customer=$result->fetch_assoc()[id_customer];
$sql = "SELECT * FROM transactions WHERE id_customer=$id_customer && paid!=cost";
	if (!$result = $conn->query($sql)) {
	 	echo "SQL connect error: ";
		exit();
	} else if ($result->num_rows > 0) {
		echo "You have unpaid transactions.You cannot delete this account. <br>";
		exit();
	}
$sql = "DELETE FROM customers WHERE username='$username' && password='$password'";
if (!$result = $conn->query($sql)) {
	 	echo "SQL connect error: ";
		exit();
	}
?>
</body>
</html>
