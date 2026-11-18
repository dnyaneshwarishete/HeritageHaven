<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>HeritageHaven | Discover India's Heritage</title>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- Main CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <!-- ================= NAVBAR ================= -->

    <header class="navbar">

        <div class="logo">
            <span class="logo-icon">✦</span>
            <span>Heritage<span class="logo-highlight">Haven</span></span>
        </div>

        <nav class="nav-links">
            <a href="index.php" class="active">Home</a>
            <a href="heritage.php">Explore</a>
            <a href="guides.php">Local Guides</a>
            <a href="booking.php">Bookings</a>
            <a href="about.php">About</a>
        </nav>

        <div class="nav-actions">
            <button class="language-btn">EN ▾</button>
            <a href="login.php" class="login-btn">Log in</a>
            <a href="register.php" class="signup-btn">Get Started</a>
        </div>

    </header>


    <!-- ================= HERO ================= -->

    <main>

        <section class="hero">

            <!-- Background Image -->
            <div class="hero-background"></div>

            <!-- Dark Overlay -->
            <div class="hero-overlay"></div>

            <!-- Hero Content -->
            <div class="hero-content">

                <div class="hero-tag">
                    <span>✦</span>
                    Discover the soul of India
                </div>

                <h1>
                    Explore India's
                    <span>Timeless Heritage</span>
                </h1>

                <p>
                    Discover magnificent forts, ancient temples, royal palaces
                    and unforgettable experiences with passionate local guides.
                </p>


                <!-- Search Box -->

                <div class="search-box">

                    <div class="search-item">
                        <span class="search-icon">⌖</span>

                        <div>
                            <small>Where</small>
                            <input
                                type="text"
                                placeholder="Search a destination"
                            >
                        </div>
                    </div>


                    <div class="search-divider"></div>


                    <div class="search-item">
                        <span class="search-icon">◉</span>

                        <div>
                            <small>Experience</small>
                            <select>
                                <option>Heritage Sites</option>
                                <option>Local Guides</option>
                                <option>Cultural Experiences</option>
                                <option>Historical Tours</option>
                            </select>
                        </div>
                    </div>


                    <button class="search-button">
                        Search
                        <span>→</span>
                    </button>

                </div>


                <!-- Quick Categories -->

                <div class="quick-links">

                    <span>Popular:</span>

                    <a href="#">Forts</a>
                    <a href="#">Temples</a>
                    <a href="#">Palaces</a>
                    <a href="#">Local Experiences</a>

                </div>

            </div>


            <!-- Scroll Indicator -->

            <div class="scroll-indicator">
                <span></span>
                Scroll to explore
            </div>

        </section>

    </main>


    <!-- ================= JAVASCRIPT ================= -->

    <script src="assets/js/script.js"></script>

</body>
</html>


<!-- ================================
     EXPLORE INDIA SECTION
================================= -->

<section class="explore-section" id="explore">

    <div class="section-heading">
        <span class="section-tag">EXPLORE INDIA</span>

        <h2>Discover India's Timeless Treasures</h2>

        <p>
            Explore magnificent heritage sites, historic cities and
            unforgettable places across India.
        </p>
    </div>


    <div class="heritage-grid">

        <!-- Card 1 -->
        <div class="heritage-card">

            <div class="heritage-image">
                <img src="assets/images/heritage/taj-mahal.jpg"
                     alt="Taj Mahal">

                <span class="card-location">Agra, Uttar Pradesh</span>
            </div>

            <div class="heritage-content">

                <h3>Taj Mahal</h3>

                <p>
                    Experience one of India's most iconic monuments
                    and a symbol of eternal love.
                </p>

                <a href="destination_details.php?id=1">Explore</a>

            </div>

        </div>


        <!-- Card 2 -->
        <div class="heritage-card">

            <div class="heritage-image">
                <img src="assets/images/heritage/red-fort.jpg"
                     alt="Red Fort">

                <span class="card-location">Delhi</span>
            </div>

            <div class="heritage-content">

                <h3>Red Fort</h3>

                <p>
                    Step into India's Mughal history and discover
                    the magnificent Red Fort.
                </p>

                <a href="destination_details.php?id=2">Explore</a>

            </div>

        </div>


        <!-- Card 3 -->
        <div class="heritage-card">

            <div class="heritage-image">
                <img src="assets/images/heritage/jaipur-palace.jpg"
                     alt="Jaipur Palace">

                <span class="card-location">Jaipur, Rajasthan</span>
            </div>

            <div class="heritage-content">

                <h3>Jaipur Palace</h3>

                <p>
                    Discover royal architecture, colourful culture
                    and the heritage of Rajasthan.
                </p>

                <a href="destination_details.php?id=3">Explore</a>

            </div>

        </div>


        <!-- Card 4 -->
        <div class="heritage-card">

            <div class="heritage-image">
                <img src="assets/images/heritage/ajanta-caves.jpg"
                     alt="Ajanta Caves">

                <span class="card-location">Maharashtra</span>
            </div>

            <div class="heritage-content">

                <h3>Ajanta Caves</h3>

                <p>
                    Explore ancient rock-cut caves filled with
                    remarkable art and history.
                </p>

                <a href="destination_details.php?id=4">Explore</a>

            </div>

        </div>

    </div>


    <div class="explore-button">

        <a href="heritage.php">
            View All Heritage Sites →
        </a>

    </div>

