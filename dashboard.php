<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/lib/Session.php');
include_once($filepath . '/lib/Database.php');
include_once('classes/User.php');

Session::init();
$userId = Session::get("userId");

// Connect to the database
$db = new Database();
$con = $db->link; // Get the database connection

// Fetch user data directly using mysqli_query
$sql = "SELECT * FROM tbl_user WHERE userId = '$userId'"; // Ensure you use the correct table name
$result = mysqli_query($con, $sql); // Use mysqli_query with the connection

// Check if data was retrieved
if ($result) {
    $userData = mysqli_fetch_assoc($result); // Fetch associative array
} else {
    echo "Error retrieving user data: " . mysqli_error($con);
    exit();
}

// Include header
include 'inc/header.php';
?>

<div class="container mt-5">
    <h2>Student Dashboard</h2>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">User Information</h5>
            <p class="card-text">Name: <?php echo htmlspecialchars($userData['name']); ?></p>
            <p class="card-text">Username: <?php echo htmlspecialchars($userData['userName']); ?></p>
            <p class="card-text">Email: <?php echo htmlspecialchars($userData['email']); ?></p>
            <a href="profile.php" class="btn btn-primary">Edit Profile</a>
        </div>
    </div>
</div>

<?php
// Include footer
include 'inc/footer.php';
?>
