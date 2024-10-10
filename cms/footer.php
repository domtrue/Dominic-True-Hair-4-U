<?php
include 'setup.php';

// Initialize variable for social media links
$socialMediaLinks = [];

// Prepare and execute the query
$query = "SELECT platform, logo_url, link FROM social_media_links";
$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result) {
    // Fetch all rows as an associative array
    while ($row = mysqli_fetch_assoc($result)) {
        $socialMediaLinks[] = $row; // Store the rows in an array
    }
} else {
    // Log the error for debugging
    error_log("Error fetching social media links: " . mysqli_error($conn));
}

?>

<footer>
    <div class="social-media-footer">
        <?php foreach ($socialMediaLinks as $link): ?>
            <a href="<?php echo htmlspecialchars($link['link']); ?>" target="_blank" aria-label="<?php echo htmlspecialchars($link['platform']); ?>">
                <img src="<?php echo htmlspecialchars($link['logo_url']); ?>" alt="<?php echo htmlspecialchars($link['platform']); ?> Logo" />
            </a>
        <?php endforeach; ?>
    </div>
    
    <div class="copyright">
        &copy; Triple Five Enterprises Limited
    </div>
    <div class="address">
        68 High Street, Bulls 4818
    </div>
</footer>

