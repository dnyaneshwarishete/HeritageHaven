<?php
session_start();
require_once "includes/db.php";

// Allow only logged-in admins
if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['user_role']) ||
    $_SESSION['user_role'] !== 'admin'
) {
    header("Location: login.php");
    exit;
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$message = trim($_GET["message"] ?? "");
$error = "";

// Delete a guide using POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";
    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

    if ($action === "delete" && $id) {
        $stmt = $conn->prepare("DELETE FROM guides WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $message = "Guide deleted successfully.";
        } else {
            $error = "Unable to delete the guide.";
        }
        $stmt->close();
    }

    // Update guide status
    if ($action === "status" && $id) {
        $status = $_POST["status"] ?? "";

        if (in_array($status, ["approved", "pending", "rejected"], true)) {
            $stmt = $conn->prepare(
                "UPDATE guides SET status = ? WHERE id = ?"
            );
            $stmt->bind_param("si", $status, $id);

            if ($stmt->execute()) {
                $message = "Guide status updated successfully.";
            } else {
                $error = "Unable to update guide status.";
            }
            $stmt->close();
        } else {
            $error = "Invalid status selected.";
        }
    }
}

// Load all guides
$result = $conn->query(
    "SELECT id, name, city, specialization, experience_years,
            photo_url, status, created_at
     FROM guides
     ORDER BY id DESC"
);

if (!$result) {
    die("Could not load guides. Please confirm that the guides table exists.");
}

$total = $result->num_rows;
$approved = 0;
$pending = 0;
$rejected = 0;

$guides = [];

while ($guide = $result->fetch_assoc()) {
    $guides[] = $guide;

    if ($guide["status"] === "approved") {
        $approved++;
    } elseif ($guide["status"] === "pending") {
        $pending++;
    } elseif ($guide["status"] === "rejected") {
        $rejected++;
    }
}

$editId = filter_input(INPUT_GET, "edit", FILTER_VALIDATE_INT);
$editGuide = null;

if ($editId) {
    $stmt = $conn->prepare("SELECT * FROM guides WHERE id = ?");
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $editGuide = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

$editMode = is_array($editGuide);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Guides | HeritageHaven</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

<style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: 'DM Sans', sans-serif;
    background: #f5f6f3;
    color: #26342e;
}

.topbar {
    background: #203c32;
    color: white;
    padding: 19px 5%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    flex-wrap: wrap;
}

.brand {
    font-family: 'Playfair Display', serif;
    font-size: 25px;
    font-weight: 700;
}

.topbar a {
    color: white;
    text-decoration: none;
    margin-left: 18px;
    font-size: 14px;
}

.container {
    width: min(1200px, 92%);
    margin: 35px auto;
}

.heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
    margin-bottom: 25px;
}

h1, h2 {
    font-family: 'Playfair Display', serif;
}

h1 {
    font-size: clamp(27px, 4vw, 36px);
}

.subtitle {
    color: #738078;
    margin-top: 7px;
    font-size: 14px;
}

.button {
    display: inline-block;
    padding: 11px 16px;
    border: none;
    border-radius: 8px;
    background: #b98a52;
    color: white;
    text-decoration: none;
    font: inherit;
    font-size: 13px;
    cursor: pointer;
}

.button:hover {
    opacity: .9;
}

.button.dark {
    background: #203c32;
}

.button.red {
    background: #b84040;
}

.button.gray {
    background: #e8ece8;
    color: #34433b;
}

.stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    margin-bottom: 25px;
}

.stat, .panel {
    background: white;
    padding: 22px;
    border-radius: 13px;
    border: 1px solid #e6e9e5;
    box-shadow: 0 5px 18px #26342e08;
}

.stat span {
    color: #758078;
    font-size: 13px;
}

.stat strong {
    display: block;
    font-size: 30px;
    margin-top: 8px;
}

.panel {
    margin-bottom: 25px;
}

.panel h2 {
    font-size: 23px;
    margin-bottom: 18px;
}

.alert {
    padding: 13px 16px;
    border-radius: 8px;
    margin-bottom: 18px;
    font-size: 14px;
}

.success {
    background: #e5f5e9;
    color: #206738;
}

.failure {
    background: #fde8e8;
    color: #982f2f;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
}

.field {
    display: flex;
    flex-direction: column;
    gap: 7px;
}

.field.full {
    grid-column: 1 / -1;
}

