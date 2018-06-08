<!DOCTYPE html>
<html>
<style>
    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
    }

    th, td {
        padding: 5px;
        text-align: left;
    }
</style>
<head>
    <title>Management</title>
</head>

<?php
$conn = new mysqli("localhost", "user", "12345678", "hy360project");
if ($conn->connect_errno) {
    echo "SQL connect error: $conn->connect_errno, $conn->connect_error. ";
    exit;
}
?>
<h1>ADMIN MENU</h1>

<h2>Direct SQL Instruction</h2>
<form action='execute.php' method='post'>
	<textarea cols='100' rows='1' placeholder='QUERY' name='query' required></textarea>
    <input type='submit' value='Execute'>
</form>

<h2>Wines</h2>
<?php
$sql = "SELECT * FROM wines";
if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        echo "<table style='width:100%'> <caption>Wines List</caption> <th>ID (id_wine)</th> <th>Name (name)</th> <th>Price (price)</th> <th>Year (year)</th> <th>Color (color)</th> <th>Winery (winery)</th> <th>Image Name (image)</th> <th>Sold (sold)</th> <th>Delete</th>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
					<td>" . $row[id_wine] . "</td>
					<td>" . $row[name] . "</td>
					<td>" . $row[price] . "</td>
					<td>" . $row[year] . "</td>
					<td>" . $row[color] . "</td>
					<td>" . $row[winery] . "</td>
					<td>" . $row[image] . "</td>
					<td>" . $row[sold] . "</td>
					<td>
						<form action='execute.php' method='post'>
							<input type='hidden' name='query' value = 'DELETE FROM wines WHERE id_wine=" .$row[id_wine]. "'>
							<input type='submit' value=''>
						</form>
					</td>
				</tr>";
        }
		echo "</table>";
    } else echo "EMPTY!<br>";
} else echo "Query failed! #$conn->errno: $conn->error<br>";
?>
<form action='execute.php' method='post'>
	<input type='checkbox' required>
	<input type='checkbox' required>
	<input type='checkbox' required>
	<input type='hidden' name='query' value = 'DELETE FROM wines'>
    <input type='submit' value='DELETE ALL'>
</form>
<form action='add_wine.php' method='post'>
	<input type='text' placeholder='Name' name='name' required>
	<input type='float' placeholder='Price' name='price' required>
	<input type='number' placeholder='Year' name='year' required>
	<input type='text' placeholder='Color' name='color' required>
	<input type='text' placeholder='Winery' name='winery' required>
	<input type='text' placeholder='Image Name' name='image' required>
    <input type='submit' value='Add Wine'>
</form>

<h2>Customers</h2>
<?php
$sql = "SELECT * FROM customers";
if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        echo "<table style='width:100%'> <caption>Customers List</caption> <th>ID (id_customer)</th> <th>Username (username)</th> <th>Password (password)</th> <th>Balance (balance)</th> <th>Account Type (wholesale)</th> <th>Email (email)</th> <th>Phone Number (phone_number)</th> <th>Address (address)</th> <th>Delete</th>";
        while ($row = $result->fetch_assoc()) {
            $account_type = 'Retail';
            if ($row["wholesale"]) $account_type = 'Wholesale';
            echo "<tr>
					<td>" . $row[id_customer] . "</td>
					<td>" . $row[username] . "</td>
					<td>" . $row[password] . "</td>
					<td>" . $row[balance] . " €</td>
					<td>" . $account_type . "</td>
					<td>" . $row[email] . "</td>
					<td>" . $row[phone_number] . "</td>
					<td>" . $row[address] . "</td>
					<td>
						<form action='execute.php' method='post'>
							<input type='hidden' name='query' value = 'DELETE FROM customers WHERE id_customer=" .$row[id_customer]. "'>
							<input type='submit' value=''>
						</form>
					</td>
				</tr>";
        }
    } else {
        echo "EMPTY!<br>";
    }
    echo "</table>";
} else echo "Query failed! #$conn->errno: $conn->error<br>";
?>
<form action='execute.php' method='post'>
	<input type='checkbox' required>
	<input type='checkbox' required>
	<input type='checkbox' required>
	<input type='hidden' name='query' value = 'DELETE FROM customers'>
    <input type='submit' value='DELETE ALL'>
</form>

<form action='good_customers.php' method='post'>
    <input type='submit' value='Good Customers'>
</form>
<form action='bad_customers.php' method='post'>
    <input type='submit' value='Bad Customers'>
</form>

<h2>Transactions</h2>
<?php
$sql = "SELECT * FROM transactions";
if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        echo "<table style='width:100%'> <caption>Transactions List</caption> <th>ID (id_transaction)</th> <th>ID (id_customer)</th> <th>Timestamp (timestamp)</th> <th>Cost (cost)</th> <th>Paid (paid)</th> <th>Delete</th>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
					<td>" . $row[id_transaction] . "</td>
					<td>" . $row[id_customer] . "</td>
					<td>" . $row[timestamp] . "</td>
					<td>" . $row[cost] . "</td>
					<td>" . $row[paid] . "</td>
					<td>
						<form action='execute.php' method='post'>
							<input type='hidden' name='query' value = 'DELETE FROM transactions WHERE id_transaction=" .$row[id_transaction]. "'>
							<input type='submit' value=''>
						</form>
					</td>
				</tr>";
        }
    } else {
        echo "EMPTY!<br>";
    }
    echo "</table>";
} else echo "Query failed! #$conn->errno: $conn->error<br>";
?>
<form action='execute.php' method='post'>
	<input type='checkbox' required>
	<input type='checkbox' required>
	<input type='checkbox' required>
	<input type='hidden' name='query' value = 'DELETE FROM transactions'>
    <input type='submit' value='DELETE ALL'>
</form>

<h2>Orders</h2>
<?php
$sql = "SELECT * FROM orders";
if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        echo "<table style='width:100%'> <caption>Orders List</caption> <th>ID (id_transaction)</th> <th>ID (id_wine)</th> <th>Amount (amount)</th>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
					<td>" . $row[id_transaction] . "</td>
					<td>" . $row[id_wine] . "</td>
					<td>" . $row[amount] . "</td>
				</tr>";
        }
    } else {
        echo "EMPTY!<br>";
    }
    echo "</table>";
} else echo "Query failed! #$conn->errno: $conn->error<br>";
?>
<form action='execute.php' method='post'>
	<input type='checkbox' required>
	<input type='checkbox' required>
	<input type='checkbox' required>
	<input type='hidden' name='query' value = 'DELETE FROM orders'>
    <input type='submit' value='DELETE ALL'>
</form>

<h2>Cart</h2>
<?php
$sql = "SELECT * FROM cart";
if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        echo "<table style='width:100%'> <caption>Cart</caption> <th>ID (id_customer)</th> <th>ID (id_wine)</th> <th>Amount (amount)</th> <th>Cost (cost)</th>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
					<td>" . $row[id_customer] . "</td>
					<td>" . $row[id_wine] . "</td>
					<td>" . $row[amount] . "</td>
					<td>" . $row[cost] . "</td>
				</tr>";
        }
    } else {
        echo "EMPTY!<br>";
    }
    echo "</table>";
} else echo "Query failed! #$conn->errno: $conn->error<br>";
?>
<form action='execute.php' method='post'>
	<input type='checkbox' required>
	<input type='checkbox' required>
	<input type='checkbox' required>
	<input type='hidden' name='query' value = 'DELETE FROM cart'>
    <input type='submit' value='DELETE ALL'>
</form>
<br>
<input type='button' value='Refresh' onclick=location.href='manage.php'>

<?php
$conn->close();
?>
</html>