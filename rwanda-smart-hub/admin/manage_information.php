<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";

$status_filter = $_GET["status"] ?? "";

$sql = "
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
";

if ($status_filter !== "") {
    $status_filter = $conn->real_escape_string($status_filter);

    $sql .= "
        WHERE information.verification_status = '$status_filter'
    ";
}

$sql .= "
    ORDER BY information.created_at DESC
";

$information = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Manage Information - Rwanda Smart Hub</title>

    <link rel="stylesheet"
          href="../assets/style.css">

</head>

<body>
<a href="dashboard.php">
    ← Back to Dashboard
</a>
<header>

    <div class="container">

        <div class="navbar">

            <div class="logo">
                🇷🇼 Rwanda Smart Hub
            </div>

            <div class="nav-links">

                <a href="dashboard.php">
                    Dashboard
                </a>

                <a href="logout.php">
                    Logout
                </a>

            </div>

        </div>

    </div>

</header>


<main class="container">

    <section class="hero">

        <h1>Manage Information</h1>

        <p>
            View information stored in the Rwanda Smart Hub database.
        </p>

    </section>


    <section>

        <?php if ($information && $information->num_rows > 0): ?>

            <?php while ($row = $information->fetch_assoc()): ?>

                <div class="information-card">

                    <h2>
                        <?php
                        echo htmlspecialchars(
                            $row["name"]
                        );
                        ?>
                    </h2>

                    <p>
                        <strong>Category:</strong>
                        <?php
                        echo htmlspecialchars(
                            $row["category_name"]
                        );
                        ?>
                    </p>

                    <p>
                        <strong>Location:</strong>
                        <?php
                        echo htmlspecialchars(
                            $row["district"] ?? "N/A"
                        );
                        ?>
                    </p>

                    <p>
                        <strong>Phone:</strong>
                        <?php
                        echo htmlspecialchars(
                            $row["phone"] ?? "N/A"
                        );
                        ?>
                    </p>

                    <p>
                        <strong>Address:</strong>
                        <?php
                        echo htmlspecialchars(
                            $row["address"] ?? "N/A"
                        );
                        ?>
                    </p>

                    <p>
                        <strong>Status:</strong>

                        <?php
                        echo htmlspecialchars(
                            $row["verification_status"]
                        );
                        ?>
                    </p>

                    <p>
                        <?php
                        echo htmlspecialchars(
                            $row["description"] ?? ""
                        );
                        ?>
                    </p>

                    <a href="edit_information.php?id=<?php echo $row["id"]; ?>"
   class="clear-button">

    ✏️ Edit Information

</a>

<a href="delete_information.php?id=<?php echo $row["id"]; ?>"
   class="clear-button">

    🗑️ Delete Information

</a>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <div class="information-card">

                <h3>
                    No information found.
                </h3>

            </div>

        <?php endif; ?>

    </section>

</main>


<footer>

    <p>
        Rwanda Smart Hub — Admin Panel
    </p>

</footer>

</body>

</html>