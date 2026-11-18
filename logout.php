<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION = [];
    session_destroy();

    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Logout | HeritageHaven</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: #f7f5f1;
            color: #222;
        }

        .popup {
            width: min(420px, 100%);
            padding: 35px;
            text-align: center;
            background: white;
            border-radius: 20px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.10);
        }

        .icon {
            font-size: 42px;
        }

        h1 {
            font-size: 25px;
            margin: 15px 0 10px;
        }

        p {
            color: #666;
            line-height: 1.6;
        }

        .actions {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        button, .cancel {
            flex: 1;
            padding: 13px 10px;
            border: none;
            border-radius: 9px;
            font-size: 15px;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
        }

        .confirm {
            background: #a33b32;
            color: white;
        }

        .cancel {
            background: #eeeae4;
            color: #222;
        }

        .confirm:hover {
            background: #842c25;
        }

        .cancel:hover {
            background: #ded7ce;
        }
    </style>
</head>
<body>

<div class="popup">
    <div class="icon">👋</div>
    <h1>Are you sure you want to log out?</h1>
    <p>You will need to log in again to access your tourist dashboard.</p>

    <div class="actions">
        <a href="tourist_dashboard.php" class="cancel">Cancel</a>

        <form method="POST" action="logout.php" style="flex:1; display:flex;">
            <button type="submit" class="confirm">Yes, Log Out</button>
        </form>
    </div>
</div>

</body>
</html>
