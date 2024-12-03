<!DOCTYPE html><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hair 4 U</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="appointment-page">


    <div class="main-content">
        <div class="content">
            <h1>Book an Appointment</h1>
            <form action="appointment_form_submission.php" method="post">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" required>

                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" required>

                <label for="service_type">Select Service:</label>
                <select id="service_type" name="service_type" required>
                    <option value="Haircut">Haircut</option>
                    <option value="Colour">Colour</option>
                    <option value="Foils">Foils</option>
                    <option value="Perm">Perm</option>
                    <option value="Treatment">Treatment</option>
                    <option value="Blow wave">Blow wave</option>
                </select>

                <label for="appointment_date">Appointment Date:</label>
                <input type="date" id="appointment_date" name="appointment_date" required>

                <label for="appointment_time">Appointment Time:</label>
                <input type="time" id="appointment_time" name="appointment_time" required>

                <input type="submit" value="Book Appointment">
            </form>
        </div>
    </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="script.js"></script>
</body>

</html>
