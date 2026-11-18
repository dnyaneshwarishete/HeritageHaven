
<?php
require_once 'includes/db.php';

$booking = null;
$booking_id = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);

if ($booking_id && $booking_id > 0) {
    $stmt = $conn->prepare(
        "SELECT id, destination, travel_date, guests,
                experience, name
         FROM bookings
         WHERE id = ?"
    );

    if ($stmt) {
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $booking = $result->fetch_assoc();

        $stmt->close();
    }
}

function safe($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Booking Confirmation | HeritageHaven</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f6f5f1;
            color: #202020;
        }

        .navbar {
            padding: 22px 7%;
            background: #151515;
            color: white;
            font-size: 23px;
            font-weight: bold;
        }

        .navbar span {
            color: #f2a261;
        }

        .container {
            width: min(650px, 92%);
            margin: 65px auto;
            padding: 40px;
            background: white;
            border-radius: 24px;
            text-align: center;
            box-shadow: 0 15px 45px rgba(0,0,0,.08);
        }

        .check {
            width: 85px;
            height: 85px;
            margin: 0 auto 20px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: #e2f7e9;
            color: #168544;
            font-size: 48px;
        }

        h1 {
            margin-bottom: 12px;
            font-size: 32px;
        }

        .intro {
            color: #666;
            line-height: 1.7;
        }

        .details {
            margin: 30px 0;
            padding: 22px;
            border-radius: 15px;
            background: #faf8f5;
            text-align: left;
        }

        .details h2 {
            margin-top: 0;
            font-size: 20px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            padding: 13px 0;
            border-bottom: 1px solid #e7e3dd;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-row span:first-child {
            color: #777;
        }

        .detail-row span:last-child {
            font-weight: bold;
            text-align: right;
            overflow-wrap: anywhere;
        }

        .reference {
            color: #b96929;
            font-weight: bold;
            margin-top: 22px;
        }

        .button {
            display: inline-block;
            margin: 8px 5px;
            padding: 14px 22px;
            border-radius: 10px;
            background: #171717;
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .button.secondary {
            background: white;
            border: 1px solid #ddd;
            color: #222;
        }

        @media (max-width: 500px) {
            .container {
                padding: 25px 18px;
                margin: 35px auto;
            }

            h1 {
                font-size: 26px;
            }

            .detail-row {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>

    <div class="navbar">
        ✦ Heritage<span>Haven</span>
    </div>

    <main class="container">

        <?php if ($booking): ?>

            <div class="check">✓</div>

            <h1>Booking Request Successful!</h1>

            <p class="intro">
                Thank you, <?= safe($booking['name']) ?>!
                Your booking request has been saved successfully.
            </p>

            <div class="details">
                <h2>Your Booking Details</h2>

                <div class="detail-row">
                    <span>Booking Reference</span>
                    <span>HH<?= (int)$booking['id'] ?></span>
                </div>

                <div class="detail-row">
                    <span>Destination</span>
                    <span><?= safe($booking['destination']) ?></span>
                </div>

                <div class="detail-row">
                    <span>Travel Date</span>
                    <span><?= safe($booking['travel_date']) ?></span>
                </div>

                <div class="detail-row">
                    <span>Guests</span>
                    <span><?= (int)$booking['guests'] ?></span>
                </div>

                <div class="detail-row">
                    <span>Experience</span>
                    <span><?= safe($booking['experience']) ?></span>
                </div>
            </div>

            <p class="intro">
                Please keep your booking reference for future use.
                This confirms that your request was submitted; it does
                not mean that a guide or reservation has been confirmed.
            </p>

            <a class="button" href="index.php">Back to Home</a>

            <a class="button secondary" href="booking.php">
                Book Another Experience
            </a>

        <?php else: ?>

            <div class="check">!</div>

            <h1>Booking Not Found</h1>

            <p class="intro">
                We couldn't find that booking. Please submit a new request.
            </p>

            <a class="button" href="booking.php">Return to Booking</a>

        <?php endif; ?>

    </main>

</body>
</html>
