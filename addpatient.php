<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'armband');
if ($conn->connect_error) {
    die("<script>alert('Connection failed: " . $conn->connect_error . "');</script>");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $armbandId = $conn->real_escape_string($_POST['armbandId']);
    $fullName = $conn->real_escape_string($_POST['fullName']);
    $idNumber = $conn->real_escape_string($_POST['idNumber']);
    $patientTelephone = $conn->real_escape_string($_POST['patientTelephone']);
    $sex = $conn->real_escape_string($_POST['sex']);
    $district = $conn->real_escape_string($_POST['district']);
    $province = $conn->real_escape_string($_POST['province']);
    $hospital = $conn->real_escape_string($_POST['hospital']);
    $notifyPerson = isset($_POST['notifyPerson']) ? $conn->real_escape_string($_POST['notifyPerson']) : NULL;
    $notifyTelephone = isset($_POST['notifyTelephone']) ? $conn->real_escape_string($_POST['notifyTelephone']) : NULL;

    // Check for duplicate ArmbandId
    $check = $conn->prepare("SELECT ArmbandId FROM patient WHERE ArmbandId = ?");
    $check->bind_param("s", $armbandId);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>
                alert('Error: Armband ID $armbandId already exists! Please use a different ID.');
                window.history.back();
              </script>";
        exit();
    }
    $check->close();

    // Validate telephone numbers
    if (!preg_match('/^\+?\d{8,15}$/', $patientTelephone)) {
        die("<script>alert('Invalid patient phone format'); history.back();</script>");
    }

    // Insert data with prepared statement
    $stmt = $conn->prepare("INSERT INTO patient (ArmbandId, fullName, idNumber, patientTelephone, sex, district, province, hospital, notifyPerson, notifyTelephone) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $armbandId, $fullName, $idNumber, $patientTelephone, $sex, $district, $province, $hospital, $notifyPerson, $notifyTelephone);
    
    if ($stmt->execute()) {
        echo "<script>
                alert('Patient registered successfully!');
                window.location.href = 'addpatient.php';
              </script>";
    } else {
        echo "<script>
                alert('Database error: " . addslashes($stmt->error) . "');
                window.history.back();
              </script>";
    }
    $stmt->close();
    $conn->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Registration</title>
    <style>
        /* Main Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: skyblue;
            margin: 0;
            padding: 15px;
            color: #333;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: gray;
            background-color:skyblue;
        }
        
        h1 {
            color: blue;
            text-align: center;
            margin-bottom: 10px;
            margin-top:5px;
            font-size: 20px;
        }
        
        /* Form Layout */
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom:10px;
        }
        
        .form-column {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 25px;
            background: gray;
            border-radius: 8px;
            border: 1px black;
        }
        
        /* Right column styling */
        .form-column:last-child {
            background: gray;
        }
        
        /* Make both columns equal height with 5 rows */
        .form-column {
            min-height: 100px; /* Adjust based on your content */
            justify-content: space-between;
        }
        
        /* Form Elements */
        .form-group {
            margin-bottom: 5px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: black;
            font-size: 14px;
        }
        
        input, select {
            width: 90%;
            padding: 10px 10px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 16px;
            transition: all 0.3s ease;
            height:10px;
        }
        
        input:focus, select:focus {
            border-color: #4299e1;
            outline: none;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
        }
        
        /* Required Field */
        .required:after {
            content: " *";
            color: #e53e3e;
        }
        
        /* Button */
        button {
            display: block;
            width: 200px;
            margin: 30px auto 0;
            padding: 14px;
            background-color: #4299e1;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        button:hover {
            background-color: #3182ce;
        }
        
        /* Helper Classes */
        .hint {
            font-size: 12px;
            color: #718096;
            margin-top: 5px;
            display: block;
        }
        
        #armbandIdStatus {
            font-size: 13px;
            margin-left: 5px;
        }
        
        .available {
            color: #38a169;
        }
        
        .taken {
            color: #e53e3e;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
            }
            
            .form-column {
                width: 100%;
                min-height: auto;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>IoT Armband Patient Registration</h1>
        <form id="patientForm" method="POST">
            <div class="form-row">
                <!-- Left Column - 5 rows -->
                <div class="form-column">
                    <div class="form-group">
                        <label for="armbandId" class="required">Armband ID</label>
                        <input type="text" id="armbandId" name="armbandId" required>
                        <span id="armbandIdStatus"></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="fullName" class="required">Full Name</label>
                        <input type="text" id="fullName" name="fullName" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="idNumber" class="required">ID Number</label>
                        <input type="text" id="idNumber" name="idNumber" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="patientTelephone" class="required">Patient Telephone</label>
                        <input type="tel" id="patientTelephone" name="patientTelephone" 
                               pattern="\+?\d{8,15}" 
                               title="8-15 digits with optional +" 
                               required>
                        <span class="hint">Format: +1234567890 or 0123456789</span>
                    </div>
                    
                    <div class="form-group">
                        <label for="sex" class="required">Sex</label>
                        <select id="sex" name="sex" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                
                <!-- Right Column - 5 rows -->
                <div class="form-column">
                    <div class="form-group">
                        <label for="district" class="required">District</label>
                        <input type="text" id="district" name="district" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="province" class="required">Province</label>
                        <input type="text" id="province" name="province" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="hospital" class="required">Hospital</label>
                        <input type="text" id="hospital" name="hospital" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="notifyPerson">Notify (Doctor/Caregiver)</label>
                        <select id="notifyPerson" name="notifyPerson">
                            <option value="">Select Option</option>
                            <option value="Doctor">Doctor</option>
                            <option value="Caregiver">Caregiver</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="notifyTelephone">Notification Telephone</label>
                        <input type="tel" id="notifyTelephone" name="notifyTelephone"
                               pattern="\+?\d{8,15}"
                               title="8-15 digits with optional +">
                    </div>
                </div>
            </div>
            <button type="submit" id="submitBtn">Register Patient</button>
        </form>
    </div>

    <script>
        // Real-time Armband ID availability check
        document.getElementById('armbandId').addEventListener('input', function() {
            const armbandId = this.value.trim();
            const statusElement = document.getElementById('armbandIdStatus');
            
            if (armbandId.length > 0) {
                fetch('addpatient.php?checkArmbandId=' + encodeURIComponent(armbandId))
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            statusElement.textContent = '✗ Already in use';
                            statusElement.className = 'taken';
                            document.getElementById('submitBtn').disabled = true;
                        } else {
                            statusElement.textContent = '✓ Available';
                            statusElement.className = 'available';
                            document.getElementById('submitBtn').disabled = false;
                        }
                    });
            } else {
                statusElement.textContent = '';
                document.getElementById('submitBtn').disabled = false;
            }
        });

        // Telephone number formatting
        const formatPhone = (input) => {
            input.value = input.value.replace(/[^\d+]/g, '');
        };

        // Validate form before submission
        document.getElementById('patientForm').addEventListener('submit', function(e) {
            const patientTel = document.getElementById('patientTelephone');
            if (!/^\+?\d{8,15}$/.test(patientTel.value)) {
                e.preventDefault();
                alert('Please enter a valid patient telephone number (8-15 digits)');
                patientTel.focus();
                return;
            }
            
            const notifyPerson = document.getElementById('notifyPerson');
            const notifyTel = document.getElementById('notifyTelephone');
            if (notifyPerson.value && !/^\+?\d{8,15}$/.test(notifyTel.value)) {
                e.preventDefault();
                alert('Please enter valid notification telephone number');
                notifyTel.focus();
                return;
            }
            
            // Final check for duplicate Armband ID (in case of race condition)
            const armbandId = document.getElementById('armbandId').value.trim();
            if (!armbandId) {
                e.preventDefault();
                alert('Please enter an Armband ID');
                return;
            }
        });

        // Add input formatting
        document.getElementById('patientTelephone').addEventListener('input', (e) => formatPhone(e.target));
        document.getElementById('notifyTelephone').addEventListener('input', (e) => formatPhone(e.target));
    </script>

<?php
// Handle AJAX request for Armband ID check
if (isset($_GET['checkArmbandId'])) {
    header('Content-Type: application/json');
    $checkId = $conn->real_escape_string($_GET['checkArmbandId']);
    
    $check = $conn->prepare("SELECT ArmbandId FROM patient WHERE ArmbandId = ?");
    $check->bind_param("s", $checkId);
    $check->execute();
    $check->store_result();
    
    echo json_encode(['exists' => $check->num_rows > 0]);
    $check->close();
    $conn->close();
    exit();
}
?>
</body>
</html>