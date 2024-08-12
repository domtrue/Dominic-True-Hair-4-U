<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Responsive Navbar</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <nav class="navbar">
      <div class="logo">
          <a href="/">
            <img src="img/logo.png" alt="Logo">
          </a>
        </div>
        <div class="menu-container">
            <ul class="menu-left">
                <li><a href="promotions.php">Promotions</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="book_appointment.php">Book Appointment</a></li>
            </ul>
            <ul class="menu-right">
                <li><a href="hair_services.php">Hair Services</a></li>
                <li><a href="gift_vouchers.php">Gift Vouchers</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="login.php" class="fa-solid fa-right-to-bracket"></a></li>

            </ul>
        </div>
        <div class="hamburger" onclick="toggleMenu()">&#9776;</div>
        <ul class="menu">
            <li><a href="promotions.php">Promotions</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li><a href="book_appointment.php">Book Appointment</a></li>
            <li><a href="hair_services.php">Hair Services</a></li>
            <li><a href="gift_vouchers.php">Gift Vouchers</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <i class="fa-solid fa-right-to-bracket"></i>

        </ul>
    </nav>

    <script src="script.js"></script>
</body>
</html>