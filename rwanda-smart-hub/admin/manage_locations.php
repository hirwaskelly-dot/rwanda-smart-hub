<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $province = trim($_POST["province"] ?? "");
    $district = trim($_POST["district"] ?? "");
    $sector = trim($_POST["sector"] ?? "");

    if ($province !== "" && $district !== "" && $sector !== "") {

        $province = $conn->real_escape_string($province);
        $district = $conn->real_escape_string($district);
        $sector = $conn->real_escape_string($sector);

        $check = $conn->query("
            SELECT id
            FROM locations
            WHERE province = '$province'
            AND district = '$district'
            AND sector = '$sector'
        ");

        if ($check->num_rows === 0) {

            $conn->query("
                INSERT INTO locations
                (province, district, sector)
                VALUES
                ('$province', '$district', '$sector')
            ");

        }

    }

}
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_location"])) {

    $location_id = (int)($_POST["location_id"] ?? 0);

    $used = $conn->query("
        SELECT id
        FROM information
        WHERE location_id = $location_id
        LIMIT 1
    ");

    if ($used && $used->num_rows === 0) {

        $conn->query("
            DELETE FROM locations
            WHERE id = $location_id
        ");

    } else {

        echo "<script>
            alert('This location cannot be deleted because information is using it.');
        </script>";

    }
}

$result = $conn->query("
    SELECT *
    FROM locations
    ORDER BY province, district, sector
");

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Manage Locations - Rwanda Smart Hub</title>

    <link rel="stylesheet"
          href="../assets/style.css">

</head>

<body>

<div class="admin-dashboard">

    <h1>Manage Locations</h1>

    <a href="dashboard.php">
        ← Back to Dashboard
    </a>

    <hr>

    <h2>Add Location</h2>

    <form method="POST">

        <input
            type="text"
            name="province"
            placeholder="Province"
            required
        >

        <input
            type="text"
            name="district"
            placeholder="District"
            required
        >

        <input
            type="text"
            name="sector"
            placeholder="Sector"
            required
        >

        <button type="submit">
            Add Location
        </button>

    </form>

    <hr>

    <h2>Locations</h2>

   <?php while ($location = $result->fetch_assoc()): ?>

    <div class="information-card">

        <h3>
            <?php echo htmlspecialchars($location["province"]); ?>
            →
            <?php echo htmlspecialchars($location["district"]); ?>
            →
            <?php echo htmlspecialchars($location["sector"]); ?>
        </h3>

        <form method="POST"
              onsubmit="return confirm('Delete this location?');">

            <input
                type="hidden"
                name="location_id"
                value="<?php echo (int)$location["id"]; ?>"
            >

            <button type="submit" name="delete_location">
                Delete Location
            </button>
<a href="edit_locations.php?id=<?php echo (int)$location["id"]; ?>">
    Edit Location
</a>
        </form>

    </div>

<?php endwhile; ?>

</div>

</body>

</html>