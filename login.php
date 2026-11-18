
<?php
session_start();
require_once 'includes/db.php';

$error = '';
$email = '';

// Redirect users who are already logged in
if (isset($_SESSION['user_id'])) {
    if (($_SESSION['user_role'] ?? '') === 'admin') {
        header("Location: admin_dashboard.php");
        exit;
    } elseif (($_SESSION['user_role'] ?? '') === 'tourist') {
        header("Location: tourist_dashboard.php");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = "Please enter a valid email and password.";
    } else {
        $stmt = $conn->prepare(
            "SELECT id, name, email, password, role
             FROM users
             WHERE email = ?
             LIMIT 1"
        );

        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 1) {
                $stmt->bind_result(
                    $id,
                    $name,
                    $db_email,
                    $hashed_password,
                    $role
                );

                $stmt->fetch();

                if (password_verify($password, $hashed_password)) {
                    if ($role === 'tourist' || $role === 'admin') {
                        session_regenerate_id(true);

                        $_SESSION['user_id'] = $id;
                        $_SESSION['user_name'] = $name;
                        $_SESSION['user_role'] = $role;

                        $stmt->close();

                        if ($role === 'admin') {
                            header("Location: admin_dashboard.php");
                        } else {
                            header("Location: tourist_dashboard.php");
                        }

                        exit;
                    } else {
                        $error = "This account role cannot log in here.";
                    }
                } else {
                    $error = "Incorrect email or password.";
                }
            } else {
                $error = "Incorrect email or password.";
            }

            $stmt->close();
        } else {
            $error = "Unable to process login. Please try again.";
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
    <title>Login | HeritageHaven</title>

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

        .eyebrow {
            color: #a65b20;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        h1 {
            margin-bottom: 10px;
        }

        .intro {
            color: #666;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin: 17px 0 7px;
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
            border-radius: 9px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #a65b20;
        }

        .forgot-password {
            display: block;
            text-align: right;
            margin-top: 12px;
            font-size: 14px;
            text-decoration: none;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .error {
            padding: 13px;
            background: #fff0ef;
            color: #a12820;
            border-radius: 9px;
            line-height: 1.5;
            margin-bottom: 15px;
        }

        .links {
            margin-top: 22px;
            text-align: center;
            color: #666;
            line-height: 1.8;
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
    <div class="eyebrow">WELCOME BACK</div>

    <h1>Log In</h1>

    <p class="intro">
        Sign in to manage your bookings and plan your next journey.
    </p>

    <?php if ($error !== ''): ?>
        <div class="error"><?= h($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <label for="email">Email Address</label>

        <input
            id="email"
            name="email"
            type="email"
            value="<?= h($email) ?>"
            autocomplete="email"
            required
        >

        <label for="password">Password</label>

        <input
            id="password"
            name="password"
            type="password"
            autocomplete="current-password"
            required
        >

        <button type="submit">Log In →</button>

        <a href="forgot_password.php" class="forgot-password">
            Forgot Password?
        </a>
    </form>

    <div class="links">
        Don't have an account?
        <a href="register.php">Create an account</a>
        <br>
        <a href="index.php">← Back to Home</a>
    </div>
</main>

</body>
</html>
