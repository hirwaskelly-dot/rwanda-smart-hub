<?php

require_once "../config/database.php";

$id = $_GET["id"] ?? 0;

$id = (int) $id;
require_once "../languages/language.php";

$translations = $translations;

require_once "../config/database.php";

$result = $conn->query("
    SELECT
        information.*,
        categories.name AS category_name,
        locations.province,
        locations.district,
        locations.sector
    FROM information

    INNER JOIN categories
        ON information.category_id = categories.id

    LEFT JOIN locations
        ON information.location_id = locations.id

    WHERE information.id = $id
");

if (!$result || $result->num_rows === 0) {
    die("Information not found.");
}

$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo htmlspecialchars($row["name"]); ?>
        - Rwanda Smart Hub
    </title>

    <link rel="stylesheet" href="assest/style.css">

</head>

<body>

    <div class="details-page">

        <a href="index.php" class="back-button">
    ← <?php echo $translations["back_to_information"]; ?>
</a>

        <div class="details-card">

            <h1>
                <?php echo htmlspecialchars($row["name"]); ?>
            </h1>

            <p>
               <strong><?php echo $translations["category"]; ?>:</strong>
                <?php echo htmlspecialchars($row["category_name"]); ?>
            </p>

            <p>
                <strong><?php echo $translations["location"]; ?>:</strong>
                <?php echo htmlspecialchars($row["province"]); ?>
                →
                <?php echo htmlspecialchars($row["district"]); ?>
                →
                <?php echo htmlspecialchars($row["sector"]); ?>
            </p>

            <p>
                <strong><?php echo $translations["phone"]; ?>:</strong>
                <?php echo htmlspecialchars($row["phone"]); ?>
            </p>

            <p>
                <strong><?php echo $translations["address"]; ?>:</strong>
                <?php echo htmlspecialchars($row["address"]); ?>
            </p>

            <p>
                <strong><?php echo $translations["description"]; ?>:</strong>
                <?php echo htmlspecialchars($row["description"]); ?>
            </p>

            <p>
                <strong><?php echo $translations["status"]; ?>:</strong>
                <?php echo htmlspecialchars($row["verification_status"]); ?>
            </p>

        </div>

    </div>

</body>

</html>