
<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_admin"])) {

    $delete_id = (int)($_POST["admin_id"] ?? 0);
    $current_admin_id = (int)$_SESSION["admin_id"];

    if ($delete_id === $current_admin_id) {

        $message = "You cannot delete your own account.";

    } else {

        $count_result = $conn->query("SELECT COUNT(*) AS total FROM admins");
        $admin_count = (int)$count_result->fetch_assoc()["total"];

        if ($admin_count <= 1) {

            $message = "You cannot delete the last administrator.";

        } else {

            $delete_result = $conn->query("
                DELETE FROM admins
                WHERE id = $delete_id
            ");

            if ($delete_result && $conn->affected_rows > 0) {
                $message = "Administrator deleted successfully.";
            } else {
                $message = "Administrator could not be deleted.";
            }
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || $password === "") {

        $message = "Please fill in all fields.";

    } elseif (strlen($password) < 8) {

        $message = "Password must contain at least 8 characters.";

    } else {

        $username_sql = $conn->real_escape_string($username);

        $check = $conn->query("
            SELECT id
            FROM admins
            WHERE username = '$username_sql'
        ");

        if (!$check) {

            $message = "Could not check admins account. Verify your database table.";

        } elseif ($check->num_rows > 0) {

            $message = "That username already exists.";

        } else {

            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $password_sql = $conn->real_escape_string($password_hash);

            $insert = $conn->query("
                INSERT INTO admins (username, password)
                VALUES ('$username_sql', '$password_sql')
            ");

            if ($insert) {
                $message = "Admins added successfully.";
            } else {
                $message = "Could not add admins. Check your table columns.";
            }
        }
    }
}

$result = $conn->query("
    SELECT id, username
    FROM admins
    ORDER BY username ASC
");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Administrators - Rwanda Smart Hub</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>

<div class="admin-dashboard">

    <h1>Manage Administrators</h1>

    <p>
        <a href="dashboard.php">← Back to Dashboard</a>
    </p>

    <hr>

    <h2>Add Administrator</h2>

    <?php if ($message !== ""): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="POST">

        <p>
            <input
                type="text"
                name="username"
                placeholder="Administrator username"
                required
            >
        </p>

        <p>
            <input
                type="password"
                name="password"
                placeholder="Password (at least 8 characters)"
                minlength="8"
                required
            >
        </p>

        <button type="submit">Add Administrator</button>

    </form>

    <hr>

    <h2>Existing Administrators</h2>

    <?php if ($result): ?>

        
<?php while ($admin = $result->fetch_assoc()): ?>

    <div class="information-card">

        <h3>
            <?php echo htmlspecialchars($admin["username"]); ?>
        </h3>

        <?php if ((int)$admin["id"] !== (int)$_SESSION["admin_id"]): ?>

            <form method="POST"
                  onsubmit="return confirm('Are you sure you want to delete this administrator?');">

                <input
                    type="hidden"
                    name="admin_id"
                    value="<?php echo (int)$admin["id"]; ?>"
                >

                <button type="submit" name="delete_admin">
                    Delete Administrator
                </button>

            </form>

        <?php else: ?>

            <p>Current account</p>

        <?php endif; ?>

    </div>

<?php endwhile; ?>

    <?php else: ?>

        <p>Could not load administrators. Check your database table.</p>

    <?php endif; ?>

</div>

</body>
</html>