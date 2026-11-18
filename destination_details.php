
<?php
require_once 'includes/db.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header('Location: heritage.php');
    exit;
}

$stmt = $conn->prepare("SELECT * FROM destinations WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$place = $result->fetch_assoc();

if (!$place) {
    $stmt->close();
    $conn->close();
    http_response_code(404);
    exit('Destination not found. Please return to the Explore page.');
}

function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($place['name']) ?> | HeritageHaven</title>

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
            line-height: 1.7;
        }

        nav {
            padding: 18px 6%;
            background: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            border-bottom: 1px solid #e9e5dc;
        }

        .brand {
            font-family: Georgia, serif;
            color: #245a43;
            font-size: 25px;
            font-weight: bold;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 22px;
            flex-wrap: wrap;
        }

        .nav-links a {
            color: #36433c;
            font-size: 14px;
            text-decoration: none;
        }

        .hero {
            min-height: 350px;
            display: flex;
            align-items: end;
            padding: 55px 7%;
            color: white;
            background:
                linear-gradient(
                    0deg,
                    rgba(15, 32, 24, 0.88),
                    rgba(15, 32, 24, 0.12)
                ),
                #617565;
            background-size: cover;
            background-position: center;
        }

        .hero.has-image {
            background-image:
                linear-gradient(
                    0deg,
                    rgba(15, 32, 24, 0.88),
                    rgba(15, 32, 24, 0.12)
                ),
                var(--destination-image);
        }

        .hero-content {
            max-width: 850px;
        }

        .tag {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.18);
            font-size: 12px;
        }

        h1 {
            margin: 14px 0 5px;
            font-family: Georgia, serif;
            font-size: clamp(36px, 6vw, 58px);
            font-weight: normal;
            line-height: 1.15;
        }

        .location {
            font-size: 16px;
        }

        main {
            max-width: 1150px;
            margin: 45px auto 70px;
            padding: 0 25px;
        }

        .layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 310px;
            gap: 30px;
            align-items: start;
        }

        .content-card,
        .info-card {
            background: white;
            padding: 28px;
            margin-bottom: 24px;
            border: 1px solid #ebe7df;
            border-radius: 13px;
        }

        h2 {
            margin-bottom: 15px;
            font-family: Georgia, serif;
            font-size: 27px;
            font-weight: normal;
        }

        .description {
            color: #5f6d64;
        }

        .info-row {
            padding: 14px 0;
            border-bottom: 1px solid #eeeae3;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            display: block;
            color: #9a6a35;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.7px;
        }

        .info-value {
            display: block;
            margin-top: 4px;
            color: #35443a;
        }

        .map-link {
            display: inline-block;
            margin-top: 18px;
            color: #245a43;
            font-weight: bold;
            text-decoration: none;
        }

        .button {
            display: block;
            padding: 14px 18px;
            margin-top: 20px;
            border-radius: 8px;
            background: #245a43;
            color: white;
            text-align: center;
            text-decoration: none;
            font-weight: bold;
        }

        .button:hover {
            background: #19432f;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 24px;
            color: #245a43;
            text-decoration: none;
        }

        footer {
            padding: 28px 6%;
            background: #1e352b;
            color: #e5ebe5;
            text-align: center;
            font-size: 13px;
        }

        @media (max-width: 800px) {
            .layout {
                grid-template-columns: 1fr;
            }

            .hero {
                min-height: 300px;
                padding: 40px 6%;
            }

            main {
                margin-top: 30px;
                padding: 0 16px;
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
    </div>
</nav>

<section
    class="hero <?= !empty($place['image_url']) ? 'has-image' : '' ?>"
    <?php if (!empty($place['image_url'])): ?>
        style="--destination-image: url('<?= e($place['image_url']) ?>');"
    <?php endif; ?>
>
    <div class="hero-content">
        <span class="tag"><?= e($place['category']) ?></span>

        <h1><?= e($place['name']) ?></h1>

        <p class="location">📍 <?= e($place['state']) ?>, India</p>
    </div>
</section>

<main>
    <a class="back-link" href="heritage.php">← Back to Explore</a>

    <div class="layout">
        <div>
            <section class="content-card">
                <h2>About This Destination</h2>

                <p class="description">
                    <?= nl2br(e($place['short_description'])) ?>
                </p>
            </section>

            <?php if (!empty($place['history'])): ?>
                <section class="content-card">
                    <h2>History & Heritage</h2>

                    <p class="description">
                        <?= nl2br(e($place['history'])) ?>
                    </p>
                </section>
            <?php endif; ?>

            <?php if (
                $place['latitude'] !== null &&
                $place['longitude'] !== null
            ): ?>
                <section class="content-card">
                    <h2>Find This Destination</h2>

                    <p class="description">
                        View the destination's location on OpenStreetMap.
                    </p>

                    <a
                        class="map-link"
                        href="https://www.openstreetmap.org/?mlat=<?= e($place['latitude']) ?>&mlon=<?= e($place['longitude']) ?>#map=14/<?= e($place['latitude']) ?>/<?= e($place['longitude']) ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        View Map Location →
                    </a>
                </section>
            <?php endif; ?>
        </div>

        <aside>
            <section class="info-card">
                <h2>Plan Your Visit</h2>

                <div class="info-row">
                    <span class="info-label">Location</span>
                    <span class="info-value"><?= e($place['state']) ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label">Best Time to Visit</span>
                    <span class="info-value">
                        <?= e($place['best_time'] ?: 'Check before visiting') ?>
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">Suggested Duration</span>
                    <span class="info-value">
                        <?= e($place['visit_duration'] ?: 'Depends on your itinerary') ?>
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">Opening Hours</span>
                    <span class="info-value">
                        <?= e($place['opening_hours'] ?: 'Verify before visiting') ?>
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">Entry Fee</span>
                    <span class="info-value">
                        <?= e($place['entry_fee'] ?: 'Verify current fee') ?>
                    </span>
                </div>

                <a
                    class="button"
                    href="booking.php?destination=<?= urlencode($place['name']) ?>"
                >
                    Plan & Book Your Visit
                </a>
            </section>
        </aside>
    </div>
</main>

<footer>
    © <?= date('Y') ?> HeritageHaven · Discover India's Timeless Heritage
</footer>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
