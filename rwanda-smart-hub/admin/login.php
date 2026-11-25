
<?php

session_start();

require_once "../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    $stmt = $conn->prepare(
        "SELECT id, username, password FROM admins WHERE username = ? LIMIT 1"
    );

    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {

        $admin = $result->fetch_assoc();
        $stored_password = $admin["password"];

        // Check a hashed password first.
        $password_is_valid = password_verify(
            $password,
            $stored_password
        );

        // Support older accounts that still store plain-text passwords.
        if (
            !$password_is_valid &&
            hash_equals((string)$stored_password, (string)$password)
        ) {

            $password_is_valid = true;

            // Upgrade the old password to a secure hash.
            $new_hash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $update = $conn->prepare(
                "UPDATE admins SET password = ? WHERE id = ?"
            );

            $update->bind_param("si", $new_hash, $admin["id"]);
            $update->execute();
            $update->close();
        }

        if ($password_is_valid) {

            session_regenerate_id(true);

            $_SESSION["admin_id"] = $admin["id"];
            $_SESSION["admin_username"] = $admin["username"];

            header("Location: dashboard.php");
            exit;

        } else {

            $message = "Invalid username or password.";

        }

    } else {

        $message = "Invalid username or password.";

    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Login - Rwanda Smart Hub</title>
</head>

<body>

<h1>Rwanda Smart Hub</h1>

<h2>Admin Login</h2>

<?php

if ($message !== "") {
    echo "<p>" . htmlspecialchars($message) . "</p>";
}

?>

<form method="POST">

    <label>Username</label><br>
    <input type="text" name="username" required>

    <br><br>

    <label>Password</label><br>
    <input type="password" name="password" required>

    <br><br>

    <button type="submit">Login</button>

</form>

</body>

</html>