
<?php
require_once 'includes/db.php';

$message = "";

// Load all destinations
$destinations = [];

$result = $conn->query("SELECT name FROM destinations ORDER BY name ASC");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $destinations[] = $row['name'];
    }
}

// Load approved guides
$guides = [];

$guide_result = $conn->query(
    "SELECT id, name, city, specialization
     FROM guides
     WHERE status = 'approved'
     ORDER BY name ASC"
);

if ($guide_result) {
    while ($row = $guide_result->fetch_assoc()) {
        $guides[] = $row;
    }
}

// Read selected destination
$selected_destination = trim($_GET['destination'] ?? '');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $selected_destination = trim($_POST['destination'] ?? '');
}

if (!in_array($selected_destination, $destinations, true)) {
    $selected_destination = "";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $destination = trim($_POST['destination'] ?? '');
    $travel_date = $_POST['travel_date'] ?? '';
    $guests = (int)($_POST['guests'] ?? 0);
    $experience = trim($_POST['experience'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $user_message = trim($_POST['message'] ?? '');

    // A blank guide selection means no guide requested
    $guide_input = trim($_POST['guide_id'] ?? '');
    $guide_id = null;

    if ($guide_input !== '') {
        if (ctype_digit($guide_input) && (int)$guide_input > 0) {
            $guide_id = (int)$guide_input;
        } else {
            $message = "Please select a valid guide.";
        }
    }

    // Validate destination
    if ($message === "" && !in_array($destination, $destinations, true)) {
        $message = "Please select a valid destination.";
    }

    // Validate the selected guide
    if ($message === "" && $guide_id !== null) {
        $valid_guide_ids = array_map(
            'intval',
            array_column($guides, 'id')
        );

        if (!in_array($guide_id, $valid_guide_ids, true)) {
            $message = "Please select a valid approved guide.";
        }
    }

    // Validate remaining booking details
    if (
        $message === "" &&
        (
            $travel_date === '' ||
            $travel_date < date('Y-m-d') ||
            $guests < 1 ||
            $guests > 20 ||
            !in_array($experience, [
                "Heritage Sites",
                "Local Guide",
                "Cultural Experience",
                "Food & Culture",
                "Historical Walk"
            ], true) ||
            $name === '' ||
            !filter_var($email, FILTER_VALIDATE_EMAIL) ||
            $phone === ''
        )
    ) {
        $message = "Please enter valid details in all required fields.";
    }

    // Save booking
    if ($message === "") {

        $sql = "INSERT INTO bookings
                (destination, travel_date, guests, experience,
                 guide_id, name, email, phone, message)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param(
                "ssissssss",
                $destination,
                $travel_date,
                $guests,
                $experience,
                $guide_id,
                $name,
                $email,
                $phone,
                $user_message
            );

            if ($stmt->execute()) {
                $booking_id = $conn->insert_id;
                $stmt->close();

                header(
                    "Location: booking_success.php?id=" .
                    urlencode((string)$booking_id)
                );
                exit;
            } else {
                $message = "Booking failed. Please try again.";
                $stmt->close();
            }
        } else {
            $message = "Unable to process your booking right now.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Plan Your Journey | HeritageHaven</title>

    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f7f5f2;
            color: #222;
        }

        .booking-page {
            min-height: 100vh;
            padding: 80px 20px;
        }

        .booking-container {
            max-width: 850px;
            margin: auto;
            background: white;
            padding: 45px;
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
        }

        .booking-heading {
            text-align: center;
            margin-bottom: 35px;
        }

        .booking-heading span {
            color: #e88945;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .booking-heading h1 {
            font-size: 42px;
            margin: 12px 0;
        }

        .booking-heading p {
            color: #666;
            font-size: 17px;
        }

        .booking-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        label {
            margin-bottom: 8px;
            font-weight: 600;
        }

        input,
        select,
        textarea {
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 15px;
            outline: none;
            box-sizing: border-box;
            width: 100%;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #e88945;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        .booking-button {
            grid-column: 1 / -1;
            padding: 16px;
            border: none;
            border-radius: 12px;
            background: #171717;
            color: white;
            font-size: 17px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .booking-button:hover {
            background: #e88945;
            transform: translateY(-2px);
        }

        .error-message {
            background: #fdeaea;
            color: #a32626;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: bold;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 25px;
            color: #555;
            text-decoration: none;
        }

        .back-link:hover {
            color: #e88945;
        }

        @media (max-width: 700px) {
            .booking-container {
                padding: 25px;
            }

            .booking-heading h1 {
                font-size: 30px;
            }

            .booking-form {
                grid-template-columns: 1fr;
            }

            .form-group.full,
            .booking-button {
                grid-column: auto;
            }
        }
    </style>
</head>

<body>

<section class="booking-page">
    <div class="booking-container">

        <a href="heritage.php" class="back-link">
            ← Back to Heritage Sites
        </a>

        <div class="booking-heading">
            <span>YOUR JOURNEY STARTS HERE</span>
            <h1>Plan Your Journey</h1>
            <p>
                Tell us about your travel plans and
                create your perfect Indian heritage experience.
            </p>
        </div>

        <?php if ($message !== ""): ?>
            <div class="error-message">
                <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="booking.php" class="booking-form">

            <div class="form-group">
                <label for="destination">Destination</label>

                <select name="destination" id="destination" required>
                    <option value="">Select Destination</option>

                    <?php foreach ($destinations as $place): ?>
                        <option
                            value="<?= htmlspecialchars($place, ENT_QUOTES, 'UTF-8') ?>"
                            <?= $selected_destination === $place ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($place, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="travel_date">Travel Date</label>

                <input
                    type="date"
                    id="travel_date"
                    name="travel_date"
                    min="<?= date('Y-m-d') ?>"
                    value="<?= htmlspecialchars($_POST['travel_date'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="guests">Number of Guests</label>

                <input
                    type="number"
                    id="guests"
                    name="guests"
                    min="1"
                    max="20"
                    value="<?= htmlspecialchars($_POST['guests'] ?? '1', ENT_QUOTES, 'UTF-8') ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="experience">Experience</label>

                <select name="experience" id="experience" required>
                    <option value="">Select Experience</option>

                    <?php
                    $experiences = [
                        "Heritage Sites",
                        "Local Guide",
                        "Cultural Experience",
                        "Food & Culture",
                        "Historical Walk"
                    ];

                    $selected_experience = $_POST['experience'] ?? '';
                    ?>

                    <?php foreach ($experiences as $item): ?>
                        <option
                            value="<?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>"
                            <?= $selected_experience === $item ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- NEW: Optional Local Guide dropdown -->
            <div class="form-group">
                <label for="guide_id">Local Guide (Optional)</label>

                <select name="guide_id" id="guide_id">
                    <option value="">No guide required</option>

                    <?php foreach ($guides as $guide): ?>
                        <option
                            value="<?= (int)$guide['id'] ?>"
                            <?= (
                                isset($_POST['guide_id']) &&
                                (string)$_POST['guide_id'] === (string)$guide['id']
                            ) ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($guide['name'], ENT_QUOTES, 'UTF-8') ?>
                            — <?= htmlspecialchars($guide['city'], ENT_QUOTES, 'UTF-8') ?>
                            (<?= htmlspecialchars($guide['specialization'], ENT_QUOTES, 'UTF-8') ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="name">Full Name</label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="Enter your name"
                    value="<?= htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="email">Email</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>

                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    placeholder="Enter phone number"
                    value="<?= htmlspecialchars($_POST['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="message">Additional Message</label>

                <input
                    type="text"
                    id="message"
                    name="message"
                    placeholder="Any special requirements?"
                    value="<?= htmlspecialchars($_POST['message'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                >
            </div>

            <button type="submit" class="booking-button">
                Confirm Booking →
            </button>

        </form>
    </div>
</section>

</body>
</html>
