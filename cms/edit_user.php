<?php
include 'setup.php';
session_start();

if (isset($_SESSION['user_details']['role']) && $_SESSION['user_details']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$user_id = $_GET['id'];

// Fetch current customer details from the database
$sql = "SELECT * FROM accounts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();

if (!$customer) {
    echo "<p>Error: Customer not found.</p>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission for updating account details and setting a new password
    $firstname = htmlspecialchars($_POST['firstname'], ENT_QUOTES);
    $lastname = htmlspecialchars($_POST['lastname'], ENT_QUOTES);
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES);
    $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES);

    // Update customer details
    $stmt = $conn->prepare('UPDATE accounts SET firstname = ?, lastname = ?, email = ?, phone = ? WHERE id = ?');
    $stmt->bind_param('ssssi', $firstname, $lastname, $email, $phone, $customer['id']);
    if ($stmt->execute()) {
        echo 'Account updated successfully!';
    } else {
        echo 'Failed to update account.';
    }

    // Update password if new password is set
    if (!empty($_POST['new_password'])) {
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare('UPDATE accounts SET password = ? WHERE id = ?');
        $stmt->bind_param('si', $new_password, $customer['id']);
        if ($stmt->execute()) {
            echo 'Password updated successfully!';
        } else {
            echo 'Failed to update password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer Account</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <h1>Edit Customer Account</h1>
    <form method="post" action="">
        <label for="firstname">First Name:</label>
        <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($customer['firstname'], ENT_QUOTES); ?>" required>

        <label for="lastname">Last Name:</label>
        <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($customer['lastname'], ENT_QUOTES); ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($customer['email'], ENT_QUOTES); ?>" required>

        <label for="phone">Phone Number:</label>
        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($customer['phone'], ENT_QUOTES); ?>" required>

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password">

        <button type="submit">Update Account</button>
    </form>
</body>
</html>