</section>

<!-- =================================
     POPULAR EXPERIENCES SECTION
================================= -->

<section class="experiences-section" id="experiences">

    <div class="experience-heading">

        <span class="section-tag">POPULAR EXPERIENCES</span>

        <h2>Experience India Beyond the Landmarks</h2>

        <p>
            Discover the stories, flavours, traditions and people
            that make every journey through India unforgettable.
        </p>

    </div>


    <div class="experience-grid">

        <!-- Experience 1 -->

        <a href="heritage.php" class="experience-card">

            <div class="experience-icon">
                🏛️
            </div>

            <div class="experience-content">

                <h3>Heritage & History</h3>

                <p>
                    Walk through centuries of history,
                    magnificent forts and ancient monuments.
                </p>

                <span>Explore heritage →</span>

            </div>

        </a>


        <!-- Experience 2 -->

        <a href="guides.php" class="experience-card">

            <div class="experience-icon">
                🧑‍🏫
            </div>

            <div class="experience-content">

                <h3>Local Guides</h3>

                <p>
                    Meet passionate local guides and discover
                    India through their stories.
                </p>

                <span>Meet local guides →</span>

            </div>

        </a>


        <!-- Experience 3 -->

        <a href="guides.php" class="experience-card">

            <div class="experience-icon">
                🍛
            </div>

            <div class="experience-content">

                <h3>Food & Culture</h3>

                <p>
                    Taste authentic flavours and experience
                    India's rich cultural traditions.
                </p>

                <span>Discover culture →</span>

            </div>

        </a>


        <!-- Experience 4 -->

        <a href="guides.php" class="experience-card">

            <div class="experience-icon">
                🎨
            </div>

            <div class="experience-content">

                <h3>Art & Crafts</h3>

                <p>
                    Discover traditional artwork, handmade crafts
                    and local creative traditions.
                </p>

                <span>Explore crafts →</span>

            </div>

        </a>

    </div>

</section>

<!-- =================================
     LOCAL GUIDES SECTION
================================= -->

