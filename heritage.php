
<?php
require_once 'includes/db.php';

$search = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');

// Exclude the four destinations already featured on the homepage.
$sql = "SELECT * FROM destinations
        WHERE name NOT IN (
            'Taj Mahal',
            'Red Fort',
            'Jaipur Palace',
            'Ajanta Caves'
        )";

$params = [];
$types = '';

if ($search !== '') {
    $sql .= " AND (
        name LIKE ?
        OR state LIKE ?
        OR short_description LIKE ?
    )";

    $term = '%' . $search . '%';

    $params[] = $term;
    $params[] = $term;
    $params[] = $term;

    $types .= 'sss';
}

if ($category !== '') {
    $sql .= " AND category = ?";
    $params[] = $category;
    $types .= 's';
}

$sql .= " ORDER BY name ASC";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$categories = $conn->query(
    "SELECT DISTINCT category
     FROM destinations
     WHERE name NOT IN (
         'Taj Mahal',
         'Red Fort',
         'Jaipur Palace',
         'Ajanta Caves'
     )
     ORDER BY category ASC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Explore Heritage Sites | HeritageHaven</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f8f6f1;
            color: #26332e;
        }

        nav {
            min-height: 76px;
            padding: 18px 6%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            background: #fff;
            border-bottom: 1px solid #e9e5dc;
        }

        .brand {
            color: #245a43;
            font-family: Georgia, serif;
            font-size: 25px;
            font-weight: bold;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 22px;
            flex-wrap: wrap;
        }

        .nav-links a {
            color: #36433c;
            text-decoration: none;
            font-size: 14px;
        }

        .nav-links a:hover {
            color: #b77a36;
        }

        .hero {
            padding: 70px 6% 55px;
            text-align: center;
            background: #e9eee6;
        }

        .eyebrow {
            color: #a56d2e;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .hero h1 {
            margin: 16px 0;
            font-family: Georgia, serif;
            font-size: clamp(34px, 5vw, 54px);
            font-weight: normal;
        }

        .hero p {
            max-width: 680px;
            margin: auto;
            color: #657168;
            line-height: 1.8;
            font-size: 15px;
        }

        .search-area {
            max-width: 900px;
            margin: 30px auto 0;
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .search-area input,
        .search-area select {
            min-width: 190px;
            flex: 1;
            padding: 15px;
            border: 1px solid #d9ded6;
            border-radius: 8px;
            background: #fff;
            font-size: 14px;
            outline-color: #245a43;
        }

        .search-area button {
            padding: 15px 25px;
            background: #245a43;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }

        .search-area button:hover {
            background: #19432f;
        }

        .clear-link {
            display: inline-block;
            margin-top: 16px;
            color: #245a43;
            font-size: 14px;
            text-decoration: none;
        }

        .clear-link:hover {
            text-decoration: underline;
        }

        .destinations {
            padding: 55px 6% 80px;
        }

        .section-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .section-heading h2 {
            font-family: Georgia, serif;
            font-size: 32px;
            font-weight: normal;
        }

        .count {
            color: #68756d;
            font-size: 14px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 26px;
        }

        .card {
            overflow: hidden;
            background: #fff;
            border: 1px solid #ebe7df;
            border-radius: 13px;
            box-shadow: 0 5px 18px rgba(31, 49, 39, 0.05);
            transition: transform 0.25s, box-shadow 0.25s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 28px rgba(31, 49, 39, 0.12);
        }

        .card-image {
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #e9eee6;
            color: #536b5b;
            font-size: 14px;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s;
        }

        .card:hover .card-image img {
            transform: scale(1.04);
        }

        .card-content {
            padding: 23px;
        }

        .tag {
            display: inline-block;
            padding: 7px 11px;
            background: #edf3ed;
            color: #245a43;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }

        .card h3 {
            margin: 15px 0 8px;
            font-family: Georgia, serif;
            font-size: 23px;
            font-weight: normal;
        }

        .location {
            margin-bottom: 13px;
            color: #a56d2e;
            font-size: 13px;
        }

        .description {
            min-height: 72px;
            color: #68756d;
            font-size: 14px;
            line-height: 1.75;
        }

        .card-bottom {
            margin-top: 20px;
            padding-top: 17px;
            border-top: 1px solid #eeeae3;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .duration {
            color: #68756d;
            font-size: 12px;
        }

        .details-link {
            color: #245a43;
            font-size: 13px;
            font-weight: bold;
            text-decoration: none;
            white-space: nowrap;
        }

        .details-link:hover {
            color: #b77a36;
        }

        .empty {
            grid-column: 1 / -1;
            padding: 55px 20px;
            text-align: center;
            background: #fff;
            border-radius: 12px;
            line-height: 2;
        }

        footer {
            padding: 30px 6%;
            background: #1e352b;
            color: #e5ebe5;
            text-align: center;
            font-size: 13px;
        }

        @media (max-width: 1000px) {
            .grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 620px) {
            nav {
                padding: 18px 5%;
            }

            .nav-links {
                gap: 15px;
            }

            .hero {
                padding: 48px 5%;
            }

            .destinations {
                padding: 38px 5% 55px;
            }

            .grid {
                grid-template-columns: 1fr;
            }

            .search-area input,
            .search-area select,
            .search-area button {
                width: 100%;
            }

            .section-heading h2 {
                font-size: 27px;
            }
        }
    </style>
</head>

<body>

<nav>
    <a class="brand" href="index.php">✦ HeritageHaven</a>

    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="heritage.php">Explore</a>
        <a href="booking.php">Bookings</a>
        <a href="login.php">Log In</a>
    </div>
</nav>

<section class="hero">
    <div class="eyebrow">Discover India's Heritage</div>

    <h1>Explore Timeless India</h1>

    <p>
        Discover magnificent forts, ancient caves, sacred sites and
        historic monuments. Find a new destination for your next journey
        through India's rich cultural heritage.
    </p>

    <form class="search-area" action="heritage.php" method="GET">
        <input
            type="text"
            name="search"
            placeholder="Search destination or state..."
            value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>"
        >

        <select name="category">
            <option value="">All Categories</option>

            <?php while ($cat = $categories->fetch_assoc()): ?>
                <option
                    value="<?= htmlspecialchars($cat['category'], ENT_QUOTES, 'UTF-8') ?>"
                    <?= $category === $cat['category'] ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($cat['category'], ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endwhile; ?>
        </select>

        <button type="submit">Search Sites</button>
    </form>

    <?php if ($search !== '' || $category !== ''): ?>
        <a class="clear-link" href="heritage.php">
            Clear search and filters
        </a>
    <?php endif; ?>
</section>

<section class="destinations">
    <div class="section-heading">
        <h2>Discover More Destinations</h2>

        <span class="count">
            <?= $result->num_rows ?> destinations found
        </span>
    </div>

    <div class="grid">

        <?php if ($result->num_rows > 0): ?>

            <?php while ($place = $result->fetch_assoc()): ?>

                <article class="card">

                    <div class="card-image">
                        <?php if (!empty($place['image_url'])): ?>
                            <img
                                src="<?= htmlspecialchars($place['image_url'], ENT_QUOTES, 'UTF-8') ?>"
                                alt="<?= htmlspecialchars($place['name'], ENT_QUOTES, 'UTF-8') ?>"
                                loading="lazy"
                            >
                        <?php else: ?>
                            <span>Image coming soon</span>
                        <?php endif; ?>
                    </div>

                    <div class="card-content">

                        <span class="tag">
                            <?= htmlspecialchars($place['category'], ENT_QUOTES, 'UTF-8') ?>
                        </span>

                        <h3>
                            <?= htmlspecialchars($place['name'], ENT_QUOTES, 'UTF-8') ?>
                        </h3>

                        <div class="location">
                            📍 <?= htmlspecialchars($place['state'], ENT_QUOTES, 'UTF-8') ?>
                        </div>

                        <p class="description">
                            <?= htmlspecialchars($place['short_description'], ENT_QUOTES, 'UTF-8') ?>
                        </p>

                        <div class="card-bottom">

                            <span class="duration">
                                <?= htmlspecialchars(
                                    $place['visit_duration'] ?: 'Duration varies',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </span>

                            <a
                                class="details-link"
                                href="destination_details.php?id=<?= (int) $place['id'] ?>"
                            >
                                Explore Details →
                            </a>

                        </div>
                    </div>
                </article>

            <?php endwhile; ?>

        <?php else: ?>

            <div class="empty">
                <h3>No destinations found</h3>

                <p>
                    Try another destination name or choose a different category.
                </p>

                <a class="clear-link" href="heritage.php">
                    View all destinations
                </a>
            </div>

        <?php endif; ?>

    </div>
</section>

<footer>
    © <?= date('Y') ?> HeritageHaven · Discover India's Timeless Heritage
</footer>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
