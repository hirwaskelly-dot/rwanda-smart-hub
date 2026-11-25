<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

require_once "../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST["name"] ?? "";
    $category_id = $_POST["category_id"] ?? "";
    $location_id = $_POST["location_id"] ?? "";
    $phone = $_POST["phone"] ?? "";
    $address = $_POST["address"] ?? "";
    $description = $_POST["description"] ?? "";

    $sql = "INSERT INTO information
            (category_id, location_id, name, phone, address, description)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "iissss",
        $category_id,
        $location_id,
        $name,
        $phone,
        $address,
        $description
    );

    if ($stmt->execute()) {
        $message = "Information added successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

$categories = $conn->query("SELECT * FROM categories ORDER BY name");

$locations = $conn->query(
    "SELECT * FROM locations ORDER BY province, district, sector"
);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Add Information - Rwanda Smart Hub</title>

</head>

<body>

<h1>Rwanda Smart Hub</h1>

<h2>Add Information</h2>

<?php

if ($message !== "") {
    echo "<p><strong>"
        . htmlspecialchars($message)
        . "</strong></p>";
}

?>

<form method="POST">

    <label>Name</label><br>

    <input
        type="text"
        name="name"
        required
    >

    <br><br>


    <label>Category</label><br>

    <select name="category_id" required>

        <option value="">Select category</option>

        <?php

        while ($category = $categories->fetch_assoc()) {

            echo "<option value='"
                . $category["id"]
                . "'>"
                . htmlspecialchars($category["name"])
                . "</option>";

        }

        ?>

    </select>

    <br><br>


    <label>Location</label><br>

    <select name="location_id" required>

        <option value="">Select location</option>

        <?php

        while ($location = $locations->fetch_assoc()) {

            $location_name =
                $location["province"]
                . " → "
                . $location["district"]
                . " → "
                . $location["sector"];

            echo "<option value='"
                . $location["id"]
                . "'>"
                . htmlspecialchars($location_name)
                . "</option>";

        }

        ?>

    </select>

    <br><br>


    <label>Phone</label><br>

    <input
        type="text"
        name="phone"
    >

    <br><br>


    <label>Address</label><br>

    <input
        type="text"
        name="address"
    >

    <br><br>


    <label>Description</label><br>

    <textarea
        name="description"
        rows="5"
        cols="40"
    ></textarea>

    <br><br>


    <button type="submit">
        Add Information
    </button>

</form>

<br>

<a href="dashboard.php">Back to Dashboard</a>

</body>

</html>