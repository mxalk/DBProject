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
    exit();
}
$sql = "SELECT SUM(paid) AS total, id_customer FROM transactions WHERE id_customer NOT IN (SELECT DISTINCT id_customer from transactions WHERE paid!=cost) GROUP BY id_customer ORDER BY total DESC";
if (!$result = $conn->query($sql)) {
	echo "SQL connect error: $conn->connect_errno, $conn->connect_error. ";
	exit();
}
?>
<h1>ADMIN MENU</h1>
<h2>Good Customers</h2>
<?php
echo "<table style='width:100%'> <caption>Customers List</caption> <th>ID (id_customer)</th> <th>Income (SUM(paid))</th>";
while ($row = $result->fetch_assoc()) {
	if (!isset($row[total])) $row[total]=0;
	echo "<tr>
		<td>" .$row[id_customer]. "</td>
		<td>" .$row[total]. "</td>
	</tr>";
}
echo "</table>";
$conn->close();
?>
</html>