<section class="guides-section" id="guides">

    <div class="guides-heading">

        <span class="section-tag">MEET THE LOCALS</span>

        <h2>Discover India With Local Guides</h2>

        <p>
            Connect with passionate locals who know the hidden
            stories, traditions and places beyond the usual tourist path.
        </p>

    </div>


    <div class="guides-grid">

        <!-- Guide 1 -->

        <div class="guide-card">

            <div class="guide-image">

                <img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=600&h=750&fit=crop&crop=faces&utm_source=chatgpt.com"
                     alt="Local guide">

                <span class="guide-status">Available</span>

            </div>

            <div class="guide-info">

                <h3>Ritesh Thorat</h3>

                <p class="guide-location">📍 Jaipur, Rajasthan</p>

                <p class="guide-description">
                    Heritage enthusiast specializing in
                    Jaipur's royal history and architecture.
                </p>

                <div class="guide-bottom">

                    <span>⭐ 4.9</span>

                    <a href="guide_details.php?id=2">View Profile</a>

                </div>

            </div>

        </div>


        <!-- Guide 2 -->

        <div class="guide-card">

            <div class="guide-image">

                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=600&h=750&fit=crop&crop=faces&utm_source=chatgpt.com"
                     alt="Local guide">

                <span class="guide-status">Available</span>

            </div>

            <div class="guide-info">

                <h3>Meera Joshi</h3>

                <p class="guide-location">📍 Mumbai, Maharashtra</p>

                <p class="guide-description">
                    Local storyteller helping visitors discover
                    Mumbai's culture, food and heritage.
                </p>

                <div class="guide-bottom">

                    <span>⭐ 4.8</span>

                    <a href="guide_details.php?id=3">View Profile</a>

                </div>

            </div>

        </div>


        <!-- Guide 3 -->

        <div class="guide-card">

            <div class="guide-image">

                <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=600&h=750&fit=crop&crop=faces&utm_source=chatgpt.com"
                     alt="Local guide">

                <span class="guide-status">Available</span>

            </div>

            <div class="guide-info">

                <h3>Rohan Patil</h3>

                <p class="guide-location">📍 Pune, Maharashtra</p>

                <p class="guide-description">
                    Passionate about ancient history, forts
                    and Maharashtra's hidden heritage.
                </p>

                <div class="guide-bottom">

                    <span>⭐ 4.9</span>

                    <a href="guide_details.php?id=4">View Profile</a>

                </div>

            </div>

        </div>


        <!-- Guide 4 -->

        <div class="guide-card">

            <div class="guide-image">

                <img src="https://images.unsplash.com/photo-1531123897727-8f129e1688ce?w=600&h=750&fit=crop&crop=faces&utm_source=chatgpt.com"
                     alt="Local guide">

                <span class="guide-status">Available</span>

            </div>

            <div class="guide-info">

                <h3>Ananya Singh</h3>

                <p class="guide-location">📍 Delhi</p>

                <p class="guide-description">
                    Culture and history guide bringing Delhi's
                    fascinating stories to life.
                </p>

                <div class="guide-bottom">

                    <span>⭐ 4.9</span>

                    <a href="guide_details.php?id=5">View Profile</a>

                </div>

            </div>

        </div>

    </div>


    <div class="guides-button">

        <a href="guides.php">
            Meet All Guides →
        </a>

    </div>

</section>

<!-- =================================
     WHY CHOOSE HERITAGEHAVEN
================================= -->

<section class="why-section">

    <div class="why-container">

        <!-- Left Content -->

        <div class="why-content">

            <span class="section-tag">WHY HERITAGEHAVEN</span>

            <h2>
                Travel Deeper.
                <span>Experience More.</span>
            </h2>

            <p>
                We believe travelling is more than visiting a place.
                It is about discovering its stories, meeting its people
                and experiencing the culture that makes it unique.
            </p>

            <a href="about.php" class="why-button">
                Discover Our Story →
            </a>

        </div>


        <!-- Right Features -->

        <div class="why-features">

            <!-- Feature 1 -->

            <div class="why-feature">

                <div class="why-icon">
                    🧭
                </div>

                <div>
                    <h3>Authentic Experiences</h3>

                    <p>
                        Discover genuine local experiences
                        beyond the usual tourist attractions.
                    </p>
                </div>

            </div>


            <!-- Feature 2 -->

            <div class="why-feature">

                <div class="why-icon">
                    🏛️
                </div>

                <div>
                    <h3>Rich Heritage</h3>

                    <p>
                        Explore India's history, architecture,
                        traditions and timeless stories.
                    </p>
                </div>

            </div>


            <!-- Feature 3 -->

            <div class="why-feature">

                <div class="why-icon">
                    👥
                </div>

                <div>
                    <h3>Local Experts</h3>

                    <p>
                        Connect with passionate local guides
                        who know their destinations deeply.
                    </p>
                </div>

            </div>


            <!-- Feature 4 -->

            <div class="why-feature">

                <div class="why-icon">
                    🔒
                </div>

                <div>
                    <h3>Easy & Secure Booking</h3>

                    <p>
                        Find experiences, choose your guide
                        and book your journey with confidence.
                    </p>
                </div>

            </div>

        </div>

    </div>

</section>

<!-- =================================
     TESTIMONIALS SECTION
================================= -->

