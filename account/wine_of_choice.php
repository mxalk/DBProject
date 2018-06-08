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
$id_wine = $_POST[id_wine];
$sql = "SELECT * FROM wines WHERE id_wine='$id_wine'";
if (!$result = $conn->query($sql)) {
    echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
    exit();
} else if ($result->num_rows == 0) {
    echo "Couldn't find wine in DataBase!<br>";
    exit();
}
$wine = $result->fetch_assoc();
$conn->close();
?>
<head>
    <meta charset="utf-8" />
       <title>??Fine Greek Wines</title>
<link rel="stylesheet" type="text/css" href="mainPage.css" />
</head>
<body>
    <h1>You clicked on:</h1>
	<img src='../img/wines/<?php echo $wine[image]; ?>'>
	<h2>Name: <?php echo $wine[name]; ?></h2>
	<h3>Year: <?php echo $wine[year]; ?></h3>
	<h3>Color: <?php echo $wine[color]; ?></h3>
	<h3>Winery: <?php echo $wine[winery]; ?></h3>
	<p>Sold: <?php echo $wine[sold]; ?></p>
	<p>Wine ID: <?php echo $wine[id_wine]; ?></p>
	<h2><?php echo $wine[price]; ?> €</h2>
	How many do you want? 
	<form action='add_to_cart.php' method='post'>
		<input type='number' name='amount'>
		<input type='hidden' name='id_wine' value=<?php echo $wine[id_wine]; ?>>
		<input type='hidden' name='username' value=<?php echo $username; ?>>
		<input type='hidden' name='password' value=<?php echo $password; ?>>
		<input type='submit' value='Add to Cart'>
	</form>
</body>
</html>