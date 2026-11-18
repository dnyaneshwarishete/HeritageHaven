
<?php
require_once "includes/db.php";

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$search = trim($_GET["search"] ?? "");
$city = trim($_GET["city"] ?? "");

$sql = "SELECT id, name, city, languages, specialization,
               experience_years, bio, photo_url
        FROM guides
        WHERE status = 'approved'";

$params = [];
$types = "";

if ($search !== "") {
    $sql .= " AND (name LIKE ? OR city LIKE ?
              OR specialization LIKE ? OR languages LIKE ?)";
    $term = "%" . $search . "%";
    $params = array_merge($params, [$term, $term, $term, $term]);
    $types .= "ssss";
}

if ($city !== "") {
    $sql .= " AND city = ?";
    $params[] = $city;
    $types .= "s";
}

$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$guides = [];
while ($row = $result->fetch_assoc()) {
    $guides[] = $row;
}
$stmt->close();

$cityResult = $conn->query(
    "SELECT DISTINCT city FROM guides
     WHERE status = 'approved' ORDER BY city"
);

$cities = [];
while ($row = $cityResult->fetch_assoc()) {
    $cities[] = $row["city"];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Local Guides | HeritageHaven</title>

<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

<style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: 'DM Sans', sans-serif;
    background: #f5f6f3;
    color: #26342e;
}

nav {
    background: #203c32;
    color: white;
    padding: 20px 6%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
}

.brand {
    font: 700 26px 'Playfair Display', serif;
}

nav a {
    color: white;
    text-decoration: none;
    margin-left: 18px;
    font-size: 14px;
}

.hero {
    text-align: center;
    padding: 65px 20px 45px;
    background: #e8eee6;
}

.hero h1 {
    font: 700 clamp(32px, 5vw, 46px) 'Playfair Display', serif;
    margin-bottom: 12px;
}

.hero p {
    color: #657269;
    line-height: 1.7;
}

.container {
    width: min(1150px, 92%);
    margin: 35px auto 65px;
}

.search-box {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    background: white;
    padding: 18px;
    border-radius: 12px;
    margin-bottom: 30px;
    border: 1px solid #e3e8e1;
}

input, select {
    flex: 1;
    min-width: 170px;
    padding: 13px;
    border: 1px solid #dce2dc;
    border-radius: 7px;
    font: inherit;
}

button, .button {
    border: 0;
    border-radius: 7px;
    padding: 13px 20px;
    background: #b98a52;
    color: white;
    font: inherit;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
}

.grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 24px;
}

.card {
    background: white;
    border: 1px solid #e5e9e3;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 6px 20px #203c3208;
    display: flex;
    flex-direction: column;
}

.photo {
    width: 100%;
    height: 220px;
    object-fit: cover;
    background: #e5ece5;
}

.placeholder {
    height: 220px;
    display: grid;
    place-items: center;
    background: #e5ece5;
    color: #203c32;
    font: 700 65px 'Playfair Display', serif;
}

.details {
    padding: 22px;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.details h2 {
    font: 700 23px 'Playfair Display', serif;
    margin-bottom: 8px;
}

.location {
    color: #8c693d;
    font-size: 14px;
    margin-bottom: 15px;
}

.tag {
    display: inline-block;
    background: #e8eee6;
    color: #315341;
    border-radius: 5px;
    padding: 6px 9px;
    font-size: 12px;
    margin-bottom: 13px;
    width: fit-content;
}

.info {
    font-size: 13px;
    color: #647067;
    line-height: 1.7;
    margin-bottom: 10px;
}

.bio {
    font-size: 14px;
    line-height: 1.7;
    color: #526057;
    margin-bottom: 20px;
    overflow-wrap: anywhere;
}

.card .button {
    margin-top: auto;
    text-align: center;
}

.empty {
    background: white;
    padding: 45px 20px;
    text-align: center;
    border-radius: 12px;
    line-height: 2;
}

footer {
    background: #203c32;
    color: #e7ece7;
    text-align: center;
    padding: 24px;
    font-size: 13px;
}

@media (max-width: 850px) {
    .grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 560px) {
    .grid {
        grid-template-columns: 1fr;
    }

    nav a {
        margin-left: 0;
        margin-right: 12px;
    }
}
</style>
</head>

<body>

<nav>
    <div class="brand">HeritageHaven</div>
    <div>
        <a href="index.php">Home</a>
        <a href="heritage.php">Explore</a>
        <a href="guides.php">Local Guides</a>
        <a href="booking.php">Bookings</a>
    </div>
</nav>

<section class="hero">
    <h1>Meet Our Local Guides</h1>
    <p>Discover India's heritage with people who know its history, culture and hidden stories.</p>
</section>

<main class="container">

    <form class="search-box" method="GET" action="guides.php">
        <input
            type="search"
            name="search"
            placeholder="Search by name, city or expertise..."
            value="<?= e($search) ?>"
        >

        <select name="city">
            <option value="">All Locations</option>
            <?php foreach ($cities as $cityName): ?>
                <option value="<?= e($cityName) ?>"
                    <?= $city === $cityName ? 'selected' : '' ?>>
                    <?= e($cityName) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Search Guides</button>
        <a class="button" href="guides.php">Reset</a>
    </form>

    <?php if (count($guides) > 0): ?>

        <div class="grid">
            <?php foreach ($guides as $guide): ?>
                <article class="card">

                    <?php if (!empty($guide["photo_url"])): ?>
                        <img
                            class="photo"
                            src="<?= e($guide["photo_url"]) ?>"
                            alt="<?= e($guide["name"]) ?>"
                            loading="lazy"
                        >
                    <?php else: ?>
                        <div class="placeholder">
                            <?= e(strtoupper(substr($guide["name"], 0, 1))) ?>
                        </div>
                    <?php endif; ?>

                    <div class="details">
                        <h2><?= e($guide["name"]) ?></h2>

                        <p class="location">
                            &#128205; <?= e($guide["city"]) ?>
                        </p>

                        <span class="tag">
                            <?= e($guide["specialization"]) ?>
                        </span>

                        <p class="info">
                            <strong>Languages:</strong>
                            <?= e($guide["languages"]) ?>
                        </p>

                        <p class="info">
                            <strong>Experience:</strong>
                            <?= (int)$guide["experience_years"] ?> years
                        </p>

                        <p class="bio"><?= e($guide["bio"]) ?></p>

                        <a class="button" href="booking.php">
                            Plan Your Journey
                        </a>
                    </div>

                </article>
            <?php endforeach; ?>
        </div>

    <?php else: ?>

        <div class="empty">
            <h2>No approved guides found</h2>
            <p>Try another search or location, or check that a guide has been approved by the admin.</p>
        </div>

    <?php endif; ?>

</main>

<footer>
    HeritageHaven &copy; <?= date("Y") ?> | Discover India's Living Heritage
</footer>

</body>
</html>
