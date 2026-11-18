
<?php
require_once "includes/db.php";

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: guides.php");
    exit;
}

$stmt = $conn->prepare(
    "SELECT id, name, city, languages, specialization,
            experience_years, bio, photo_url
     FROM guides
     WHERE id = ? AND status = 'approved'
     LIMIT 1"
);

$stmt->bind_param("i", $id);
$stmt->execute();
$guide = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$guide) {
    http_response_code(404);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>
    <?= $guide ? e($guide['name']) . ' | HeritageHaven' : 'Guide Not Found' ?>
</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
<style>
* { box-sizing: border-box; }
body {
    margin: 0;
    background: #f5f6f3;
    color: #26342e;
    font-family: 'DM Sans', sans-serif;
}
nav {
    background: #203c32;
    color: white;
    padding: 20px 6%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}
.brand { font: 700 25px 'Playfair Display', serif; }
nav a { color: white; text-decoration: none; margin-left: 15px; }
.container { width: min(900px, 92%); margin: 45px auto; }
.profile {
    background: white;
    border-radius: 16px;
    padding: 30px;
    display: grid;
    grid-template-columns: minmax(0, 280px) 1fr;
    gap: 30px;
    border: 1px solid #e5e9e3;
}
.photo, .placeholder {
    width: 100%;
    height: 330px;
    object-fit: cover;
    border-radius: 12px;
    background: #e5ece5;
}
.placeholder {
    display: grid;
    place-items: center;
    color: #203c32;
    font: 700 80px 'Playfair Display', serif;
}
h1, h2 { font-family: 'Playfair Display', serif; }
h1 { margin: 0 0 10px; font-size: 34px; }
.location { color: #9a713f; margin-bottom: 22px; }
.tag {
    display: inline-block;
    background: #e8eee6;
    color: #315341;
    padding: 8px 12px;
    border-radius: 6px;
    margin-bottom: 18px;
}
p { line-height: 1.8; overflow-wrap: anywhere; }
.button {
    display: inline-block;
    background: #b98a52;
    color: white;
    padding: 12px 18px;
    border-radius: 7px;
    text-decoration: none;
    margin: 8px 8px 0 0;
}
.back { color: #315341; }
@media (max-width: 650px) {
    .profile { grid-template-columns: 1fr; padding: 20px; }
    .photo, .placeholder { height: 280px; }
    h1 { font-size: 28px; }
}
</style>
</head>
<body>
<nav>
    <div class="brand">HeritageHaven</div>
    <div>
        <a href="index.php">Home</a>
        <a href="guides.php">All Guides</a>
    </div>
</nav>

<main class="container">
<?php if ($guide): ?>
    <article class="profile">
        <div>
            <?php if (!empty($guide['photo_url'])): ?>
                <img class="photo"
                     src="<?= e($guide['photo_url']) ?>"
                     alt="<?= e($guide['name']) ?>">
            <?php else: ?>
                <div class="placeholder">
                    <?= e(strtoupper(substr($guide['name'], 0, 1))) ?>
                </div>
            <?php endif; ?>
        </div>

        <div>
            <h1><?= e($guide['name']) ?></h1>
            <div class="location">
                &#128205; <?= e($guide['city']) ?>
            </div>

            <div class="tag"><?= e($guide['specialization']) ?></div>

            <p><strong>Languages:</strong> <?= e($guide['languages']) ?></p>
            <p><strong>Experience:</strong>
                <?= (int)$guide['experience_years'] ?> years
            </p>

            <h2>About the Guide</h2>
            <p><?= nl2br(e($guide['bio'])) ?></p>

            <a class="button" href="booking.php">
                Plan Your Journey
            </a>
            <a class="back" href="guides.php">Back to All Guides</a>
        </div>
    </article>
<?php else: ?>
    <h1>Guide Not Found</h1>
    <p>This guide may not exist or may not be approved yet.</p>
    <a class="button" href="guides.php">Browse All Guides</a>
<?php endif; ?>
</main>
</body>
</html>
