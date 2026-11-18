
<?php
session_start();
require_once 'includes/db.php';

// Only admin can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Get all bookings, newest first
$sql = "SELECT id, name, destination, travel_date, guests,
               email, phone, booking_date, experience
        FROM bookings
        ORDER BY booking_date DESC, id DESC";

$result = $conn->query($sql);

if (!$result) {
    die("Unable to load bookings.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage All Bookings | HeritageHaven</title>

<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f5f3ef;
    color: #292929;
}

header {
    background: #171717;
    color: white;
    padding: 20px 5%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

header a {
    color: white;
    text-decoration: none;
}

.brand {
    font-size: 23px;
    font-weight: bold;
}

.brand span {
    color: #f2a261;
}

main {
    width: 94%;
    max-width: 1500px;
    margin: 30px auto;
}

.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 20px;
}

.button {
    display: inline-block;
    padding: 11px 16px;
    background: #9b5428;
    color: white;
    text-decoration: none;
    border-radius: 6px;
}

.summary {
    background: white;
    padding: 18px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px #0000000d;
}

.table-wrapper {
    background: white;
    border-radius: 10px;
    overflow-x: auto;
    box-shadow: 0 2px 8px #0000000d;
}

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 1100px;
}

th, td {
    padding: 14px 12px;
    text-align: left;
    border-bottom: 1px solid #e8e5e0;
    font-size: 14px;
}

th {
    background: #292929;
    color: white;
    white-space: nowrap;
}

tr:hover td {
    background: #faf7f2;
}

.empty {
    padding: 30px;
    text-align: center;
    color: #666;
}

@media (max-width: 600px) {
    main {
        width: 92%;
        margin: 20px auto;
    }
}
</style>
</head>

<body>

<header>
    <a href="index.php" class="brand">
        Heritage<span>Haven</span>
    </a>
    <a href="admin_dashboard.php">← Back to Dashboard</a>
</header>

<main>
    <div class="top-bar">
        <div>
            <h1>Manage All Bookings</h1>
            <p>View all tourist booking details in one place.</p>
        </div>

        <a href="admin_dashboard.php" class="button">
            Admin Dashboard
        </a>
    </div>

    <div class="summary">
        <strong>Total Bookings:</strong> <?= $result->num_rows ?>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Booking ID</th>
                    <th>Tourist Name</th>
                    <th>Destination</th>
                    <th>Travel Date</th>
                    <th>Guests</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Experience</th>
                    <th>Booking Date</th>
                </tr>
            </thead>

            <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php $rowNumber = 1; ?>

                <?php while ($booking = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $rowNumber++ ?></td>
                        <td><?= (int)$booking['id'] ?></td>
                        <td><?= htmlspecialchars($booking['name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($booking['destination'] ?? '') ?></td>
                        <td><?= htmlspecialchars($booking['travel_date'] ?? '') ?></td>
                        <td><?= (int)$booking['guests'] ?></td>
                        <td><?= htmlspecialchars($booking['email'] ?? '') ?></td>
                        <td><?= htmlspecialchars($booking['phone'] ?? '') ?></td>
                        <td><?= htmlspecialchars($booking['experience'] ?? '') ?></td>
                        <td><?= htmlspecialchars($booking['booking_date'] ?? '') ?></td>
                    </tr>
                <?php endwhile; ?>

            <?php else: ?>
                <tr>
                    <td colspan="10" class="empty">
                        No bookings found yet.
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>
