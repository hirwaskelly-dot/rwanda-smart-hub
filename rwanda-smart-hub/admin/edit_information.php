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


/* Get existing information */

$sql = "
    SELECT *
    FROM information
    WHERE id = $id
";

$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    die("Information not found.");
}

$info = $result->fetch_assoc();


/* Update information */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST["name"] ?? "";
    $category_id = $_POST["category_id"] ?? "";
    $location_id = $_POST["location_id"] ?? "";
    $phone = $_POST["phone"] ?? "";
    $address = $_POST["address"] ?? "";
    $description = $_POST["description"] ?? "";
    $verification_status = $_POST["verification_status"] ?? "unverified";

    $stmt = $conn->prepare("
        UPDATE information
        SET
            category_id = ?,
            location_id = ?,
            name = ?,
            phone = ?,
            address = ?,
            description = ?,
            verification_status = ?
        WHERE id = ?
    ");

    $stmt->bind_param(
        "iisssssi",
        $category_id,
        $location_id,
        $name,
        $phone,
        $address,
        $description,
        $verification_status,
        $id
    );

    if ($stmt->execute()) {

        header(
            "Location: manage_information.php"
        );

        exit;

    } else {

        echo "Update failed: "
            . htmlspecialchars($stmt->error);
    }
}


/* Get categories */

$categories = $conn->query("
    SELECT *
    FROM categories
    ORDER BY name
");


/* Get locations */

$locations = $conn->query("
    SELECT *
    FROM locations
    ORDER BY district
");

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Information - Rwanda Smart Hub</title>

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

        <h1>Edit Information</h1>

        <p>
            Update the selected information.
        </p>

    </section>


    <section>

        <form method="POST">

            <div class="information-card">

                <p>

                    <strong>Name</strong>

                    <input
                        type="text"
                        name="name"
                        value="<?php
                            echo htmlspecialchars(
                                $info["name"]
                            );
                        ?>"
                        required
                    >

                </p>


                <p>

                    <strong>Category</strong>

                    <select
                        name="category_id"
                        required
                    >

                        <?php while (
                            $category =
                            $categories->fetch_assoc()
                        ): ?>

                            <option
                                value="<?php
                                    echo $category["id"];
                                ?>"
                                <?php
                                if (
                                    $category["id"]
                                    ==
                                    $info["category_id"]
                                ) {
                                    echo "selected";
                                }
                                ?>
                            >

                                <?php
                                echo htmlspecialchars(
                                    $category["name"]
                                );
                                ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </p>


                <p>

                    <strong>Location</strong>

                    <select
                        name="location_id"
                        required
                    >

                        <?php while (
                            $location =
                            $locations->fetch_assoc()
                        ): ?>

                            <option
                                value="<?php
                                    echo $location["id"];
                                ?>"
                                <?php
                                if (
                                    $location["id"]
                                    ==
                                    $info["location_id"]
                                ) {
                                    echo "selected";
                                }
                                ?>
                            >

                                <?php
                                echo htmlspecialchars(
                                    $location["district"]
                                );
                                ?>

                                -
                                
                                <?php
                                echo htmlspecialchars(
                                    $location["sector"]
                                );
                                ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </p>


                <p>

                    <strong>Phone</strong>

                    <input
                        type="text"
                        name="phone"
                        value="<?php
                            echo htmlspecialchars(
                                $info["phone"] ?? ""
                            );
                        ?>"
                    >

                </p>


                <p>

                    <strong>Address</strong>

                    <input
                        type="text"
                        name="address"
                        value="<?php
                            echo htmlspecialchars(
                                $info["address"] ?? ""
                            );
                        ?>"
                    >

                </p>


                <p>

                    <strong>Description</strong>

                    <textarea
                        name="description"
                        rows="5"
                    ><?php
                        echo htmlspecialchars(
                            $info["description"] ?? ""
                        );
                    ?></textarea>

                </p>


                <p>

                    <strong>
                        Verification Status
                    </strong>

                    <select
                        name="verification_status"
                    >

                        <option
                            value="unverified"
                            <?php
                            if (
                                $info[
                                    "verification_status"
                                ]
                                === "unverified"
                            ) {
                                echo "selected";
                            }
                            ?>
                        >
                            Unverified
                        </option>

                        <option
                            value="verified"
                            <?php
                            if (
                                $info[
                                    "verification_status"
                                ]
                                === "verified"
                            ) {
                                echo "selected";
                            }
                            ?>
                        >
                            Verified
                        </option>

                        <option
                            value="needs review"
                            <?php
                            if (
                                $info[
                                    "verification_status"
                                ]
                                === "needs review"
                            ) {
                                echo "selected";
                            }
                            ?>
                        >
                            Needs Review
                        </option>

                        <option
                            value="archived"
                            <?php
                            if (
                                $info[
                                    "verification_status"
                                ]
                                === "archived"
                            ) {
                                echo "selected";
                            }
                            ?>
                        >
                            Archived
                        </option>

                    </select>

                </p>


                <button
                    type="submit"
                    class="clear-button"
                >
                    💾 Save Changes
                </button>


                <a
                    href="manage_information.php"
                    class="clear-button"
                >
                    Cancel
                </a>

            </div>

        </form>

    </section>

</main>


<footer>

    <p>
        Rwanda Smart Hub — Admin Panel
    </p>

</footer>

</body>

</html>