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
?>
<head>
    <meta charset='utf-8' />
    <title>🍷Fine Greek Wines</title>
    <link rel="stylesheet" type="text/css" href="mainPage.css" />
    <link href='//netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css' rel='stylesheet'>
    <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js'></script>
</head>
<body>
    <header><h2>Welcome, <?php echo $username; ?>!</h2></header>
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
                <input type='submit' value='Cart'>
            </form>
        </div>
	</nav>
    <h1>Highly Rated wines</h1>
	<div id='mainContent'>
		<h1> List Of Wines</h1>
        <ul class='products'>
			<?php
			$criteria = $_POST[criteria];
			$sql = "SELECT * FROM wines ORDER BY sold DESC";
			if ($criteria != 'sold') $sql = "SELECT * FROM wines ORDER BY $criteria ASC, sold DESC";
			if (!$result = $conn->query($sql)) {
					echo "Query failed! #" . $conn->errno . ": " . $conn->error . "<br>";
					exit();
			}
			$val = "";
			$times = 0;
			$row = $result->fetch_assoc();
			while ($row = $result->fetch_assoc()) {
				if ($val != $row[$criteria]) {
					$val = $row[$criteria];
					$times = 0;
					echo "<h3>" .$row[$criteria]. ": </h3>";
				}
				$times += 1;
				if ($times <= 3) {
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
								<input type='submit' id='disOne' value='Dis one'>
							</form>
						</figure>
						</li>
					";
				}
			}
			?>
		</ul>
	</div>
</body>
<?php
$conn->close();
?>
</html>