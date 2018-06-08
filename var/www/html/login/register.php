<!DOCTYPE html>

<html>
<?php
$conn = new mysqli("localhost", "user", "12345678", "hy360project");
if ($conn->connect_errno) {
	echo "SQL connect error: $conn->connect_errno, $conn->connect_error. ";
	exit();
}
if (empty($username = $_POST["username"]) OR empty($password = $_POST["password"]) OR empty($email = $_POST["email"]) OR empty($phone_number = $_POST["phone_number"]) OR empty($address = $_POST["address"])) {
	echo "Must fill all fields! <br>";
	exit();
}

$wholesale = 0;
if ($_POST["wholesale"] == 'on') $wholesale = 1;

$sql = "INSERT INTO customers (username, password, wholesale, email, phone_number, address) VALUES ('$username', '$password', $wholesale, '$email', '$phone_number', '$address')";
if (!$result = $conn->query($sql)) {
	echo "Query failed! #" .$conn->errno. ": " .$conn->error. "<br>";
	exit();
}
$sql = "SELECT * FROM customers WHERE username='$username' && password='$password'";
if (!$result = $conn->query($sql)) {
	echo "Query failed! #" .$conn->errno. ": " .$conn->error. "<br>";
	exit();
}
$conn->close();
$row = $result->fetch_assoc();
$account_type = 'Retail';
if ($row["wholesale"]) $account_type = 'Wholesale';
?>
<head>
    <meta charset='utf-8' />
    <title>??Fine Greek Wines</title>
    <link rel='stylesheet' type='text/css' href='inBetween.css' />

</head>
<body>
    <header>You've successfully Registered!</header>

    <div class='container'>
		Account Created:<br>
		ID:<?php echo $row["id_customer"]; ?><br>
		Username:<?php echo $row["username"]; ?><br>
		Password:<?php echo $row["password"]; ?><br>
		Account Type: <?php echo $account_type; ?><br>
		Email:<?php echo $row["email"]; ?><br>
		Phone number:<?php echo $row["phone_number"]; ?><br>
		Address: <?php echo $address; ?><br>
        <div class='button-wrapper'>
            <div class='left'>
				<form action='../account/logged.php' method='post'>
					<input type='hidden' name='username' value=<?php echo $username; ?>> 
					<input type='hidden' name='password' value=<?php echo $password; ?>> 
					<input id='button' type='submit' value='Continue'><br>
				</form>
            </div>
            <div class='right'>
                <input id='button' type='button' value='Go back' onclick=location.href='../index.html'>
            </div>
        </div>
    </div>
</body>
</html>