<?php
session_start();
require_once 'includes/db.php';

// Only admins can access this page
if (
    !isset($_SESSION['user_id']) ||
    ($_SESSION['user_role'] ?? '') !== 'admin'
) {
    header("Location: login.php");
    exit;
}

// Count total bookings
$bookingResult = $conn->query("SELECT COUNT(*) AS total FROM bookings");
$totalBookings = $bookingResult->fetch_assoc()['total'];

// Count total destinations
$destinationResult = $conn->query("SELECT COUNT(*) AS total FROM destinations");
$totalDestinations = $destinationResult->fetch_assoc()['total'];

// Count registered users
$userResult = $conn->query("SELECT COUNT(*) AS total FROM users");
$totalUsers = $userResult->fetch_assoc()['total'];

// Get latest 10 bookings
$latestBookings = $conn->query(
    "SELECT id, name, destination, travel_date, guests, email
     FROM bookings
     ORDER BY id DESC
     LIMIT 10"
);

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | HeritageHaven</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f6f4f0;
            color: #222;
        }

        header {
            background: #171717;
            color: white;
            padding: 20px 6%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .brand {
            color: white;
            text-decoration: none;
            font-size: 23px;
            font-weight: bold;
        }

        .brand span { color: #f2a261; }

        header a.logout {
            color: white;
            text-decoration: none;
            border: 1px solid #777;
            padding: 10px 15px;
            border-radius: 8px;
        }

        main {
            width: 90%;
            max-width: 1200px;
            margin: 35px auto;
        }

        .welcome { margin-bottom: 25px; }
        .welcome p { color: #666; }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 18px;
            margin-bottom: 30px;
        }

        .stat {
            background: white;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 5px 20px rgba(0,0,0,.05);
        }

        .stat p {
            color: #666;
            margin: 0 0 12px;
        }

        .stat h2 {
            margin: 0;
            font-size: 32px;
            color: #a65b20;
        }

        .section {
            background: white;
            padding: 24px;
            border-radius: 14px;
            margin-bottom: 25px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px;
        }

        th, td {
            text-align: left;
            padding: 13px;
            border-bottom: 1px solid #eee;
        }

        th { background: #f8f4ef; }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 18px;
        }

        .button {
            display: inline-block;
            background: #171717;
            color: white;
            padding: 13px 17px;
            border-radius: 8px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .button:hover { background: #a65b20; }

        .empty {
            padding: 20px 0;
            color: #666;
        }

        footer {
            text-align: center;
            padding: 25px;
            color: #777;
        }

        @media (max-width: 600px) {
            main {
                width: 94%;
                margin: 22px auto;
            }

            .section { padding: 16px; }
        }
    </style>
</head>
<body>

<header>
    <a href="index.php" class="brand">✦ Heritage<span>Haven</span></a>
    <a class="logout" href="logout.php">Log Out</a>
</header>

<main>
    <div class="welcome">
        <h1>Admin Dashboard</h1>
        <p>
            Welcome, <?= h($_SESSION['user_name'] ?? 'Admin') ?>!
            Manage your tourism website from here.
        </p>
    </div>

    <!-- Dashboard statistics -->
    <div class="stats">
        <div class="stat">
            <p>Total Bookings</p>
            <h2><?= (int)$totalBookings ?></h2>
        </div>

        <div class="stat">
            <p>Total Destinations</p>
            <h2><?= (int)$totalDestinations ?></h2>
        </div>

        <div class="stat">
            <p>Registered Users</p>
            <h2><?= (int)$totalUsers ?></h2>
        </div>
    </div>

    <!-- Recent bookings -->
    <section class="section">
        <h2>Recent Bookings</h2>
        <p>Showing the latest 10 bookings.</p>

        <?php if ($latestBookings && $latestBookings->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Booking ID</th>
                        <th>Tourist</th>
                        <th>Destination</th>
                        <th>Travel Date</th>
                        <th>Guests</th>
                        <th>Email</th>
                    </tr>
                </thead>

                <tbody>
                <?php $rowNumber = 1; ?>

                <?php while ($booking = $latestBookings->fetch_assoc()): ?>
                    <tr>
                        <td><?= $rowNumber++ ?></td>
                        <td><?= (int)$booking['id'] ?></td>
                        <td><?= h($booking['name']) ?></td>
                        <td><?= h($booking['destination']) ?></td>
                        <td><?= h($booking['travel_date']) ?></td>
                        <td><?= (int)$booking['guests'] ?></td>
                        <td><?= h($booking['email']) ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="empty">
                No bookings found yet. New bookings will appear here.
            </p>
        <?php endif; ?>

        <!-- View all bookings button -->
        <div class="actions">
            <a class="button" href="admin_bookings.php">
                View All Bookings
            </a>
        </div>
    </section>

    <!-- Quick actions -->
    <section class="section">
        <h2>Quick Actions</h2>
        <div class="actions">
            <a class="button" href="heritage.php">
                View Heritage Sites
            </a>

            <a class="button" href="index.php">
                View Website
            </a>

            <a class="button" href="admin_destinations.php">
                Manage Destinations
            </a>

            <a class="button" href="admin_guides.php" class="quick-action">
    <i class="fas fa-user-tie"></i>
    <span>Manage Guides</span>
</a>
        </div>
    </section>
</main>

<footer>HeritageHaven Admin Panel</footer>

</body>
</html>
