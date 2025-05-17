<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'armband');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all records with the associated patient full name (join with patient table)
$sql = "SELECT r.ArmbandId, p.fullName, r.systolic, r.diastolic, r.heart_rate, r.oxygen_saturation, r.timestamp 
        FROM record r 
        JOIN patient p ON r.ArmbandId = p.ArmbandId 
        ORDER BY r.timestamp DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Patient Records</title>
    <style>
       body {
    font-family: Arial, sans-serif;
}
h2 {
    text-align: center;
}
table {
    width: 90%;
    margin: 20px auto;
    border-collapse: collapse;
    background-color:gray;
}
th, td {
    padding: 10px;
    border: 1px solid black;
    text-align: center;
    

}
th {
    background-color: skyblue;
} 
    </style>
</head>
<body>
    <h2>All Patient Health Records</h2>

    <table>
        <thead>
            <tr>
                <th>Armband ID</th>
                <th>Full Name</th>
                <th>Systolic (mmHg)</th>
                <th>Diastolic (mmHg)</th>
                <th>Heart Rate (bpm)</th>
                <th>Oxygen Saturation (%)</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['ArmbandId']); ?></td>
                        <td><?php echo htmlspecialchars($row['fullName']); ?></td>
                        <td><?php echo htmlspecialchars($row['systolic']); ?></td>
                        <td><?php echo htmlspecialchars($row['diastolic']); ?></td>
                        <td><?php echo htmlspecialchars($row['heart_rate']); ?></td>
                        <td><?php echo htmlspecialchars($row['oxygen_saturation']); ?></td>
                        <td><?php echo htmlspecialchars($row['timestamp']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7">No records found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?>
