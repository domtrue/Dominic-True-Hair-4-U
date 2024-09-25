<?php
session_start();
// Redirect to login page if user is not logged in
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .navtop {
            background-color: #2f3947;
            height: 60px;
            width: 100%;
            border-bottom: 1px solid #ddd;
            color: #eaebed;
        }
        .navtop div {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1000px;
            margin: 0 auto;
            height: 100%;
            padding: 0 20px;
        }
        .navtop div h1 {
            font-size: 24px;
            margin: 0;
        }
        .navtop div a {
            color: #c1c4c8;
            text-decoration: none;
            font-weight: bold;
            padding: 0 15px;
        }
        .navtop div a:hover {
            color: #eaebed;
        }
        .content {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .content h2 {
            margin-top: 0;
            border-bottom: 2px solid #4a536e;
            padding-bottom: 10px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h3 {
            margin-top: 0;
            color: #4a536e;
        }
        .card {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .card a {
            text-decoration: none;
            color: #007bff;
        }
        .card a:hover {
            text-decoration: underline;
        }
        .card button {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }
        .card button:hover {
            background-color: #0056b3;
        }
        @media (max-width: 768px) {
            .navtop div {
                flex-direction: column;
                align-items: flex-start;
            }
            .navtop div a {
                padding: 10px 0;
            }
        }
    </style>
</head>
<body class="loggedin">
    <nav class="navtop">
        <div>
            <h1>Admin Dashboard</h1>
            <div>
                <a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
            </div>
        </div>
    </nav>
    <div class="content">
        <h2>Welcome, <?= htmlspecialchars($_SESSION['name'], ENT_QUOTES) ?>!</h2>
        
        <!-- Dashboard Overview -->
        <div class="section">
            <h3>Dashboard Overview</h3>
            <div class="card">
                <h4>Orders Summary</h4>
                <p>Total Orders: <span id="total-orders">Loading...</span></p>
                <p><a href="orders.php">View Orders</a></p>
            </div>
            <div class="card">
                <h4>Appointments Summary</h4>
                <p>Total Appointments: <span id="total-appointments">Loading...</span></p>
                <p><a href="appointments.php">View Appointments</a></p>
            </div>
            <div class="card">
                <h4>Products Summary</h4>
                <p>Total Products: <span id="total-products">Loading...</span></p>
                <p><a href="products.php">View Products</a></p>
            </div>
            <div class="card">
                <h4>Sales & Revenue</h4>
                <p>Total Sales: <span id="total-sales">Loading...</span></p>
                <p><a href="sales.php">View Sales</a></p>
            </div>
        </div>
        
        <!-- Additional Information and Links -->
        <div class="section">
            <h3>Manage Accounts</h3>
            <div class="card">
                <p><a href="accounts.php">View and Manage Customer Accounts</a></p>
            </div>
        </div>
    </div>
</body>
</html>
