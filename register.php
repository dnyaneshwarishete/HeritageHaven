
<?php
session_start();
require_once 'includes/db.php';

$errors = [];
$name = '';
$email = '';
$phone = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($name === '' || strlen($name) > 100) {
        $errors[] = "Please enter a valid name.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if (!preg_match('/^[6-9][0-9]{9}$/', $phone)) {
        $errors[] = "Please enter a valid phone number.";
    }

    if (strlen($password) < 8) {
        $errors[] = "Password must contain at least 8 characters.";
    }

    if ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }

    if (!$errors) {
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $errors[] = "This email is already registered.";
        }

        $check->close();
    }

    if (!$errors) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare(
            "INSERT INTO users (name, email, phone, password)
             VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssss",
            $name,
            $email,
            $phone,
            $hashed_password
        );

        if ($stmt->execute()) {
            $user_id = $conn->insert_id;

            session_regenerate_id(true);
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_role'] = 'tourist';

            $stmt->close();

            header("Location: tourist_dashboard.php");
            exit;
        } else {
            $errors[] = "Registration failed. Please try again.";
        }

        $stmt->close();
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
    <title>Create Account | HeritageHaven</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f7f5f1;
            color: #222;
        }

        header {
            padding: 22px 7%;
            background: #171717;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }

        header span { color: #f2a261; }

        .container {
            width: min(480px, 92%);
            margin: 45px auto;
            padding: 35px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 12px 35px rgba(0,0,0,.08);
        }

        .eyebrow {
            color: #bb702f;
            font-weight: bold;
            letter-spacing: 2px;
            font-size: 12px;
        }

        h1 { margin-bottom: 10px; }

        .intro {
            color: #666;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin: 16px 0 7px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 13px;
            border: 1px solid #ddd;
            border-radius: 9px;
            font-size: 15px;
        }

        input:focus {
            outline: 2px solid #f2a261;
            border-color: transparent;
        }

        button {
            width: 100%;
            margin-top: 24px;
            padding: 15px;
            background: #171717;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover { background: #bb702f; }

        .errors {
            background: #fff0ef;
            color: #a12820;
            padding: 14px 20px;
            border-radius: 10px;
            line-height: 1.7;
        }

        .login {
            margin-top: 22px;
            text-align: center;
            color: #666;
        }

        a { color: #a65b20; }

        @media (max-width: 500px) {
            .container { padding: 25px 20px; }
        }
    </style>
</head>
<body>

<header>✦ Heritage<span>Haven</span></header>

<main class="container">
    <div class="eyebrow">START YOUR JOURNEY</div>
    <h1>Create Your Account</h1>
    <p class="intro">
        Join HeritageHaven to discover India's heritage and plan your next trip.
    </p>

    <?php if ($errors): ?>
        <div class="errors">
            <?php foreach ($errors as $error): ?>
                <div><?= h($error) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label for="name">Full Name</label>
        <input id="name" name="name" type="text"
               maxlength="100" value="<?= h($name) ?>" required>

        <label for="email">Email Address</label>
        <input id="email" name="email" type="email"
               maxlength="150" value="<?= h($email) ?>" required>

        <label for="phone">Phone Number</label>
<input
    id="phone"
    name="phone"
    type="tel"
    placeholder="Enter 10-digit mobile number"
    value="<?= h($phone) ?>"
    pattern="[6-9][0-9]{9}"
    minlength="10"
    maxlength="10"
    inputmode="numeric"
    title="Enter a valid 10-digit Indian mobile number starting with 6, 7, 8 or 9."
    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
    required
>

        <label for="password">Password</label>
        <input id="password" name="password" type="password"
               minlength="8" autocomplete="new-password" required>

        <label for="confirm_password">Confirm Password</label>
        <input id="confirm_password" name="confirm_password"
               type="password" minlength="8"
               autocomplete="new-password" required>

        <button type="submit">Create Account →</button>
    </form>

    <p class="login">
        Already have an account?
        <a href="login.php">Log in</a>
    </p>
</main>

</body>
</html>
