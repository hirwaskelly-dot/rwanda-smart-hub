<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";

$id = $_GET["id"] ?? "";

if ($id === "") {
    die("Information ID is missing.");
}

$id = (int) $id;


/* Get information */

$sql = "
    SELECT
        information.*,
        categories.name AS category_name
    FROM information
    INNER JOIN categories
        ON information.category_id = categories.id
    WHERE information.id = $id
";

$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    die("Information not found.");
}

$info = $result->fetch_assoc();


/* Delete after confirmation */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $stmt = $conn->prepare("
        DELETE FROM information
        WHERE id = ?
    ");

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {

        header(
            "Location: manage_information.php"
        );

        exit;

    } else {

        echo "Delete failed: "
            . htmlspecialchars($stmt->error);

    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Delete Information - Rwanda Smart Hub
    </title>

    <link
        rel="stylesheet"
        href="../assets/style.css"
    >

</head>

<body>

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

                <a href="manage_information.php">
                    Manage Information
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

        <h1>Delete Information</h1>

        <p>
            Please confirm this action.
        </p>

    </section>


    <section>

        <div class="information-card">

            <h2>
                ⚠️ Confirm Deletion
            </h2>

            <p>
                You are about to permanently delete:
            </p>

            <p>

                <strong>
                    <?php
                    echo htmlspecialchars(
                        $info["name"]
                    );
                    ?>
                </strong>

            </p>

            <p>

                Category:

                <?php
                echo htmlspecialchars(
                    $info["category_name"]
                );
                ?>

            </p>

            <p>
                This action cannot be undone.
            </p>


            <form method="POST">

                <button
                    type="submit"
                    class="clear-button"
                >
                    🗑️ Yes, Delete
                </button>

                <a
                    href="manage_information.php"
                    class="clear-button"
                >
                    Cancel
                </a>

            </form>

        </div>

    </section>

</main>


<footer>

    <p>
        Rwanda Smart Hub — Admin Panel
    </p>

</footer>

</body>

</html>