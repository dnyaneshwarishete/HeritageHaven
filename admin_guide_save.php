
<?php
session_start();
require_once "includes/db.php";

if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['user_role']) ||
    $_SESSION['user_role'] !== 'admin'
) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: admin_guides.php");
    exit;
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
$name = trim($_POST["name"] ?? "");
$city = trim($_POST["city"] ?? "");
$languages = trim($_POST["languages"] ?? "");
$specialization = trim($_POST["specialization"] ?? "");
$experience = filter_input(INPUT_POST, "experience_years", FILTER_VALIDATE_INT);
$bio = trim($_POST["bio"] ?? "");
$photoUrl = trim($_POST["photo_url"] ?? "");
$status = $_POST["status"] ?? "approved";

if (
    $name === "" || $city === "" || $languages === "" ||
    $specialization === "" || $bio === "" ||
    $experience === false || $experience === null ||
    $experience < 0 || $experience > 80 ||
    !in_array($status, ["approved", "pending", "rejected"], true)
) {
    exit("Invalid input. Please go back and check all required fields.");
}

if ($photoUrl !== "" && !filter_var($photoUrl, FILTER_VALIDATE_URL)) {
    exit("Please enter a valid photo URL or leave it empty.");
}

if ($photoUrl !== "") {
    $scheme = strtolower((string)parse_url($photoUrl, PHP_URL_SCHEME));
    if (!in_array($scheme, ["http", "https"], true)) {
        exit("Photo URLs must use HTTP or HTTPS.");
    }
}

if ($id) {
    $stmt = $conn->prepare(
        "UPDATE guides
         SET name=?, city=?, languages=?, specialization=?,
             experience_years=?, bio=?, photo_url=?, status=?
         WHERE id=?"
    );

    $stmt->bind_param(
        "ssssisssi",
        $name, $city, $languages, $specialization,
        $experience, $bio, $photoUrl, $status, $id
    );

    $successMessage = "Guide profile updated successfully.";
} else {
    $stmt = $conn->prepare(
        "INSERT INTO guides
         (name, city, languages, specialization, experience_years,
          bio, photo_url, status)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "ssssisss",
        $name, $city, $languages, $specialization,
        $experience, $bio, $photoUrl, $status
    );

    $successMessage = "Guide added successfully.";
}

if ($stmt->execute()) {
    $stmt->close();
    header("Location: admin_guides.php?message=" . urlencode($successMessage));
    exit;
}

$stmt->close();
http_response_code(500);
echo "Unable to save the guide. Please check your database and try again.";
?>
