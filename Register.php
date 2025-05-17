<?php
// Connect to the Armband database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Armband";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$status = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST["username"];
    $pass = $_POST["password"];

    if (!empty($user) && !empty($pass)) {
        // Store plain password (for demo; not secure for real apps)
        $sql = "INSERT INTO register (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $user, $pass);

        if ($stmt->execute()) {
            $message = "User registered successfully!";
            $status = "success";
        } else {
            $message = "Error: " . $stmt->error;
            $status = "error";
        }

        $stmt->close();
    } else {
        $message = "Please fill in all fields.";
        $status = "error";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    
    <link rel="stylesheet" href="Armband.css">

</head>
<body>


<h2>Register form</h2>
<form method="post" action="">
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Register</button>
</form>

<p>Already have an account? <a href="login.php">Login </a></p>

<?php if (!empty($message)): ?>
    <div class="msg <?php echo $status; ?>" id="msgBox">
        <?php echo $message; ?>
    </div>

    <script>
        setTimeout(function() {
            var box = document.getElementById("msgBox");
            if (box) box.style.display = "none";
        }, 3000); // 3 seconds
    </script>
<?php endif; ?>

</body>
</html>
