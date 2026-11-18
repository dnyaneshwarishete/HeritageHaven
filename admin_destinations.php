
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

// Create a CSRF token for delete requests
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = '';
$error = '';

// Delete a destination only after a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['delete_id'])) {

    $token = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        $error = "Invalid request. Please refresh the page.";
    } else {
        $id = filter_var(
            $_POST['delete_id'],
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );

        if ($id === false) {
            $error = "Invalid destination ID.";
        } else {
            $stmt = $conn->prepare(
                "DELETE FROM destinations WHERE id = ?"
            );

            if ($stmt) {
                $stmt->bind_param("i", $id);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    $message = "Destination deleted successfully.";
                } else {
                    $error = "Destination not found or already deleted.";
                }

                $stmt->close();
            } else {
                $error = "Unable to delete destination.";
            }
        }
    }
}

// Fetch destinations
$result = $conn->query(
    "SELECT id, name, state, category, entry_fee, best_time
     FROM destinations
     ORDER BY name ASC"
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

    <title>Manage Destinations | HeritageHaven</title>

    <style>
        * {
            box-sizing: border-box;
        }

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
            flex-wrap: wrap;
            gap: 15px;
        }

        .brand {
            color: white;
            font-size: 23px;
            font-weight: bold;
            text-decoration: none;
        }

        .brand span {
            color: #f2a261;
        }

        header a.back {
            color: white;
            text-decoration: none;
        }

        main {
            width: 92%;
            max-width: 1200px;
            margin: 35px auto;
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }

        .top p {
            color: #666;
        }

        .button {
            display: inline-block;
            padding: 12px 17px;
            background: #171717;
            color: white;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }

        .button:hover {
            background: #a65b20;
        }

        .delete {
            background: #a12820;
        }

        .delete:hover {
            background: #7d1c16;
        }

        .table-box {
            background: white;
            padding: 22px;
            border-radius: 14px;
            box-shadow: 0 5px 20px rgba(0,0,0,.05);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 760px;
        }

        th, td {
            text-align: left;
            padding: 14px 12px;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #f8f4ef;
        }

        .actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .actions form {
            margin: 0;
        }

        .message {
            background: #e8f7ec;
            color: #206534;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 18px;
        }

        .error {
            background: #fff0ef;
            color: #a12820;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 18px;
        }

        .empty {
            padding: 25px 0;
            color: #666;
        }

        footer {
            text-align: center;
            color: #777;
            padding: 25px;
        }

        @media (max-width: 600px) {
            .table-box {
                padding: 12px;
            }
        }
    </style>
</head>

<body>

<header>
    <a href="admin_dashboard.php" class="brand">
        ✦ Heritage<span>Haven</span>
    </a>

    <a href="admin_dashboard.php" class="back">
        ← Back to Dashboard
    </a>
</header>

<main>
    <div class="top">
        <div>
            <h1>Manage Destinations</h1>
            <p>View and manage heritage sites in your database.</p>
        </div>

        <a href="admin_destination_form.php" class="button">
            + Add Destination
        </a>
    </div>

    <?php if ($message !== ''): ?>
        <div class="message"><?= h($message) ?></div>
    <?php endif; ?>

    <?php if ($error !== ''): ?>
        <div class="error"><?= h($error) ?></div>
    <?php endif; ?>

    <section class="table-box">
        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Destination</th>
                        <th>State</th>
                        <th>Category</th>
                        <th>Entry Fee</th>
                        <th>Best Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($place = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= (int)$place['id'] ?></td>
                            <td><?= h($place['name']) ?></td>
                            <td><?= h($place['state']) ?></td>
                            <td><?= h($place['category']) ?></td>
                            <td><?= h($place['entry_fee']) ?></td>
                            <td><?= h($place['best_time']) ?></td>

                            <td>
                                <div class="actions">
                                    <a
                                        class="button"
                                        href="admin_destination_form.php?id=<?= (int)$place['id'] ?>"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this destination?');"
                                    >
                                        <input
                                            type="hidden"
                                            name="delete_id"
                                            value="<?= (int)$place['id'] ?>"
                                        >

                                        <input
                                            type="hidden"
                                            name="csrf_token"
                                            value="<?= h($_SESSION['csrf_token']) ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="button delete"
                                        >
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="empty">
                No destinations found. Add your first heritage site to get started.
            </p>
        <?php endif; ?>
    </section>
</main>

<footer>HeritageHaven Admin Panel</footer>

</body>
</html>
