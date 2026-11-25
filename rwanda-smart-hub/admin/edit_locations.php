
<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";

$location_id = (int)($_GET["id"] ?? $_POST["location_id"] ?? 0);

$result = $conn->query("
    SELECT *
    FROM locations
    WHERE id = $location_id
");

if (!$result || $result->num_rows === 0) {
    die("Location not found.");
}

$location = $result->fetch_assoc();
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $province = trim($_POST["province"] ?? "");
    $district = trim($_POST["district"] ?? "");
    $sector = trim($_POST["sector"] ?? "");

    if ($province === "" || $district === "" || $sector === "") {

        $error = "Please fill in all fields.";

    } else {

        $province_sql = $conn->real_escape_string($province);
        $district_sql = $conn->real_escape_string($district);
        $sector_sql = $conn->real_escape_string($sector);

        $check = $conn->query("
            SELECT id
            FROM locations
            WHERE province = '$province_sql'
              AND district = '$district_sql'
              AND sector = '$sector_sql'
              AND id != $location_id
        ");

        if ($check && $check->num_rows > 0) {

            $error = "This location already exists.";

        } else {

            $conn->query("
                UPDATE locations
                SET province = '$province_sql',
                    district = '$district_sql',
                    sector = '$sector_sql'
                WHERE id = $location_id
            ");

            header("Location: manage_locations.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Location - Rwanda Smart Hub</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
<div class="admin-dashboard">

    <h1>Edit Location</h1>

    <a href="manage_locations.php">← Back to Locations</a>

    <hr>

    <?php if ($error !== ""): ?>
        <p><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST">

        <input
            type="hidden"
            name="location_id"
            value="<?php echo (int)$location["id"]; ?>"
        >

        <p>
            <input
                type="text"
                name="province"
                placeholder="Province"
                value="<?php echo htmlspecialchars($_POST["province"] ?? $location["province"]); ?>"
                required
            >
        </p>

        <p>
            <input
                type="text"
                name="district"
                placeholder="District"
                value="<?php echo htmlspecialchars($_POST["district"] ?? $location["district"]); ?>"
                required
            >
        </p>

        <p>
            <input
                type="text"
                name="sector"
                placeholder="Sector"
                value="<?php echo htmlspecialchars($_POST["sector"] ?? $location["sector"]); ?>"
                required
            >
        </p>

        <button type="submit">Save Changes</button>

    </form>

</div>
</body>
</html>