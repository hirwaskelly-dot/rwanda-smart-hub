<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: manage_information.php");
    exit();
}

$id = (int)($_POST["id"] ?? 0);
$name = $_POST["name"] ?? "";
$category_id = (int)($_POST["category_id"] ?? 0);
$location_id = (int)($_POST["location_id"] ?? 0);
$phone = $_POST["phone"] ?? "";
$address = $_POST["address"] ?? "";
$description = $_POST["description"] ?? "";

$sql = "
    UPDATE information
    SET
        name = ?,
        category_id = ?,
        location_id = ?,
        phone = ?,
        address = ?,
        description = ?
    WHERE id = ?
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "siisssi",
    $name,
    $category_id,
    $location_id,
    $phone,
    $address,
    $description,
    $id
);

if ($stmt->execute()) {

    $stmt->close();

    header("Location: manage_information.php");
    exit();

} else {

    echo "Error updating information: "
        . htmlspecialchars($stmt->error);

}

?>