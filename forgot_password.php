
<?php
session_start();
require_once 'includes/db.php';

$message = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
    } else {
        $stmt = $conn->prepare(
            "SELECT id FROM users WHERE email = ? LIMIT 1"
        );

        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 1) {
                // Do not reveal whether an account exists.
                $message = "If an account exists with this email, please contact the website administrator to reset your password.";
            } else {
                $message = "If an account exists with this email, please contact the website administrator to reset your password.";
            }

            $stmt->close();
        } else {
            $message = "Unable to process your request. Please try again.";
        }
    }
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
    <title>Forgot Password | HeritageHaven</title>

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
            padding: 22px 7%;
            background: #171717;
        }

        .brand {
            color: white;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }

        .brand span {
            color: #f2a261;
        }

        .container {
            width: min(450px, 92%);
            margin: 55px auto;
            padding: 35px;
            background: white;
            border-radius: 18px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, .08);
        }

        h1 {
            margin-bottom: 10px;
        }

        .intro {
            color: #666;
            line-height: 1.6;
        }

        label {
            display: block;
            margin: 22px 0 7px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 13px;
            border: 1px solid #ddd;
            border-radius: 9px;
            font-size: 15px;
        }

        button {
            width: 100%;
            margin-top: 22px;
            padding: 15px;
            background: #171717;
            color: white;
            border: none;
            border-radius: 9px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #a65b20;
        }

        .message {
            margin-top: 18px;
            padding: 13px;
            background: #fff5e9;
            color: #754319;
            border-radius: 9px;
            line-height: 1.5;
        }

        .links {
            margin-top: 22px;
            text-align: center;
            line-height: 2;
        }

        a {
            color: #a65b20;
        }

        @media (max-width: 500px) {
            .container {
                padding: 25px 20px;
            }
        }
    </style>
</head>

<body>

<header>
    <a href="index.php" class="brand">
        ✦ Heritage<span>Haven</span>
    </a>
</header>

<main class="container">
    <h1>Forgot Password?</h1>

    <p class="intro">
        Enter your registered email address to get help recovering
        access to your HeritageHaven account.
    </p>

    <form method="POST" action="forgot_password.php">
        <label for="email">Registered Email Address</label>

        <input
            type="email"
            id="email"
            name="email"
            value="<?= h($email) ?>"
            placeholder="Enter your email"
            autocomplete="email"
            required
        >

        <button type="submit">Submit Request →</button>
    </form>

    <?php if ($message !== ''): ?>
        <div class="message">
            <?= h($message) ?>
        </div>
    <?php endif; ?>

    <div class="links">
        <a href="login.php">← Back to Login</a><br>
        <a href="index.php">Back to Home</a>
    </div>
</main>

</body>
</html>
