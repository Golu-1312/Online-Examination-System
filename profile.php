<?php include 'inc/header.php'; ?>
<?php
Session::checkSession();
$userId = Session::get("userId");

// Connect to the database using the Database class
include_once 'lib/Database.php';
$db = new Database();
$con = $db->link; // Get the connection from the Database class

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = $_POST['name'] ?? '';
    $userName = $_POST['userName'] ?? '';
    $email = $_POST['email'] ?? '';

    // Update user profile directly
    $sql = "UPDATE tbl_user SET name='$name', userName='$userName', email='$email' WHERE userid='$userId'";
    $result = mysqli_query($con, $sql);
    
    if ($result) {
        $message = "Profile updated successfully.";
    } else {
        $message = "Error updating profile: " . mysqli_error($con);
    }
}

// Fetch user data directly
$sql = "SELECT name, userName, email FROM tbl_user WHERE userid='$userId'";
$getData = mysqli_query($con, $sql);
$result = $getData ? mysqli_fetch_assoc($getData) : [];
?>

<div class="container">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h1 class="mt-5">Update Your Profile</h1>
            <?php if (isset($message)) echo "<div class='alert alert-info'>$message</div>"; ?>
            <br/>
        </div>

        <div class="col-lg-4"></div>

        <div class="col-lg-4">
            <form action="" method="post">
                <table>
                    <tr>
                        <td>Name</td>
                        <td>
                            <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($result['name'] ?? ''); ?>" id="name">
                        </td>
                    </tr>
                    <tr>
                        <td>User Name</td>
                        <td>
                            <input type="text" class="form-control" name="userName" value="<?php echo htmlspecialchars($result['userName'] ?? ''); ?>" id="userName">
                        </td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>
                            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($result['email'] ?? ''); ?>" id="email">
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type="submit" id="profileUpdate" class="btn btn-info" value="Update Info">
                        </td>
                    </tr>
                </table>
            </form>
        </div>

        <div class="col-lg-4"></div>
    </div>
</div>

<?php include 'inc/footer.php'; ?>
