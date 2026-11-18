
<?php
session_start();
require_once 'includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | HeritageHaven</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            color: #2d2925;
            background: #faf8f4;
            line-height: 1.7;
        }

        header {
            background: #fff;
            padding: 18px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            box-shadow: 0 2px 12px #0000000d;
        }

        .logo {
            font-size: 25px;
            font-weight: bold;
            color: #8b4513;
            text-decoration: none;
        }

        nav {
            display: flex;
            flex-wrap: wrap;
            gap: 22px;
        }

        nav a {
            text-decoration: none;
            color: #333;
            font-size: 14px;
            font-weight: 600;
        }

        nav a:hover {
            color: #b56a2d;
        }

        .hero {
            min-height: 430px;
            padding: 90px 8%;
            display: flex;
            align-items: center;
            color: white;
            background:
                linear-gradient(90deg, rgba(25,20,15,.85),
                rgba(25,20,15,.25)),
                url('https://images.unsplash.com/photo-1606293926075-69a00dbfde81?auto=format&fit=crop&w=1800&q=85')
                center/cover no-repeat;
        }

        .hero-content {
            max-width: 720px;
        }

        .tag {
            color: #f5c58b;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-size: 13px;
            font-weight: bold;
        }

        .hero h1 {
            font-size: clamp(36px, 5vw, 58px);
            line-height: 1.15;
            margin: 18px 0;
        }

        .hero p {
            font-size: 17px;
            max-width: 620px;
            color: #f4eee8;
            margin-bottom: 28px;
        }

        .btn {
            display: inline-block;
            padding: 13px 25px;
            border-radius: 5px;
            background: #b56a2d;
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .btn:hover {
            background: #8b4513;
        }

        .section {
            padding: 65px 8%;
        }

        .section-title {
            text-align: center;
            margin-bottom: 35px;
        }

        .section-title span {
            color: #a35b27;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 12px;
            font-weight: bold;
        }

        .section-title h2 {
            font-size: 34px;
            margin-top: 8px;
        }

        .intro {
            max-width: 900px;
            margin: auto;
            text-align: center;
            font-size: 17px;
            color: #625c55;
        }

        .mission {
            background: #eee7dc;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: center;
        }

        .mission h2 {
            font-size: 32px;
            margin: 10px 0 18px;
        }

        .mission p {
            color: #625c55;
            margin-bottom: 14px;
        }

        .mission-image {
            width: 100%;
            height: 340px;
            object-fit: cover;
            border-radius: 12px;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 22px;
        }

        .feature-card {
            background: white;
            padding: 28px 24px;
            border-radius: 10px;
            box-shadow: 0 5px 22px #00000008;
            border: 1px solid #eee7df;
        }

        .feature-icon {
            font-size: 32px;
            margin-bottom: 12px;
        }

        .feature-card h3 {
            margin-bottom: 10px;
            color: #78401e;
        }

        .feature-card p {
            color: #6d655e;
            font-size: 14px;
        }

        .vision {
            text-align: center;
            background: #493021;
            color: white;
            padding: 65px 8%;
        }

        .vision h2 {
            font-size: 32px;
            margin-bottom: 15px;
        }

        .vision p {
            max-width: 750px;
            margin: 0 auto 25px;
            color: #eee0d2;
        }

        footer {
            text-align: center;
            padding: 22px;
            background: #241a14;
            color: #ddd0c4;
            font-size: 13px;
        }

        @media (max-width: 768px) {
            header {
                justify-content: center;
            }

            nav {
                justify-content: center;
                gap: 14px;
            }

            .hero {
                padding: 65px 7%;
            }

            .mission,
            .features {
                grid-template-columns: 1fr;
            }

            .section {
                padding: 45px 6%;
            }

            .mission-image {
                height: 260px;
            }

            .section-title h2 {
                font-size: 28px;
            }
        }
    </style>
</head>

<body>

<header>
    <a class="logo" href="index.php">HeritageHaven</a>

    <nav>
        <a href="index.php">Home</a>
        <a href="heritage.php">Destinations</a>
        <a href="about.php">About Us</a>
        <a href="booking.php">Book a Trip</a>
        <a href="login.php">Login</a>
    </nav>
</header>

<section class="hero">
    <div class="hero-content">
        <div class="tag">Our Story • Our Heritage</div>

        <h1>Discover the Stories Behind India's Heritage</h1>

        <p>
            Explore magnificent forts, ancient monuments and vibrant
            traditions. HeritageHaven helps you discover India's history
            and plan meaningful travel experiences.
        </p>

        <a href="heritage.php" class="btn">Explore Destinations →</a>
    </div>
</section>

<section class="section">
    <div class="section-title">
        <span>Who We Are</span>
        <h2>Welcome to HeritageHaven</h2>
    </div>

    <p class="intro">
        HeritageHaven is a heritage tourism platform created to help
        travelers discover India's historical places, cultural landmarks
        and local experiences. Our website brings destination information,
        tour guide discovery and travel booking together in one place,
        making it easier to plan your next journey.
    </p>
</section>

<section class="section mission">
    <div>
        <span class="tag">Our Mission</span>

        <h2>Making Every Journey Meaningful</h2>

        <p>
            India's heritage tells stories of courage, architecture,
            traditions and history. We want to make these stories easier
            for travelers to discover and appreciate.
        </p>

        <p>
            Through HeritageHaven, visitors can explore destinations,
            learn about their historical importance, discover local guides
            and submit travel booking requests.
        </p>

        <a href="booking.php" class="btn">Plan Your Journey</a>
    </div>

    <img
        class="mission-image"
        src="https://images.unsplash.com/photo-1477587458883-47145ed94245?auto=format&fit=crop&w=1000&q=85"
        alt="Historic Indian architecture"
        loading="lazy"
    >
</section>

<section class="section">
    <div class="section-title">
        <span>Why Choose Us</span>
        <h2>What We Offer</h2>
    </div>

    <div class="features">
        <div class="feature-card">
            <div class="feature-icon">🏰</div>
            <h3>Explore Heritage</h3>
            <p>
                Discover forts, monuments and cultural destinations
                with useful information about each place.
            </p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">🧭</div>
            <h3>Discover Local Guides</h3>
            <p>
                Find available tour guides and learn about their
                experience, languages and specializations.
            </p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">🗓️</div>
            <h3>Easy Travel Booking</h3>
            <p>
                Submit your destination, travel date, group size
                and other details through our booking form.
            </p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">📍</div>
            <h3>Destination Information</h3>
            <p>
                Learn about places you want to visit and use available
                location information to plan your trip.
            </p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">🎭</div>
            <h3>Experience Culture</h3>
            <p>
                Discover the architecture, traditions and historical
                significance that make each destination unique.
            </p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">💻</div>
            <h3>Simple Online Platform</h3>
            <p>
                Access destination details and manage travel-related
                activities through an easy-to-use website.
            </p>
        </div>
    </div>
</section>

<section class="vision">
    <h2>Our Vision</h2>

    <p>
        We envision a world where every traveler can connect with
        India's incredible history, appreciate its cultural diversity
        and help preserve its heritage for future generations.
    </p>

    <a href="heritage.php" class="btn">Start Exploring</a>
</section>

<footer>
    <p>© <?php echo date('Y'); ?> HeritageHaven. All rights reserved.</p>
    <p>Explore the Past. Experience the Culture. Create Memories.</p>
</footer>

</body>
</html>
