<!DOCTYPE html>

<html lang='en' xmlns='http://www.w3.org/1999/xhtml'>
<head>
    <meta charset='utf-8' />
     <title>🍷Fine Greek Wines</title>
<link rel="stylesheet" type="text/css" href="mainPage.css" />
</head>
<body>
    <h1>User Info</h1>
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
$row = $result->fetch_assoc();
$sql = "UPDATE customers SET ";
$count = 0;

$new_username = $_POST["new_username"];
$new_password = $_POST["new_password"];
$new_email = $_POST["new_email"];
$new_phone_number = $_POST["new_phone_number"];
$new_address = $_POST["new_address"];

if ($new_username != "") {
	echo "Username Updated<br>";
	$sql .= "username = '$new_username'";
	$count++;
}
if ($new_password != "") {
	echo "Password Updated<br>";
	if ($count) $sql .= ", ";
	$sql .= "password = '$new_password'";
	$count++;
}
if ($new_email != "") {
	echo "Email Updated<br>";
	if ($count) $sql .= ", ";
	$sql .= "email = '$new_email'";
	$count++;
}
if ($new_phone_number != "") {
	echo "Phone Number Updated<br>";
	if ($count) $sql .= ", ";
	$sql .= "phone_number = '$new_phone_number'";
	$count++;
}
if ($new_address != "") {
	echo "Address Updated<br>";
	if ($count) $sql .= ", ";
	$sql .= "address = '$new_address'";
	$count++;
}
if ($count != 0) {
	$sql .= " WHERE username='$username' && password='$password'";
	if (!$result = $conn->query($sql)) {
		echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
		exit();
	}
} else echo "Nothing Updated!<br>";
$conn->close();
?>
<br>
<form action='viewUserInfo.php' method='post'>
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