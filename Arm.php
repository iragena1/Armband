<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Smart Health Monitoring</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to bottom right, #cceeff, #a8d0f0);
      color: #1a1a1a;
      
    }

    nav {
      background-color: #e3f2fc;
      padding: 10px 0;
      display: flex;
      justify-content: center;
      border-radius: 0 0 10px 10px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    nav ul {
      list-style: none;
      display: flex;
      gap: 30px;
      margin: 0;
      padding: 0;
    }

    nav li a {
      text-decoration: none;
      font-weight: bold;
      color: #1a3c59;
      padding: 8px 16px;
      border-radius: 6px;
      transition: background-color 0.3s;
    }

    nav li a:hover {
      background-color: #d6eaff;
    }

    nav li a.active {
      background-color: #1a3c59;
      color: white;
    }

    .main-content {
      text-align: center;
      padding: 60px 20px;
    }

    .main-content h1 {
      font-size: 2.5rem;
      color: #194775;
      margin-bottom: 20px;
    }

    .main-content p {
      font-size: 18px;
      max-width: 600px;
      margin: 0 auto 30px;
      color: #2f2f2f;
      font-family: "Times New Roman", serif;
      line-height: 1.6;
    }

    .get-started-btn {
      background-color: #2c9c7b;
      color: white;
      padding: 12px 25px;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .get-started-btn:hover {
      background-color: #237b62;
    }
  </style>
</head>
<body>

  <nav>
    <ul>
      <li><a href="home.php" class="active">Home</a></li>
      <li><a href="Register.php">Register</a></li>
      <li><a href="record.php">Record</a></li>
      <li><a href="addpatient.php">Add Patient</a></li>
      <li><a href="Report.php">Reports</a></li>
      <li><a href="patient.php">patient</a></li>
    </ul>
  </nav>

  <div class="main-content">
    <h1>SMART BAND  MONITORING SYSTEM FOR ELDERLY</h1>
    <p>
      A smart armband system designed to monitor blood pressure,<br>
      heart rate and blood oxygen levels of elderly<br>
      patients in real-time.
    </p>
    <button class="get-started-btn">GET STARTED</button>
  </div>

</body>
</html>
