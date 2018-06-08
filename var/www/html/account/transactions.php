<!DOCTYPE html>

<html lang='en' xmlns='http://www.w3.org/1999/xhtml'>
<style>
    tab { padding-left: 2em; }
</style>
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
$row = $result->fetch_assoc();
$id_customer = $row[id_customer];
echo "Balance: " .$row[balance]. " €";

echo "<h2>Pending Transactions</h2>";
$sql = "SELECT * FROM transactions WHERE id_customer='$id_customer' && cost!=paid  ORDER BY timestamp DESC";
if (!$result_transaction = $conn->query($sql)) {
    echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
    exit();
} else if ($result_transaction->num_rows == 0) {
    echo "None!<br>";
} else while ($row_transaction = $result_transaction->fetch_assoc()) {
	$id_transaction = $row_transaction[id_transaction];
	$remaining = $row_transaction[cost]-$row_transaction[paid];
	echo "Transaction ID: #" .$id_transaction. " on " .$row_transaction[timestamp]. " Cost: " . $row_transaction[cost]. " €<br>
		Paid: " .$row_transaction[paid]. " €<br>
		Remaining: " .$remaining. " €<br>";
	$sql = "SELECT * FROM orders WHERE id_transaction='$id_transaction'";
	if (!$result_order = $conn->query($sql)) {
		echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
		exit();
	}
	while ($row_order = $result_order->fetch_assoc()) {
		$sql = "SELECT * FROM wines WHERE id_wine='$row_order[id_wine]'";
		if (!$result_wine = $conn->query($sql)) {
			echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
			exit();
		}
		$row_wine = $result_wine->fetch_assoc();
		echo "<tab>" .$row_wine[name]. " " .$row_wine[color] ." ". $row_wine[year]. "<br>
			<tab><tab>Price: " .$row_wine[price]. " € x " .$row_order[amount]. " = " .$row_wine[price]*$row_order[amount]. " €<br>";
	}
	echo "
		<form action='payoff.php' method='post'>
			<input type='float' name='paid' required>
			<input type='hidden' name='id_transaction' value='$id_transaction'>
			<input type='hidden' name='username' value='$username'>
			<input type='hidden' name='password' value='$password'>
			<input type='submit' value='Pay now'>
		</form>
		";
}

echo "<h2>Transaction History</h2>";
$sql = "SELECT * FROM transactions WHERE id_customer='$id_customer' ORDER BY timestamp DESC";
if (!$result_transaction = $conn->query($sql)) {
    echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
    exit();
} else if ($result_transaction->num_rows == 0) {
    echo "Empty!<br>";
} else while ($row_transaction = $result_transaction->fetch_assoc()) {
	$id_transaction = $row_transaction[id_transaction];
	echo "Transaction ID: #" .$id_transaction. " on " .$row_transaction[timestamp]. " Cost: " . $row_transaction[cost]. " € 
		<form action='refund.php' method='post'>
			<input type='hidden' name='username' value=" .$username. ">
			<input type='hidden' name='password' value=" .$password. ">
			<input type='hidden' name='id_transaction' value=" .$id_transaction. ">
			<input type='submit' value='Refund'>
		</form>
	";
	$sql = "SELECT * FROM orders WHERE id_transaction='$id_transaction'";
	if (!$result_order = $conn->query($sql)) {
		echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
		exit();
	}
	while ($row_order = $result_order->fetch_assoc()) {
		$sql = "SELECT * FROM wines WHERE id_wine='$row_order[id_wine]'";
		if (!$result_wine = $conn->query($sql)) {
			echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
			exit();
		}
		$row_wine = $result_wine->fetch_assoc();
		echo "<tab>" .$row_wine[name]. " " .$row_wine[color] ." ". $row_wine[year]. "<br>
			<tab><tab>Price: " .$row_wine[price]. " € x " .$row_order[amount]. " = " .$row_wine[price]*$row_order[amount]. " €<br>";
	}
}
$conn->close();
?>
<br>
<form action='transactions.php' method='post'>
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