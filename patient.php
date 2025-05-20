<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "armband");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all patients
$sql = "SELECT  ArmbandId,fullName, idNumber,patientTelephone,sex, district, province, hospital, notifyPerson,
notifyTelephone,registration_date FROM patient";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient List</title>
    <style>
        body {
            font-family: Arial;
            margin: 30px;
            background-color: steelblue;
        }
        h2 {
            text-align: center;
            color: #1f4e79;
        }
        table {
            width: 95%;
            margin: auto;
            border-collapse: collapse;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            border: 1px solid #999;
            text-align: center;
            background-color: #e6f2ff;
        }
        th {
            background-color: #cce0ff;
        }
    </style>
</head>
<body>
    <h2>Patient List</h2>
    <table>
        <tr>
            <th>ArmbandId</th>
            <th>fullName</th>
            <th>idNumber</th>
            <th>patientTelephone</th>
            <th>sex</th>
            <th>district</th>
            <th>province</th>
            <th>hospital</th>
            <th>notifyPerson</th>
            <th>notifyTelephone</th>
            <th>registration_date</th>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['ArmbandId']) ?></td>
                    <td><?= htmlspecialchars($row['fullName']) ?></td>
                    <td><?= htmlspecialchars($row['idNumber']) ?></td>
                    <td><?= htmlspecialchars($row['patientTelephone']) ?></td>
                    <td><?= htmlspecialchars($row['sex']) ?></td>
                    <td><?= htmlspecialchars($row['district']) ?></td>
                    <td><?= htmlspecialchars($row['province']) ?></td>
                    <td><?= htmlspecialchars($row['hospital']) ?></td>
                    <td><?= htmlspecialchars($row['notifyPerson']) ?></td>
                    <td><?= htmlspecialchars($row['notifyTelephone']) ?></td>
                    <td><?= htmlspecialchars($row['registration_date']) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="8">No patients found.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>

<?php $conn->close(); ?>
