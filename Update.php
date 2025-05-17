<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'armband');
if ($conn->connect_error) {
    die("<script>alert('Connection failed: " . $conn->connect_error . "');</script>");
}

// Check if 'id' is passed in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch user data for the given id
    $stmt = $conn->prepare("SELECT username, password FROM register WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($username, $password);
    $stmt->fetch();
    $stmt->close();

    // Handle form submission for updating user data
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Update user details
        $updateStmt = $conn->prepare("UPDATE register SET username = ?, password = ? WHERE id = ?");
        $updateStmt->bind_param("ssi", $username, $password, $id);
        $updateStmt->execute();
        $updateStmt->close();

        // Redirect back to the dashboard after update
        header("Location: dashboard.php");
        exit();
    }
} else {
    echo "<script>alert('No user ID provided!');</script>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
</head>
<body>
    <h2>Edit User</h2>
    <form method="POST">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($username) ?>" required><br><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" value="<?= htmlspecialchars($password) ?>" required><br><br>
        <button type="submit">Update</button>
    </form>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>

<?php $conn->close(); ?>
