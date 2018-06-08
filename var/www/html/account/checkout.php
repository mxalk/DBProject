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
$wholesale = $row[wholesale];
$paid = $_POST[paynow];
$discount = 0;
if ($row[wholesale]) {
	$sql = "SELECT SUM(paid) AS disc FROM transactions WHERE id_customer=$id_customer";
	if (!$result = $conn->query($sql)) {
		echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
		exit();
	}
	$total_paid = $result->fetch_assoc()[paid];
	if ($total_paid > 600) {
		$discount = 0.15;	
	} else if ($total_paid > 300) {
		$discount = 0.1;	
	} else if ($total_paid > 100) {
		$discount = 0.05;
	}
}
?>
<head>
    <meta charset="utf-8" />
        <title>??Fine Greek Wines</title>
<link rel="stylesheet" type="text/css" href="mainPage.css" />
</head>
<body>
    <h1>Check Out</h1>
	<?php
/* calculate cost */
		$sql = "SELECT SUM(cost) AS total FROM cart WHERE id_customer='$id_customer'";
		if (!$result = $conn->query($sql)) {
			echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
			exit();
		}
		$total = $result->fetch_assoc()[total];
		if (!$total) {
			echo "Cart Empty!";
			exit();
		}
		$total = $total*(1-$discount);
		if ($paid > $total) $paid = $total;
		if ($paid > $balance) {
			echo "Not enough money in account!";
			exit();
		}
		/* is checkout possible? */
		if (!$wholesale) {
			$sql = "SELECT * FROM transactions WHERE id_customer='$id_customer' && paid!=cost";
			if (!$result = $conn->query($sql)) {
				echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
				exit();
			} else if ($result->num_rows > 0) {
				echo "You have unpaid transactions! Cannot continue. <br>";
				exit();
			}
		} else {
			$sql = "SELECT SUM(amount) AS total FROM cart WHERE id_customer='$id_customer'";
			if (!$result = $conn->query($sql)) {
				echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
				exit();
			} else if ($result->fetch_assoc()[total] < 6) {
				echo "As a wholesale account type, you have to buy at least 3 distinct types with a total of at least 6 items. <br>";
				exit();
			}
			$sql = "SELECT DISTINCT id_wine FROM cart WHERE id_customer='$id_customer'";
			if (!$result = $conn->query($sql)) {
				echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
				exit();
			} else if ($result->num_rows < 3) {
				echo "As a wholesale account type, you have to buy at least 3 distinct types with a total of at least 6 items. <br>";
				exit();
			}
		}
/* create transaction */
		$sql = "INSERT INTO transactions(id_customer, cost, paid) VALUES('$id_customer', '$total', '$paid')";
		if (!$conn->query($sql)) {
			echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
			exit();
		}
/* remove paid from balance */
		if ($paid) {
			$sql = "UPDATE customers SET balance=balance-$paid WHERE username='$username' && password='$password'";
			if (!$conn->query($sql)) {
				echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
				exit();
			}
		}
/* get transaction id */
		$sql = "SELECT id_transaction FROM transactions WHERE id_customer='$id_customer' ORDER BY timestamp DESC LIMIT 1";
		if (!$result = $conn->query($sql)) {
			echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
			exit();
		} else if ($result->num_rows == 0) {
			echo "Error has occured!<br>";
			exit();
		}
		$id_transaction = $result->fetch_assoc()[id_transaction];
/* create orders */
	/* retrieve cart items */
		$sql = "SELECT * FROM cart WHERE id_customer='$id_customer'";
		if (!$result = $conn->query($sql)) {
			echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
			exit();
		}
		while ($row = $result->fetch_assoc()) {
	/* create order */
			$sql = "INSERT INTO orders(id_transaction, id_wine, amount) VALUES ($id_transaction, $row[id_wine], $row[amount])";
			if (!$conn->query($sql)) {
				echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
				exit();
			}
	/* update sold wine */
			$sql = "UPDATE wines SET sold=sold+$row[amount] WHERE id_wine=$row[id_wine]";
			if (!$conn->query($sql)) {
				echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
				exit();
			}
		}
/* empty cart*/
		$sql = "DELETE FROM cart WHERE id_customer=$id_customer";
		if (!$conn->query($sql)) {
				echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
				exit();
			}
$conn->close();
	?>
	<form action='logged.php' method='post'>
		<input type='hidden' name='username' value=<?php echo $username; ?>>
		<input type='hidden' name='password' value=<?php echo $password; ?>>
		<input type='submit' value='Return'>
	</form>
</body>
</html>