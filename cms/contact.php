<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hair 4 U</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/contact.css">
</head>

<body>
<?php include 'header.php';?>

<div class="content">
    <div class="container">
        <!-- Heading and Subheading -->
        <div class="heading-section">
            <h2>Drop us a line</h2>
            <p class="subheading">Reach out to Melissa and the team for all general enquiries including: styling recommendations, cancellation or rescheduling an appointment, price enquiries, product recommendations and special events. Where you've got a question, we've got the answer!</p>
        </div>

        <!-- Contact Form -->
        <form action="process_form.php" method="POST">
            <div class="row">
                <div class="col-25">
                    <label for="fname">First Name</label>
                </div>
                <div class="col-75">
                    <input type="text" id="fname" name="firstname" placeholder="Your first name..">
                </div>
            </div>
            <div class="row">
                <div class="col-25">
                    <label for="lname">Last Name</label>
                </div>
                <div class="col-75">
                    <input type="text" id="lname" name="lastname" placeholder="Your last name..">
                </div>
            </div>
            <div class="row">
                <div class="col-25">
                    <label for="email">Email</label>
                </div>
                <div class="col-75">
                    <input type="text" id="email" name="email" placeholder="Your email..">
                </div>
            </div>
            <div class="row">
                <div class="col-25">
                    <label for="message">Message</label>
                </div>
                <div class="col-75">
                    <textarea id="message" name="message" placeholder="Your message.." style="height:200px"></textarea>
                </div>
            </div>
            <div class="row">
                <input type="submit" value="Submit">
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php';?>
<script src="script.js"></script>
</body>
</html>
