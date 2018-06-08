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
?>
<head>
    <meta charset="utf-8" />
    <title>🍷Fine Greek Wines</title>
<link rel="stylesheet" type="text/css" href="mainPage.css" />
</head>
<body>
    <h1>Cart</h1>
<?php
$total = 0;
$total_with_discount = 0;
$sql = "SELECT * FROM cart WHERE id_customer='$id_customer'";
if (!$result = $conn->query($sql)) {
    echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
    exit();
} else if ($result->num_rows == 0) {
	echo "Empty!";
} else {
	echo "<ul>";
	while ($row = $result->fetch_assoc()) {
		$id_wine = $row["id_wine"];
		$amount = $row["amount"];
		$sql = "SELECT * FROM wines WHERE id_wine='$id_wine'";
		if (!$result2 = $conn->query($sql)) {
			echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
			exit();
		} else if ($result2->num_rows == 0) {
			echo "Error!";
			exit();
		}
		$row = $result2->fetch_assoc();
		echo "
			<li>
				<figure class='snip1268'>
					<div class='image'>
						<img src='../img/wines/" .$row["image"]. "'>
					</div>
					<figcaption>
						<h2>" .$row[name]. "</h2>
						<div class='price'>" .$amount. "x" .$row[price]. " €</div>
						<div class='price'>Total: " .$amount*$row[price]. " €</div>
					</figcaption>
				</figure>
			</li>
			";
		$total += $amount*$row[price];
	}
	echo "</ul>";
}
?>
	<form action='checkout.php' method='post'>
		Grand total: <?php 
		if (!$discount) {
			echo $total. " €";
		} else {
			echo $total. " € (-" .($discount*100). "%) = " .$total*(1-$discount). " €";
		}
		?><br>
		Amount to pay now: <input type='float' name='paynow'> €<br>
		<input type='hidden' name='username' value=<?php echo $username; ?>>
		<input type='hidden' name='password' value=<?php echo $password; ?>>
		<input type='submit' value='Check Out'>
	</form>
	<form action='logged.php' method='post'>
		<input type='hidden' name='username' value=<?php echo $username; ?>>
		<input type='hidden' name='password' value=<?php echo $password; ?>>
		<input type='submit' value='Return'>
	</form>
</body>
</html>