<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

// DATABASE CONNECTION
require_once "../config/database.php";

// STATISTICS
$total_result = $conn->query("SELECT COUNT(*) AS total FROM information");
$total = $total_result->fetch_assoc()["total"];

$verified_result = $conn->query("SELECT COUNT(*) AS total FROM information WHERE verification_status = 'verified'");
$verified = $verified_result->fetch_assoc()["total"];

$review_result = $conn->query("SELECT COUNT(*) AS total FROM information WHERE verification_status = 'needs_review'");
$needs_review = $review_result->fetch_assoc()["total"];

$archived_result = $conn->query("SELECT COUNT(*) AS total FROM information WHERE verification_status = 'archived'");
$archived = $archived_result->fetch_assoc()["total"];


?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard - Rwanda Smart Hub</title>
   <link rel="stylesheet"
          href="../assets/style.css">
</head>


<body>

<div class="admin-dashboard">

    <h1>Rwanda Smart Hub</h1>

    <h2>Admin Dashboard</h2>

    <p class="welcome">
        Welcome,
        <strong>
            <?php echo htmlspecialchars($_SESSION["admin_username"]); ?>
        </strong>
    </p>

    <div class="stats">

        <a href="manage_information.php" class="stat-card">
            <h3>Total Information</h3>
            <p><?php echo $total; ?></p>
        </a>

            <a href="manage_information.php?status=verified" class="stat-card">
    <h3>Verified</h3>
    <p><?php echo $verified; ?></p>
</a>
<a href="manage_information.php?status=archived" class="stat-card">
    <h3>Archived</h3>
    <p><?php echo $archived; ?></p>
</a>
        <a href="manage_information.php?status=needs_review" class="stat-card">
    <h3>Needs Review</h3>
    <p><?php echo $needs_review; ?></p>
</a>

   

    </div>

    <hr>

    <h3>System Management</h3>

    <ul class="admin-menu">

        <li>
            <a href="add_information.php">
                Add Information
            </a>
        </li>

        <li>
            <a href="manage_information.php">
                Manage Information
            </a>
        </li>

        <li>
            <a href="manage_categories.php">
                Manage Categories
            </a>
        </li>

        <li>
            <a href="manage_locations.php">
    Manage Locations
</a>
        </li>

        <li>
            <a href="manage_administrators.php">
    Manage Administrators
</a>
        </li>

    </ul>

    <hr>

    <a href="../public/">
        View Public Site
    </a>

    <br><br>

    <a href="logout.php">
        Logout
    </a>

</div>

</body>

</html>