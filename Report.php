<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "armband");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle report submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $armbandId = $_POST["armbandId"];
    $report = $_POST["report"];

    $stmt = $conn->prepare("INSERT INTO report (ArmbandId, report) VALUES (?, ?)");
    $stmt->bind_param("ss", $armbandId, $report);
    $stmt->execute();
    $stmt->close();
}

// Fetch recent reports
$reports = $conn->query("
    SELECT r.*, p.fullName 
    FROM report r 
    JOIN patient p ON r.ArmbandId = p.ArmbandId 
    ORDER BY r.timestamp DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor–Patient Report</title>
    <style>
        body {
            font-family: Arial;
            margin: 20px;
        }
        h2{
            text-align:center;
            color:blue;
        }
        h3:{
            text-align:center;
        color:green;
    }
          .report-form, .report-list {
            border: 1px solid gray;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 10px;
            background-color:skyblue;
        }
        .report-form input, 
        .report-form textarea {
            width: 90%;
            padding: 10px;
            margin: 5px 0 15px;
        }
        .report-form button {
            padding: 10px 20px;
        }
        table {
            width: 90%;
            border-collapse: collapse;
        }
        table th, td {
            border: 1px solid #ccc;
            padding: 8px;
            background-color:gray;
        }
    </style>
</head>
<body>
    <h2>Patient Report</h2>

    <div class="report-form">
        <h3>Search Patient by Armband ID and Write Report</h3>
        <form method="post">
            <label for="armbandId">Enter Armband ID:</label>
            <input type="text" name="armbandId" required placeholder="e.g. ARM12345">

            <label for="report">Report / Observations:</label>
            <textarea name="report" rows="5" required></textarea>

            <button type="submit">Submit Report</button>
        </form>
    </div>

    <div class="report-list">
        <h3>All Reports</h3>
        <table>
            <tr>
                <th>Patient</th>
                <th>Report</th>
                <th>Time</th>
            </tr>
            <?php while ($r = $reports->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($r['fullName']) ?> (<?= $r['ArmbandId'] ?>)</td>
                    <td><?= nl2br(htmlspecialchars($r['report'])) ?></td>
                    <td><?= $r['timestamp'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>

<?php $conn->close(); ?>