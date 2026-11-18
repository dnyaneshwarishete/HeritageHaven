
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard | HeritageHaven</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f7f5f1;
            color: #222;
        }

        header {
            background: #171717;
            color: white;
            padding: 22px 6%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .brand {
            font-size: 24px;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }

        .brand span {
            color: #f2a261;
        }

        header a.logout {
            color: white;
            text-decoration: none;
        }

        main {
            width: min(1000px, 92%);
            margin: 45px auto;
        }

        .welcome {
            padding: 40px;
            border-radius: 20px;
            color: white;
            background: linear-gradient(120deg, #171717, #65442f);
        }

        .welcome .eyebrow {
            color: #f2a261;
            letter-spacing: 2px;
            font-size: 13px;
            font-weight: bold;
        }

        .welcome h1 {
            font-size: clamp(28px, 5vw, 42px);
            margin: 12px 0;
        }

        .welcome p {
            line-height: 1.7;
        }

        .button {
            display: inline-block;
            margin-top: 12px;
            padding: 13px 18px;
            background: #f2a261;
            color: #171717;
            border-radius: 9px;
            text-decoration: none;
            font-weight: bold;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-top: 25px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
        }

        .card p {
            color: #666;
            line-height: 1.6;
        }

        .card .button {
            background: #171717;
            color: white;
        }

        .card .button:hover {
            background: #a65b20;
        }

        @media (max-width: 500px) {
            .welcome {
                padding: 25px;
            }
        }
    </style>
</head>

<body>

<header>
    <a href="index.php" class="brand">
        ✦ Heritage<span>Haven</span>
    </a>

    <a href="logout.php" class="logout">Log Out</a>
</header>

<main>
    <section class="welcome">
        <p class="eyebrow">YOUR TRAVEL SPACE</p>

        <h1>
            Welcome, <?= h($_SESSION['user_name'] ?? 'Traveller') ?>!
        </h1>

        <p>
            Discover India's heritage, explore cultural experiences,
            and plan your next memorable journey.
        </p>

        <a href="booking.php" class="button">
            Plan Your Journey →
        </a>
    </section>

    <section class="cards">

        <article class="card">
            <h2>My Bookings</h2>
            <p>View your submitted travel booking requests.</p>
            <a href="my_bookings.php" class="button">View Bookings</a>
        </article>

        <article class="card">
            <h2>Explore Destinations</h2>
            <p>Discover heritage sites and cultural destinations.</p>
            <a href="heritage.php" class="button">Explore Now</a>
        </article>

        <article class="card">
            <h2>Book an Experience</h2>
            <p>Choose a destination, travel date, and experience.</p>
            <a href="booking.php" class="button">Book Now</a>
        </article>

    </section>
</main>

</body>
</html>