<section class="testimonials-section">

    <div class="testimonials-heading">

        <span class="section-tag">TRAVELER STORIES</span>

        <h2>Loved by Curious Travelers</h2>

        <p>
            Discover what travelers have to say about their
            experiences with HeritageHaven.
        </p>

    </div>


    <div class="testimonials-grid">

        <!-- Review 1 -->

        <div class="testimonial-card">

            <div class="quote-icon">“</div>

            <div class="stars">
                ★★★★★
            </div>

            <p class="review-text">
                HeritageHaven completely changed the way I experienced
                Jaipur. Our local guide showed us places I would never
                have discovered on my own.
            </p>

            <div class="reviewer">

                <div class="reviewer-avatar">
                    AS
                </div>

                <div>
                    <h4>Ananya Sharma</h4>
                    <span>Mumbai, India</span>
                </div>

            </div>

        </div>


        <!-- Review 2 -->

        <div class="testimonial-card">

            <div class="quote-icon">“</div>

            <div class="stars">
                ★★★★★
            </div>

            <p class="review-text">
                The experience was incredibly authentic. Instead of
                simply visiting monuments, we learned the stories
                and culture behind them.
            </p>

            <div class="reviewer">

                <div class="reviewer-avatar">
                    RK
                </div>

                <div>
                    <h4>Rahul Kapoor</h4>
                    <span>Delhi, India</span>
                </div>

            </div>

        </div>


        <!-- Review 3 -->

        <div class="testimonial-card">

            <div class="quote-icon">“</div>

            <div class="stars">
                ★★★★★
            </div>

            <p class="review-text">
                Beautiful platform and very easy to use. Finding a
                local guide and booking our experience was simple
                and smooth.
            </p>

            <div class="reviewer">

                <div class="reviewer-avatar">
                    EM
                </div>

                <div>
                    <h4>Emily Martin</h4>
                    <span>London, UK</span>
                </div>

            </div>

        </div>

    </div>

</section>

<!-- =================================
     FEATURED EXPERIENCES
================================= -->

<section class="featured-section">

    <div class="featured-heading">

        <span class="section-tag">HANDPICKED FOR YOU</span>

        <h2>Experiences Worth Travelling For</h2>

        <p>
            From royal palaces to hidden streets, discover memorable
            experiences curated for curious travellers.
        </p>

    </div>


    <div class="featured-grid">

        <!-- Experience 1 -->

        <div class="featured-card featured-large">

            <img src="assets/images/gallery/jaipur-experience.jpg"
                 alt="Jaipur heritage experience">

            <div class="featured-overlay">

                <span>HERITAGE</span>

                <h3>
                    Walk Through the Royal Streets of Jaipur
                </h3>

                <p>
                    Discover palaces, hidden courtyards and stories
                    from the royal city.
                </p>

                <div class="featured-bottom">

                    <strong>From ₹1,499</strong>

                    <a href="booking.php">
                        Explore →
                    </a>

                </div>

            </div>

        </div>


        <!-- Experience 2 -->

        <div class="featured-card">

            <img src="assets/images/gallery/food-experience.jpg"
                 alt="Indian food experience">

            <div class="featured-overlay">

                <span>FOOD & CULTURE</span>

                <h3>
                    Taste the Flavours of India
                </h3>

                <div class="featured-bottom">

                    <strong>From ₹999</strong>

                    <a href="booking.php">
                        Explore →
                    </a>

                </div>

            </div>

        </div>


        <!-- Experience 3 -->

        <div class="featured-card">

            <img src="assets/images/gallery/fort-experience.jpg"
                 alt="Indian fort experience">

            <div class="featured-overlay">

                <span>HISTORY</span>

                <h3>
                    Discover Ancient Forts
                </h3>

                <div class="featured-bottom">

                    <strong>From ₹1,199</strong>

                    <a href="booking.php">
                        Explore →
                    </a>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- =========================================
     PLAN YOUR JOURNEY SECTION
========================================= -->

<section class="journey-section">

    <div class="journey-overlay">

        <div class="journey-content">

            <span class="journey-tag">
                ✦ YOUR JOURNEY STARTS HERE
            </span>

            <h2>
                Ready to Discover
                <span>India Differently?</span>
            </h2>

            <p>
                Find a place, meet a local, choose an experience
                and create memories that last a lifetime.
            </p>

            <div class="journey-actions">

                <a href="booking.php" class="journey-primary-btn">
                    Plan Your Journey
                    <span>→</span>
                </a>

                <a href="heritage.php" class="journey-secondary-btn">
                    Explore Destinations
                    <span>→</span>
                </a>

            </div>

        </div>

    </div>

</section>