label {
    font-size: 13px;
    font-weight: 700;
}

input, select, textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #dce2dc;
    border-radius: 8px;
    font: inherit;
    font-size: 14px;
    background: white;
}

textarea {
    resize: vertical;
    min-height: 100px;
}

.form-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 18px;
}

.table-wrap {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 850px;
}

th, td {
    text-align: left;
    padding: 15px 12px;
    border-bottom: 1px solid #edf0ec;
    font-size: 13px;
    vertical-align: middle;
}

th {
    color: #69766e;
    font-size: 12px;
    text-transform: uppercase;
    background: #fafbf9;
}

.guide {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 190px;
}

.guide img, .avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}

.avatar {
    display: grid;
    place-items: center;
    background: #e5ece5;
    color: #203c32;
    font-weight: 700;
}

.guide small {
    display: block;
    margin-top: 4px;
    color: #78827b;
}

.status {
    display: inline-block;
    padding: 5px 9px;
    border-radius: 30px;
    font-size: 11px;
    font-weight: 700;
    text-transform: capitalize;
}

.status.approved {
    background: #e2f5e7;
    color: #24723d;
}

.status.pending {
    background: #fff0d6;
    color: #95631b;
}

.status.rejected {
    background: #fde5e5;
    color: #a32f2f;
}

.actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 7px;
}

.actions form {
    display: inline-flex;
    gap: 5px;
    align-items: center;
}

.actions select {
    width: auto;
    max-width: 115px;
    padding: 7px;
    font-size: 12px;
}

.actions .button {
    padding: 8px 10px;
    font-size: 12px;
}

.empty {
    text-align: center;
    padding: 35px;
    color: #738078;
}

footer {
    text-align: center;
    color: #7b857e;
    padding: 25px;
    font-size: 12px;
}

