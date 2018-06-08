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
$id_customer = $row[id_customer];
$account_type = 'Retail';
if ($row[wholesale]) $account_type = 'Wholesale';
$discount = 0;
if ($row[wholesale]) {
	$sql = "SELECT SUM(paid) AS disc FROM transactions WHERE id_customer=$id_customer";
	if (!$result = $conn->query($sql)) {
		echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
		exit();
	}
	$total_paid = $result->fetch_assoc()[disc];
	if ($total_paid > 600) {
		$discount = 0.15;	
	} else if ($total_paid > 300) {
		$discount = 0.1;	
	} else if ($total_paid > 100) {
		$discount = 0.05;
	}
}
$conn->close();
?>
<form action='updateUserInfo.php' method='post'>
	ID: <?php echo $row["id_customer"]; ?><br>
	<br>
	Username: <?php echo $row[username]; ?><br>
	NEW Username <input type='text' name='new_username'><br>
	<br>
	Password: <?php echo $row[password]; ?><br>
	NEW Password <input type='password' name='new_password'><br>
	<br>
	Account Type: <?php echo $account_type; ?><br>
	<?php  if ($discount) echo "Discount: " .($discount*100). "%<br>"; ?>
	<br>
	Balance: <?php echo $row[balance]; ?> €<br>
	<br>
	Email:  <?php echo $row[email]; ?><br>
	NEW Email <input type='text' name='new_email'><br>
	<br>
	Phone Number: <?php echo $row[phone_number]; ?><br>
	NEW Phone Number <input type='text' name='new_phone_number'><br>
	<br>
	Address: <?php echo $row[address]; ?><br>
	NEW Address <input type='text' name='new_address'><br>
	<br>
	<input type='hidden' name='username' value=<?php echo $username; ?>>
	<input type='hidden' name='password' value=<?php echo $password; ?>>
	<input type='submit' value='Change'>
</form>
<form action='delete.php' method='post'>
	<input type='checkbox' required>
	<input type='checkbox' required>
	<input type='checkbox' required>
	<input type='hidden' name='username' value=<?php echo $username; ?>>
	<input type='hidden' name='password' value=<?php echo $password; ?>>
	<input type='submit' value='DELETE ACCOUNT'>
</form>
<br>
<form action='viewUserInfo.php' method='post'>
	<input type='hidden' name='username' value=<?php echo $username; ?>>
	<input type='hidden' name='password' value=<?php echo $password; ?>>
	<input type='submit' value='Refresh'>
</form>
<form action='logged.php' method='post'>
	<input type='hidden' name='username' value=<?php echo $username; ?>>
	<input type='hidden' name='password' value=<?php echo $password; ?>>
	<input type='submit' value='Return'>
</form>
</body>
</html>
