<!DOCTYPE html>

<html lang='en' xmlns='http://www.w3.org/1999/xhtml'>
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
$criteria1 = $_POST[criteria1];
$criteria2 = $_POST[criteria2];
if (!isset($_POST[criteria1])) $criteria1 = "sold";
if (!isset($_POST[criteria2])) $criteria2 = "DESC";
?>
<head>
    <meta charset='utf-8' />
    <title>🍷Fine Greek Wines</title>
    <link rel="stylesheet" type="text/css" href="mainPage.css" />
    <link href='//netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css' rel='stylesheet'>
    <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js'></script>
</head>
<body>
    <header><h1>Welcome, <?php echo $username; ?>!</h1></header>
	<nav>
        <div class='dropdown'>
            <ul>
                <li class='menu'>
                    <a href='#'><img src='img/menu.jpg' style='width:40px;height:40px;' /></a>
                    <ul>
						<li>
							<form action='logged.php' method='post'>
								<input type='hidden' name='username' value=<?php echo $username; ?>>
								<input type='hidden' name='password' value=<?php echo $password; ?>>
								<input type='submit' value='Main Page'>
							</form>
						</li>
                        <li>
							<form action='viewUserInfo.php' method='post'>
								<input type='hidden' name='username' value=<?php echo $username; ?>>
								<input type='hidden' name='password' value=<?php echo $password; ?>>
								<input type='submit' value='User Info'>
							</form>
						</li>
						<li>
							<form action='transactions.php' method='post'>
								<input type='hidden' name='username' value=<?php echo $username; ?>>
								<input type='hidden' name='password' value=<?php echo $password; ?>>
								<input type='submit' value='Transactions'>
							</form>
						</li>
                        <li>
							<input type='button' value='Logout' onclick=location.href='../index.html'>
						</li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class='cart'>
            <form action='cart.php' method='post'>
                <input type='hidden' name='username' value=<?php echo $username; ?>>
                <input type='hidden' name='password' value=<?php echo $password; ?>>
                <input type='submit' value=' '>
            </form>
        </div>
	</nav>

	<br><br>
	<h2>View our hottest products now!<br>
	Based on <form action='hot_products.php' method='post'>
			<select name="criteria">
				<option value="sold">Overall sells</option>
				<option value="year">Year</option>
				<option value="color">Color</option>
				<option value="winery">Winery</option>
			</select>
			<input type='hidden' name='username' value=<?php echo $username; ?>>
			<input type='hidden' name='password' value=<?php echo $password; ?>>
			<input type='submit' value='GO!'>
		</form>
	</h2>
	
	<div id='mainContent'>
		<h1> List Of Wines</h1>
		Sort By:
		<form action='logged.php' method='post'>
			<select name="criteria1">
				<option value="sold">Popularity</option>
				<option value="name">Name</option>
				<option value="price">Price</option>
				<option value="year">Year</option>
				<option value="color">Color</option>
				<option value="winery">Winery</option>
			</select>
			<select name="criteria2">
				<option value="DESC">Descending</option>
				<option value="ASC">Ascending</option>
			</select>
			<input type='hidden' name='username' value=<?php echo $username; ?>>
			<input type='hidden' name='password' value=<?php echo $password; ?>>
			<input type='submit' value='GO!'>
		</form>
        <ul class='products'>
			<?php
			$sql = "SELECT * FROM wines ORDER BY $criteria1 $criteria2";
			if (!$result = $conn->query($sql)) {
				echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
				exit();
			}
			while ($row = $result->fetch_assoc()) {
				echo "
				<li>
				<figure class='snip1268'>
					<img src='../img/wines/" .$row[image]. "'>
					<h2>" .$row[name]. "</h2>
					<p>" .$row[year]. "</p>
					<p>" .$row[color]. "</p>
					<p>" .$row[winery]. "</p>
					<div class='price'>" .$row[price]. " €</div>
					<form action='wine_of_choice.php' method='post'>
						<input type='hidden' name='username' value=" .$username. ">
						<input type='hidden' name='password' value=" .$password. ">
						<input type='hidden' name='id_wine' value=" .$row[id_wine]. ">
						<input type='submit' id='disOne' value='Buy'>
					</form>
				</figure>
				</li>
				";
			}
			?>
		</ul>
	</div>

    <footer>

    </footer>
</body>
<?php
$conn->close();
?>
</html>