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
$row = $result->fetch_assoc();
$id_customer = $row[id_customer];
$balance = $row[balance];
$paid = $_POST[paid];
$id_transaction=$_POST[id_transaction];
$sql = "SELECT * FROM transactions WHERE id_transaction='$id_transaction' && id_customer=$id_customer";
if (!$result = $conn->query($sql)) {
    echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
    exit();
}
$row = $result->fetch_assoc();
if ($paid>$row[cost]-$row[paid]) $paid = $row[cost]-$row[paid];

?>
<head>
    <meta charset="utf-8" />
        <title>??Fine Greek Wines</title>
<link rel="stylesheet" type="text/css" href="mainPage.css" />
</head>
<body>
    <h1>Pay Off</h1>
	<?php
		if ($paid > $balance) {
			echo "Not enough money in account!";
			exit();
		}
		if ($paid) {
			$sql = "UPDATE transactions SET paid=paid+$paid WHERE id_transaction='$id_transaction'";
			if (!$result = $conn->query($sql)) {
				echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
				exit();
			}
			$sql = "UPDATE customers SET balance=balance-$paid WHERE id_customer='$id_customer'";
			if (!$result = $conn->query($sql)) {
				echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
				exit();
			}
		}
	?>
Payment Success!
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