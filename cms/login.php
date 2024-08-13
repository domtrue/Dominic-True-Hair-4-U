<?php
// Include the database connection script
include 'setup.php'; // Ensure the path is correct

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute the query to find the user by email or phone
    $stmt = $pdo->prepare("SELECT id, password_hash, is_verified FROM users WHERE email = :username OR phone = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        if ($user['is_verified']) {
            // Start session and redirect to user dashboard or home page
            session_start();
            $_SESSION['user_id'] = $user['id'];
            header('Location: dashboard.php');
            exit();
        } else {
            $error_message = "Your account is not verified. Please check your email or phone for the verification link.";
        }
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Log In - Hair 4 U</title>
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
 
  <!-- Stylesheet -->
  <style media="screen">
    *,
    *:before,
    *:after {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }

    body {
        background-color: #080710; /* Optional: fallback color */
        background-image: url('img/login_bg.jpg'); /* Path to your image */
        background-size: cover; /* Ensure the image covers the entire viewport */
        background-position: center; /* Center the image */
        background-repeat: no-repeat; /* Prevent the image from repeating */
        margin: 0; /* Remove default margin */
        height: 100vh; /* Ensure body takes up the full viewport height */
    }

    .background {
        width: 430px;
        height: 520px;
        position: absolute;
        transform: translate(-50%, -50%);
        left: 50%;
        top: 50%;
        z-index: 1; /* Ensure it's above the background image */
    }

    form {
        height: 550px;
        width: 400px;
        background-color: rgba(255, 255, 255, 0.13); /* Semi-transparent background */
        position: absolute;
        transform: translate(-50%, -50%);
        top: 50%;
        left: 50%;
        border-radius: 10px;
        backdrop-filter: blur(10px); /* Blur effect to make text readable */
        border: 2px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 0 40px rgba(8, 7, 16, 0.6);
        padding: 50px 35px;
        z-index: 2; /* Ensure the form is on top of the shapes */
    }

    form * {
        font-family: 'Poppins', sans-serif;
        color: #ffffff;
        letter-spacing: 0.5px;
        outline: none;
        border: none;
    }

    form h3 {
        font-size: 32px;
        font-weight: 500;
        line-height: 42px;
        text-align: center;
    }

    label {
        display: block;
        margin-top: 30px;
        font-size: 16px;
        font-weight: 500;
    }

    input {
        display: block;
        height: 50px;
        width: 100%;
        background-color: rgba(255, 255, 255, 0.07);
        border-radius: 3px;
        padding: 0 10px;
        margin-top: 8px;
        font-size: 14px;
        font-weight: 300;
    }

    ::placeholder {
        color: #e5e5e5;
    }

    button, .create-account {
    width: 100%; /* Full width */
    height: 50px;
    background-color: #ffffff; /* Button background color */
    color: #080710; /* Button text color */
    font-size: 18px;
    font-weight: 600;
    border-radius: 5px;
    cursor: pointer;
    text-align: center;
    border: none; /* Remove border */
    text-decoration: none; /* Remove underline for links */
    line-height: 50px; /* Center text vertically */
    display: inline-block; /* Ensure buttons are treated as block-level elements */
}

    button {
    margin-top: 20px; /* Space between input fields and button */
    }

    .create-account {
    margin-top: 20px; /* Space between login button and create account button */
    display: inline-block; /* Ensure it's treated as a block-level element */
    text-decoration: none; /* Remove underline for link */
    }

    button:hover, .create-account:hover {
    background-color: rgba(255, 255, 255, 0.27); /* Hover effect */
    }

    .social {
        margin-top: 30px;
        display: flex;
    }

    .social div {
        background: red;
        width: 150px;
        border-radius: 3px;
        padding: 5px 10px 10px 5px;
        background-color: rgba(255, 255, 255, 0.27);
        color: #eaf0fb;
        text-align: center;
    }

    .social div:hover {
        background-color: rgba(255, 255, 255, 0.47);
    }

    .social .fb {
        margin-left: 25px;
    }

    .social i {
        margin-right: 4px;
    }

    .error {
        color: red;
        font-size: 16px;
        text-align: center;
        margin-top: 20px;
    }
  </style>
</head>
<body>
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form method="post" action="login.php">
        <h3>Log In</h3>

        <label for="username">Username</label>
        <input type="text" placeholder="Email or Phone" id="username" name="username" required>

        <label for="password">Password</label>
        <input type="password" placeholder="Password" id="password" name="password" required>

        <button type="submit">Login</button>

        <?php if (isset($error_message)): ?>
            <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

          <!-- Create an account button -->
          <a href="register.php" class="create-account">Create an account</a>

        <div class="social">
            <div class="go"><i class="fab fa-google"></i> Google</div>
            <div class="fb"><i class="fab fa-facebook"></i> Facebook</div>
        </div>
    </form>
</body>
</html>
