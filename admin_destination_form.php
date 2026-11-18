
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

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$id = filter_var(
    $_GET['id'] ?? '',
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);

$isEdit = $id !== false && $id !== null;

$place = [
    'name' => '',
    'state' => '',
    'category' => '',
    'short_description' => '',
    'history' => '',
    'best_time' => '',
    'visit_duration' => '',
    'opening_hours' => '',
    'entry_fee' => '',
    'image_url' => '',
    'latitude' => '',
    'longitude' => ''
];

$error = '';

// Load existing destination when editing
if ($isEdit) {
    $stmt = $conn->prepare(
        "SELECT name, state, category, short_description, history,
                best_time, visit_duration, opening_hours, entry_fee,
                image_url, latitude, longitude
         FROM destinations
         WHERE id = ?"
    );

    if (!$stmt) {
        die("Unable to load destination.");
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $stmt->bind_result(
        $place['name'],
        $place['state'],
        $place['category'],
        $place['short_description'],
        $place['history'],
        $place['best_time'],
        $place['visit_duration'],
        $place['opening_hours'],
        $place['entry_fee'],
        $place['image_url'],
        $place['latitude'],
        $place['longitude']
    );

    if (!$stmt->fetch()) {
        $stmt->close();
        http_response_code(404);
        exit("Destination not found. Please return to Manage Destinations.");
    }

    $stmt->close();
}

// Process submitted form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        $error = "Invalid request. Please refresh the page.";
    } else {
        foreach ($place as $key => $value) {
            $place[$key] = trim($_POST[$key] ?? '');
        }

        $name = $place['name'];
        $state = $place['state'];
        $category = $place['category'];
        $short_description = $place['short_description'];
        $history = $place['history'];
        $best_time = $place['best_time'];
        $visit_duration = $place['visit_duration'];
        $opening_hours = $place['opening_hours'];
        $entry_fee = $place['entry_fee'];
        $image_url = $place['image_url'];
        $latitude = $place['latitude'];
        $longitude = $place['longitude'];

        if ($name === '' || $state === '' || $category === '') {
            $error = "Name, state, and category are required.";
        } elseif (
            $entry_fee !== '' &&
            (!is_numeric($entry_fee) || (float)$entry_fee < 0)
        ) {
            $error = "Entry fee must be a valid non-negative number.";
        } elseif (
            ($latitude !== '' &&
                (!is_numeric($latitude) || (float)$latitude < -90 || (float)$latitude > 90)) ||
            ($longitude !== '' &&
                (!is_numeric($longitude) || (float)$longitude < -180 || (float)$longitude > 180))
        ) {
            $error = "Please enter valid latitude and longitude values.";
        } elseif (
            $image_url !== '' &&
            !filter_var($image_url, FILTER_VALIDATE_URL)
        ) {
            $error = "Please enter a valid image URL.";
        } else {
            // Convert empty optional numeric fields to NULL
            $entryFeeValue = $entry_fee === '' ? null : (float)$entry_fee;
            $latitudeValue = $latitude === '' ? null : (float)$latitude;
            $longitudeValue = $longitude === '' ? null : (float)$longitude;

            if ($isEdit) {
                $stmt = $conn->prepare(
                    "UPDATE destinations SET
                        name = ?, state = ?, category = ?,
                        short_description = ?, history = ?,
                        best_time = ?, visit_duration = ?,
                        opening_hours = ?, entry_fee = ?,
                        image_url = ?, latitude = ?, longitude = ?
                     WHERE id = ?"
                );

                if ($stmt) {
                    $stmt->bind_param(
                        "ssssssssdsddi",
                        $name,
                        $state,
                        $category,
                        $short_description,
                        $history,
                        $best_time,
                        $visit_duration,
                        $opening_hours,
                        $entryFeeValue,
                        $image_url,
                        $latitudeValue,
                        $longitudeValue,
                        $id
                    );
                }
            } else {
                $stmt = $conn->prepare(
                    "INSERT INTO destinations (
                        name, state, category, short_description, history,
                        best_time, visit_duration, opening_hours, entry_fee,
                        image_url, latitude, longitude
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
                );

                if ($stmt) {
                    $stmt->bind_param(
                        "ssssssssdsdd",
                        $name,
                        $state,
                        $category,
                        $short_description,
                        $history,
                        $best_time,
                        $visit_duration,
                        $opening_hours,
                        $entryFeeValue,
                        $image_url,
                        $latitudeValue,
                        $longitudeValue
                    );
                }
            }

            if (isset($stmt) && $stmt) {
                if ($stmt->execute()) {
                    $stmt->close();

                    header("Location: admin_destinations.php");
                    exit;
                } else {
                    $error = "Unable to save destination. Please check the details and try again.";
                    $stmt->close();
                }
            } else {
                $error = "Unable to prepare the database request.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEdit ? 'Edit' : 'Add' ?> Destination | HeritageHaven</title>

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
            padding: 20px 6%;
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

        .brand span { color: #f2a261; }

        main {
            width: 92%;
            max-width: 850px;
            margin: 35px auto;
        }

        .form-box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,.05);
        }

        .intro { color: #666; line-height: 1.6; }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .field { margin-top: 18px; }

        .full { grid-column: 1 / -1; }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font: inherit;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        input:focus, select:focus, textarea:focus {
            outline: 2px solid #f2a261;
            border-color: transparent;
        }

        .hint {
            display: block;
            margin-top: 6px;
            color: #777;
            font-size: 12px;
        }

        .actions {
            display: flex;
            gap: 12px;
            margin-top: 25px;
            flex-wrap: wrap;
        }

        button, .cancel {
            border: none;
            border-radius: 8px;
            padding: 13px 18px;
            font-size: 15px;
            cursor: pointer;
            text-decoration: none;
        }

        button {
            background: #171717;
            color: white;
        }

        button:hover { background: #a65b20; }

        .cancel {
            background: #eee;
            color: #222;
        }

        .error {
            background: #fff0ef;
            color: #a12820;
            padding: 13px;
            border-radius: 8px;
            margin: 18px 0;
        }

        @media (max-width: 600px) {
            .form-box { padding: 20px; }
            .grid { grid-template-columns: 1fr; }
            .full { grid-column: auto; }
        }
    </style>
</head>
<body>

<header>
    <a href="admin_dashboard.php" class="brand">
        ✦ Heritage<span>Haven</span>
    </a>
    <a href="admin_destinations.php">← Manage Destinations</a>
</header>

<main>
    <section class="form-box">
        <h1><?= $isEdit ? 'Edit Destination' : 'Add New Destination' ?></h1>

        <p class="intro">
            Enter the heritage site's details below. Fields marked * are required.
        </p>

        <?php if ($error !== ''): ?>
            <div class="error"><?= h($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <input
                type="hidden"
                name="csrf_token"
                value="<?= h($_SESSION['csrf_token']) ?>"
            >

            <div class="grid">
                <div class="field">
                    <label for="name">Destination Name *</label>
                    <input id="name" name="name" required maxlength="255"
                           value="<?= h($place['name']) ?>">
                </div>

                <div class="field">
                    <label for="state">State *</label>
                    <input id="state" name="state" required maxlength="150"
                           value="<?= h($place['state']) ?>">
                </div>

                <div class="field">
                    <label for="category">Category *</label>
                    <select id="category" name="category" required>
                        <option value="">Select category</option>
                        <?php
                        $categories = [
                            'Fort',
                            'Palace',
                            'Temple',
                            'Cave',
                            'Monument',
                            'Museum',
                            'Nature',
                            'Other'
                        ];
                        ?>
                        <?php foreach ($categories as $categoryOption): ?>
                            <option
                                value="<?= h($categoryOption) ?>"
                                <?= $place['category'] === $categoryOption ? 'selected' : '' ?>
                            >
                                <?= h($categoryOption) ?>
                            </option>
                        <?php endforeach; ?>
                        <?php if (
                            $place['category'] !== '' &&
                            !in_array($place['category'], $categories, true)
                        ): ?>
                            <option value="<?= h($place['category']) ?>" selected>
                                <?= h($place['category']) ?>
                            </option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="field">
                    <label for="best_time">Best Time to Visit</label>
                    <input id="best_time" name="best_time"
                           value="<?= h($place['best_time']) ?>">
                </div>

                <div class="field">
                    <label for="visit_duration">Visit Duration</label>
                    <input id="visit_duration" name="visit_duration"
                           placeholder="e.g. 2–3 hours"
                           value="<?= h($place['visit_duration']) ?>">
                </div>

                <div class="field">
                    <label for="opening_hours">Opening Hours</label>
                    <input id="opening_hours" name="opening_hours"
                           placeholder="e.g. 9 AM–5 PM"
                           value="<?= h($place['opening_hours']) ?>">
                </div>

                <div class="field">
                    <label for="entry_fee">Entry Fee (₹)</label>
                    <input id="entry_fee" name="entry_fee" type="number"
                           min="0" step="0.01"
                           value="<?= h($place['entry_fee']) ?>">
                </div>

                <div class="field">
                    <label for="image_url">Image URL</label>
                    <input id="image_url" name="image_url" type="url"
                           placeholder="https://example.com/image.jpg"
                           value="<?= h($place['image_url']) ?>">
                    <span class="hint">Use a publicly accessible image URL.</span>
                </div>

                <div class="field">
                    <label for="latitude">Latitude</label>
                    <input id="latitude" name="latitude" type="number"
                           min="-90" max="90" step="any"
                           value="<?= h($place['latitude']) ?>">
                </div>

                <div class="field">
                    <label for="longitude">Longitude</label>
                    <input id="longitude" name="longitude" type="number"
                           min="-180" max="180" step="any"
                           value="<?= h($place['longitude']) ?>">
                </div>

                <div class="field full">
                    <label for="short_description">Short Description</label>
                    <textarea id="short_description" name="short_description"><?= h($place['short_description']) ?></textarea>
                </div>

                <div class="field full">
                    <label for="history">History and Details</label>
                    <textarea id="history" name="history"><?= h($place['history']) ?></textarea>
                </div>
            </div>

            <div class="actions">
                <button type="submit">
                    <?= $isEdit ? 'Save Changes' : 'Add Destination' ?>
                </button>

                <a class="cancel" href="admin_destinations.php">
                    Cancel
                </a>
            </div>
        </form>
    </section>
</main>

</body>
</html>
