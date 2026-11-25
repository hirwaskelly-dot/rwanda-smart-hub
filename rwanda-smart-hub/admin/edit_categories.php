<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";

$category_id = (int)($_GET["id"] ?? 0);

$result = $conn->query("
    SELECT *
    FROM categories
    WHERE id = $category_id
");

if (!$result || $result->num_rows === 0) {
    die("Category not found.");
}

$category = $result->fetch_assoc();


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $category_name = trim($_POST["category_name"] ?? "");

    if ($category_name !== "") {

        $category_name = $conn->real_escape_string($category_name);

        $check = $conn->query("
            SELECT id
            FROM categories
            WHERE name = '$category_name'
            AND id != $category_id
        ");

        if ($check->num_rows === 0) {

            $conn->query("
                UPDATE categories
                SET name = '$category_name'
                WHERE id = $category_id
            ");

            header("Location: manage_categories.php");
            exit;

        } else {

            $error = "This category already exists.";

        }

    } else {

        $error = "Category name cannot be empty.";

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Category - Rwanda Smart Hub</title>

    <link rel="stylesheet"
          href="../assets/style.css">

</head>

<body>

<div class="admin-dashboard">

    <h1>Edit Category</h1>

    <a href="manage_categories.php">
        ← Back to Categories
    </a>

    <hr>

    <?php if (isset($error)): ?>

        <p>
            <?php echo htmlspecialchars($error); ?>
        </p>

    <?php endif; ?>

    <form method="POST">

        <input
            type="text"
            name="category_name"
            value="<?php echo htmlspecialchars($category["name"]); ?>"
            required
        >

        <button type="submit">
            Save Changes
        </button>

    </form>

</div>

</body>

</html>