@media (max-width: 750px) {
    .stats {
        grid-template-columns: repeat(2, 1fr);
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .field.full {
        grid-column: auto;
    }

    .topbar a {
        margin-left: 0;
        margin-right: 13px;
    }
}
</style>
</head>

<body>

<header class="topbar">
    <div class="brand">HeritageHaven</div>
    <nav>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="admin_destinations.php">Destinations</a>
        <a href="guides.php">Public Guide Page</a>
        <a href="index.php">View Website</a>
    </nav>
</header>

<main class="container">

    <div class="heading">
        <div>
            <h1>Manage Local Guides</h1>
            <p class="subtitle">
                Add and manage the local experts featured on HeritageHaven.
            </p>
        </div>

        <a class="button" href="#guide-form">+ Add New Guide</a>
    </div>

    <?php if ($message !== ""): ?>
        <div class="alert success"><?= e($message) ?></div>
    <?php endif; ?>

    <?php if ($error !== ""): ?>
        <div class="alert failure"><?= e($error) ?></div>
    <?php endif; ?>

    <section class="stats">
        <div class="stat">
            <span>Total Guides</span>
            <strong><?= $total ?></strong>
        </div>
        <div class="stat">
            <span>Approved</span>
            <strong><?= $approved ?></strong>
        </div>
        <div class="stat">
            <span>Pending</span>
            <strong><?= $pending ?></strong>
        </div>
        <div class="stat">
            <span>Rejected</span>
            <strong><?= $rejected ?></strong>
        </div>
    </section>

    <section class="panel" id="guide-form">
        <h2><?= $editMode ? "Edit Guide Profile" : "Add New Guide" ?></h2>

        <form action="/HeritageHaven/admin_guide_save.php" method="POST">
            <?php if ($editMode): ?>
                <input type="hidden" name="id" value="<?= (int)$editGuide['id'] ?>">
            <?php endif; ?>

            <div class="form-grid">
                <div class="field">
                    <label for="name">Guide Name *</label>
                    <input id="name" name="name" required maxlength="100"
                           value="<?= e($editGuide['name'] ?? '') ?>"
                           placeholder="Enter full name">
                </div>

                <div class="field">
                    <label for="city">City / Location *</label>
                    <input id="city" name="city" required maxlength="100"
                           value="<?= e($editGuide['city'] ?? '') ?>"
                           placeholder="e.g. Pune, Maharashtra">
                </div>

                <div class="field">
                    <label for="languages">Languages *</label>
                    <input id="languages" name="languages" required maxlength="255"
                           value="<?= e($editGuide['languages'] ?? '') ?>"
                           placeholder="e.g. Marathi, Hindi, English">
                </div>

                <div class="field">
                    <label for="specialization">Specialization *</label>
                    <input id="specialization" name="specialization" required maxlength="150"
                           value="<?= e($editGuide['specialization'] ?? '') ?>"
                           placeholder="e.g. Forts and local history">
                </div>

                <div class="field">
                    <label for="experience_years">Experience (years) *</label>
                    <input id="experience_years" name="experience_years"
                           type="number" min="0" max="80" required
                           value="<?= e($editGuide['experience_years'] ?? '0') ?>">
                </div>

                <div class="field">
                    <label for="status">Profile Status *</label>
                    <select id="status" name="status" required>
                        <?php
                        $currentStatus = $editGuide['status'] ?? 'approved';
                        foreach (['approved', 'pending', 'rejected'] as $s):
                        ?>
                            <option value="<?= e($s) ?>"
                                <?= $currentStatus === $s ? 'selected' : '' ?>>
                                <?= ucfirst($s) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="field full">
                    <label for="photo_url">Guide Photo URL (optional)</label>
                    <input id="photo_url" name="photo_url" type="url" maxlength="500"
                           value="<?= e($editGuide['photo_url'] ?? '') ?>"
                           placeholder="https://example.com/guide-photo.jpg">
                </div>

                <div class="field full">
                    <label for="bio">Guide Biography *</label>
                    <textarea id="bio" name="bio" required
                              placeholder="Write a short introduction about the guide"><?= e($editGuide['bio'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="form-actions">
                <button class="button dark" type="submit">
                    <?= $editMode ? "Save Changes" : "Add Guide" ?>
                </button>

                <?php if ($editMode): ?>
                    <a class="button gray" href="admin_guides.php">Cancel Edit</a>
                <?php endif; ?>
            </div>
        </form>
    </section>

    <section class="panel">
        <h2>All Guide Profiles</h2>

        <?php if (count($guides) > 0): ?>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Guide</th>
                            <th>Specialization</th>
                            <th>Experience</th>
                            <th>Status</th>
                            <th>Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($guides as $guide): ?>
                        <tr>
                            <td>
                                <div class="guide">
                                    <?php if (!empty($guide['photo_url'])): ?>
                                        <img src="<?= e($guide['photo_url']) ?>"
                                             alt="<?= e($guide['name']) ?>"
                                             loading="lazy">
                                    <?php else: ?>
                                        <div class="avatar">
                                            <?= e(strtoupper(substr($guide['name'], 0, 1))) ?>
                                        </div>
                                    <?php endif; ?>

                                    <div>
                                        <strong><?= e($guide['name']) ?></strong>
                                        <small><?= e($guide['city']) ?></small>
                                    </div>
                                </div>
                            </td>

                            <td><?= e($guide['specialization']) ?></td>
                            <td><?= (int)$guide['experience_years'] ?> years</td>

                            <td>
                                <span class="status <?= e($guide['status']) ?>">
                                    <?= e($guide['status']) ?>
                                </span>
                            </td>

                            <td>
                                <div class="actions">
                                    <a class="button dark"
                                       href="admin_guides.php?edit=<?= (int)$guide['id'] ?>#guide-form">
                                        Edit
                                    </a>

                                    <form method="POST" action="admin_guides.php"
                                          onsubmit="return confirm('Are you sure you want to delete this guide?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id"
                                               value="<?= (int)$guide['id'] ?>">
                                        <button class="button red" type="submit">Delete</button>
                                    </form>

                                    <form method="POST" action="admin_guides.php">
                                        <input type="hidden" name="action" value="status">
                                        <input type="hidden" name="id"
                                               value="<?= (int)$guide['id'] ?>">
                                        <select name="status" aria-label="Change guide status">
                                            <?php foreach (['approved', 'pending', 'rejected'] as $s): ?>
                                                <option value="<?= e($s) ?>"
                                                    <?= $guide['status'] === $s ? 'selected' : '' ?>>
                                                    <?= ucfirst($s) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button class="button" type="submit">Update</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty">
                <h3>No guides added yet</h3>
                <p>Use the form above to add your first local guide.</p>
            </div>
        <?php endif; ?>
    </section>

</main>

<footer>
    HeritageHaven Admin Panel &copy; <?= date("Y") ?>
</footer>

</body>
</html>
