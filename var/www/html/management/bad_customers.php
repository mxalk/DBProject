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
$sql = "SELECT SUM(cost-paid) AS total, id_customer FROM transactions WHERE paid!=cost GROUP BY id_customer ORDER BY total ";
if (!$result = $conn->query($sql)) {
    echo "SQL connect error: $conn->connect_errno, $conn->connect_error. ";
    exit;
} 
if ($result->num_rows > 0) {
	echo "<table style='width:100%'> <caption>Bad Customers List</caption>";
	while ($row = $result->fetch_assoc()) {
		echo "<tr>
			<td>" .$row[id_customer]. "</td>
			<td>" .$row[total]. "</td>
		</tr>";
	}
	echo "</table>";
} else echo "EMPTY!<br>";

?>
</body>
</html>
