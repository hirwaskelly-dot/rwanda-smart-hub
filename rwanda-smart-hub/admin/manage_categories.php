<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["add_category"])) {

        // your existing add-category code

    }

    if (isset($_POST["delete_category"])) {

        $category_id = (int)($_POST["category_id"] ?? 0);

        $used = $conn->query("
            SELECT id
            FROM information
            WHERE category_id = $category_id
            LIMIT 1
        ");

       if ($used->num_rows === 0) {

    $conn->query("
        DELETE FROM categories
        WHERE id = $category_id
    ");

} else {

    echo "<script>
        alert('This category cannot be deleted because it is being used by information.');
    </script>";

}

    }

}



$result = $conn->query("
    SELECT *
    FROM categories
    ORDER BY name ASC
");

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Manage Categories - Rwanda Smart Hub</title>

    <link rel="stylesheet"
          href="../assets/style.css">

</head>

<body>

<div class="admin-dashboard">

    <h1>Manage Categories</h1>

    <a href="dashboard.php">
        ← Back to Dashboard
    </a>

    <hr>

    <h2>Categories</h2>

    <form method="POST">

        <input
            type="text"
            name="category_name"
            placeholder="Category name"
            required
        >

        <button type="submit" name="add_category">
    Add Category
</button>

    </form>

    <hr>

    <?php while ($category = $result->fetch_assoc()): ?>

        <div class="information-card">

    <h3>
        <?php
        echo htmlspecialchars($category["name"]);
        ?>
    </h3>

    <form method="POST"
          onsubmit="return confirm('Delete this category?');">

        <input
            type="hidden"
            name="category_id"
            value="<?php echo $category["id"]; ?>"
        >

        <button type="submit" name="delete_category">
            Delete
        </button>

    </form>
<a href="edit_categories.php?id=<?php echo $category["id"]; ?>">
    Edit
</a>
</div>

    <?php endwhile; ?>

</div>

</body>

</html>