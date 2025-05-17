<?php
session_start();

// Connect to database
$conn = new mysqli("localhost", "root", "", "armband");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["Username"];
    $password = $_POST["Password"];

    // Prepare and check login credentials
    $stmt = $conn->prepare("SELECT * FROM register WHERE Username = ? AND Password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Login success - start session and redirect
        $_SESSION["loggedin"] = true;
        $_SESSION["username"] = $username;
        header("Location: arm.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial;
            background-image: url('alpha.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-box {
            background-color: skyblue;
            color: white;
            padding: 40px;
            border-radius: 10px;
            width: 300px;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login</h2>
        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post" action="">
            <label>Username:</label><br>
            <input type="text" name="Username" required><br>

            <label>Password:</label><br>
            <input type="password" name="Password" required><br>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>


