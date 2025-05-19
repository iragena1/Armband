<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'armband');
if ($conn->connect_error) {
    die("<script>alert('Connection failed: " . $conn->connect_error . "');</script>");
}

// Handle form submission to update or delete a user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_user'])) {
        // Update user
        $id = $_POST['id'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Update user details
        $update = $conn->prepare("UPDATE register SET username = ?, password = ? WHERE id = ?");
        $update->bind_param("ssi", $username, $password, $id);
        $update->execute();
        $update->close();
    } elseif (isset($_POST['delete_user'])) {
        // Delete user
        $id = $_POST['id'];

        // Delete user from database
        $delete = $conn->prepare("DELETE FROM register WHERE id = ?");
        $delete->bind_param("i", $id);
        $delete->execute();
        $delete->close();
    }
}

// Get all users from register table
$result = $conn->query("SELECT id, username, password FROM register");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
 .form-container {
    width: 250px;
    background-color: skyblue;
    padding: 10px;
    margin: 30px auto;
    border: 1px solid #ccc;
    border-radius: 8px;
    box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
    
     font-size: 12px
}

.form-container h3 {
    text-align: center;
    margin-bottom: 15px;
    font-size: 16px;
}

.form-container label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    font-size: 13
}

.form-container h3 {
    margin-top: 0;
    text-align: center;
    color: #333;
}

.form-container label {
    display: block;
    font-weight: bold;
    margin-top:5px;
    color: #444;
}

.form-container input[type="text"],
.form-container input[type="password"] {
    width: 80%;
    padding:5px;
    margin-top:2px;
    border: 1px solid #aaa;
    border-radius: 5px;
    box-sizing: border-box;
}

.form-container button {
    margin-top: 5px;
    margin-right: 20px;
    padding:8px 16px;
    background-color: #00aaff;
    color: white;
    border: none;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
}

.form-container button:hover {
    background-color: #0088cc;
}

.form-container button:last-child {
    background-color: #ccc;
    color: black;
}

.form-container button:last-child:hover {
    background-color: #bbb;
}


        h2 {
            text-align: center;
           text-color:blue;
        }
        table {
            width: 60%;
            margin: 0 auto;
            border-collapse: collapse;
            background-color:gray;
        }
        td{
            padding: 10px;
            border: 1px solid #aaa;
            text-align: center;
         
           
        }
        th{
            background-color:skyblue;
        }
        .table-wrapper {
            display: flex;
            justify-content: center;
        }
        a {
           background-color:blue;
            text-decoration: none;
            padding:5px 10px
        }
        a:hover {
            text-decoration: underline;
           
        }
        
    </style>
</head>
<body>
    <h2>Manage user</h2>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>" . htmlspecialchars($row['username']) . "</td>
                            <td>" . htmlspecialchars($row['password']) . "</td>
                            <td>
                                <a href='#' onclick='editUser(" . $row['id'] . ", \"" . htmlspecialchars($row['username']) . "\", \"" . htmlspecialchars($row['password']) . "\")'>Edit</a> | 
                                <a href='#' onclick='deleteUser(" . $row['id'] . ")'>Delete</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No users found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Edit/Delete user form -->
    <div id="formContainer" class="form-container" style="display: none;">
        <h3 id="formTitle">Edit User</h3>
        <form method="POST">
            <input type="hidden" id="userId" name="id">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>
            <button type="submit" name="update_user">Update</button>
            <button type="submit" name="delete_user" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
        </form>
        <br>
        <button onclick="closeForm()">Cancel</button>
    </div>

    <script>
        function editUser(id, username, password) {
            document.getElementById('userId').value = id;
            document.getElementById('username').value = username;
            document.getElementById('password').value = password;
            document.getElementById('formTitle').innerText = 'Edit User';
            document.getElementById('formContainer').style.display = 'block';
        }

        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `<input type="hidden" name="id" value="${id}">
                                  <input type="hidden" name="delete_user" value="true">`;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function closeForm() {
            document.getElementById('formContainer').style.display = 'none';
        }
    </script>
</body>
</html>

<?php $conn->close(); ?>
