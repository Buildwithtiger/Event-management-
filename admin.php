<?php
// No output before this line! Ensure no spaces or BOM.
ob_start();
require_once 'config.php'; // config.php must have no output before its own <?php

// Force session to be active (config.php already starts session, but just in case)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Disable caching to prevent weird redirect loops
header("Cache-Control: no-cache, must-revalidate, no-store, private");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// Clear any previous output buffers
while (ob_get_level() > 0) {
    ob_end_clean();
}

// --- LOGIN CHECK (FIXED) ---
$is_logged_in = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

// If not logged in and not trying to login, show login form
if (!$is_logged_in && !isset($_POST['login'])) {
    // Show login form (no redirect loop)
    // We'll output the login page here
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Admin Login</title>
        <style>
            body { font-family: Arial; background: #f4f4f4; }
            .login-form { max-width: 400px; margin: 100px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
            .btn { background: #3867d6; color: white; padding: 10px; border: none; border-radius: 3px; cursor: pointer; width: 100%; }
            input { width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ddd; border-radius: 3px; }
            .alert { background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 10px; border-radius: 3px; }
        </style>
    </head>
    <body>
        <div class="login-form">
            <h2>Admin Login</h2>
            <?php if(isset($error)) echo "<div class='alert'>$error</div>"; ?>
            <form method="POST" action="">
                <input type="text" name="username" placeholder="Username" required autocomplete="off">
                <input type="password" name="password" placeholder="Password" required autocomplete="off">
                <button type="submit" name="login" class="btn">Login</button>
            </form>
            <p style="margin-top:10px; text-align:center;">Default: admin / admin123</p>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Handle login attempt
if (!$is_logged_in && isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = "Please enter username and password.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin_logged_in'] = true;
            // Regenerate session ID for security
            session_regenerate_id(true);
            header('Location: admin.php');
            exit;
        } else {
            $error = "Invalid credentials!";
        }
    }
    // If login fails, show login form with error
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Admin Login</title>
        <style>
            body { font-family: Arial; background: #f4f4f4; }
            .login-form { max-width: 400px; margin: 100px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
            .btn { background: #3867d6; color: white; padding: 10px; border: none; border-radius: 3px; cursor: pointer; width: 100%; }
            input { width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ddd; border-radius: 3px; }
            .alert { background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 10px; border-radius: 3px; }
        </style>
    </head>
    <body>
        <div class="login-form">
            <h2>Admin Login</h2>
            <?php if(isset($error)) echo "<div class='alert'>$error</div>"; ?>
            <form method="POST" action="">
                <input type="text" name="username" placeholder="Username" required autocomplete="off">
                <input type="password" name="password" placeholder="Password" required autocomplete="off">
                <button type="submit" name="login" class="btn">Login</button>
            </form>
            <p style="margin-top:10px; text-align:center;">Default: admin / admin123</p>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// --- FROM HERE ON, USER IS LOGGED IN ---
// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

// CRUD Operations (unchanged but ensure redirects are clean)
if (isset($_POST['add_service'])) {
    $stmt = $pdo->prepare("INSERT INTO services (icon, title, description) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['icon'], $_POST['title'], $_POST['description']]);
    header('Location: admin.php');
    exit;
}
if (isset($_GET['delete_service'])) {
    $pdo->prepare("DELETE FROM services WHERE id = ?")->execute([$_GET['delete_service']]);
    header('Location: admin.php');
    exit;
}
if (isset($_POST['add_gallery'])) {
    $stmt = $pdo->prepare("INSERT INTO gallery (image_path, title) VALUES (?, ?)");
    $stmt->execute([$_POST['image_path'], $_POST['title']]);
    header('Location: admin.php');
    exit;
}
if (isset($_GET['delete_gallery'])) {
    $pdo->prepare("DELETE FROM gallery WHERE id = ?")->execute([$_GET['delete_gallery']]);
    header('Location: admin.php');
    exit;
}
if (isset($_POST['add_price'])) {
    $stmt = $pdo->prepare("INSERT INTO price_plans (title, amount, features) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['title'], $_POST['amount'], $_POST['features']]);
    header('Location: admin.php');
    exit;
}
if (isset($_GET['delete_price'])) {
    $pdo->prepare("DELETE FROM price_plans WHERE id = ?")->execute([$_GET['delete_price']]);
    header('Location: admin.php');
    exit;
}
if (isset($_POST['add_review'])) {
    $stmt = $pdo->prepare("INSERT INTO reviews (name, role, image_path, review_text) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_POST['name'], $_POST['role'], $_POST['image_path'], $_POST['review_text']]);
    header('Location: admin.php');
    exit;
}
if (isset($_GET['delete_review'])) {
    $pdo->prepare("DELETE FROM reviews WHERE id = ?")->execute([$_GET['delete_review']]);
    header('Location: admin.php');
    exit;
}

// Fetch data
$services = $pdo->query("SELECT * FROM services ORDER BY display_order")->fetchAll();
$gallery = $pdo->query("SELECT * FROM gallery ORDER BY display_order")->fetchAll();
$pricePlans = $pdo->query("SELECT * FROM price_plans ORDER BY display_order")->fetchAll();
$reviews = $pdo->query("SELECT * FROM reviews ORDER BY display_order")->fetchAll();
$messages = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Event Organizer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 1200px; margin: 20px auto; padding: 20px; }
        h1 { color: #3867d6; margin-bottom: 20px; }
        .card { background: white; padding: 20px; margin-bottom: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #3867d6; color: white; }
        .btn { display: inline-block; padding: 5px 10px; background: #3867d6; color: white; text-decoration: none; border-radius: 3px; font-size: 12px; margin: 2px; cursor: pointer; border: none; }
        .btn-danger { background: #ff0033; }
        .btn-success { background: #20bf6b; }
        form input, form textarea { width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ddd; border-radius: 3px; }
        .nav { background: #3867d6; padding: 10px; color: white; margin-bottom: 20px; }
        .nav a { color: white; margin-right: 15px; text-decoration: none; }
    </style>
</head>
<body>
    <div class="nav">
        <a href="admin.php">Dashboard</a>
        <a href="admin.php?logout=1" onclick="return confirm('Logout?')">Logout</a>
        <a href="index.php" target="_blank">View Site</a>
    </div>
    <div class="container">
        <h1>Admin Dashboard</h1>

        <!-- Messages -->
        <div class="card">
            <h2>Contact Messages</h2>
            <table>
                <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Date</th></tr></thead>
                <tbody>
                <?php if(count($messages) > 0): ?>
                    <?php foreach($messages as $msg): ?>
                    <tr>
                        <td><?= $msg['id'] ?></td>
                        <td><?= htmlspecialchars($msg['name']) ?></td>
                        <td><?= htmlspecialchars($msg['email']) ?></td>
                        <td><?= htmlspecialchars($msg['subject']) ?></td>
                        <td><?= htmlspecialchars(substr($msg['message'],0,50)) ?>...</td>
                        <td><?= $msg['created_at'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6">No messages yet.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Services -->
        <div class="card">
            <h2>Services</h2>
            <form method="POST" style="margin-bottom:20px">
                <input type="text" name="icon" placeholder="Icon class (e.g., fas fa-music)" required>
                <input type="text" name="title" placeholder="Title" required>
                <textarea name="description" placeholder="Description" required></textarea>
                <button type="submit" name="add_service" class="btn btn-success">Add Service</button>
            </form>
            <table>
                <thead><tr><th>ID</th><th>Icon</th><th>Title</th><th>Description</th><th>Action</th></tr></thead>
                <tbody>
                <?php foreach($services as $s): ?>
                <tr>
                    <td><?= $s['id'] ?></td>
                    <td><i class="<?= $s['icon'] ?>"></i></td>
                    <td><?= $s['title'] ?></td>
                    <td><?= $s['description'] ?></td>
                    <td><a href="?delete_service=<?= $s['id'] ?>" class="btn btn-danger" onclick="return confirm('Delete?')">Delete</a></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Gallery -->
        <div class="card">
            <h2>Gallery</h2>
            <form method="POST">
                <input type="text" name="image_path" placeholder="Image path (e.g., images/g-1.jpg)" required>
                <input type="text" name="title" placeholder="Title" required>
                <button type="submit" name="add_gallery" class="btn btn-success">Add Image</button>
            </form>
            <table>
                <thead><tr><th>ID</th><th>Image Path</th><th>Title</th><th>Action</th></tr></thead>
                <tbody>
                <?php foreach($gallery as $g): ?>
                <tr>
                    <td><?= $g['id'] ?></td>
                    <td><?= $g['image_path'] ?></td>
                    <td><?= $g['title'] ?></td>
                    <td><a href="?delete_gallery=<?= $g['id'] ?>" class="btn btn-danger" onclick="return confirm('Delete?')">Delete</a></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Price Plans -->
        <div class="card">
            <h2>Price Plans</h2>
            <form method="POST">
                <input type="text" name="title" placeholder="Title (e.g., For Weddings)" required>
                <input type="number" step="0.01" name="amount" placeholder="Amount" required>
                <textarea name="features" placeholder="Features (comma separated: full services,decorations,music)" required></textarea>
                <button type="submit" name="add_price" class="btn btn-success">Add Plan</button>
            </form>
            <table>
                <thead><tr><th>ID</th><th>Title</th><th>Amount</th><th>Features</th><th>Action</th></tr></thead>
                <tbody>
                <?php foreach($pricePlans as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= $p['title'] ?></td>
                    <td>$<?= $p['amount'] ?></td>
                    <td><?= $p['features'] ?></td>
                    <td><a href="?delete_price=<?= $p['id'] ?>" class="btn btn-danger" onclick="return confirm('Delete?')">Delete</a></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Reviews -->
        <div class="card">
            <h2>Reviews</h2>
            <form method="POST">
                <input type="text" name="name" placeholder="Client Name" required>
                <input type="text" name="role" placeholder="Role (e.g., Happy Client)" required>
                <input type="text" name="image_path" placeholder="Image path" required>
                <textarea name="review_text" placeholder="Review text" required></textarea>
                <button type="submit" name="add_review" class="btn btn-success">Add Review</button>
            </form>
            <table>
                <thead><tr><th>ID</th><th>Name</th><th>Role</th><th>Review</th><th>Action</th></tr></thead>
                <tbody>
                <?php foreach($reviews as $r): ?>
                <tr>
                    <td><?= $r['id'] ?></td>
                    <td><?= $r['name'] ?></td>
                    <td><?= $r['role'] ?></td>
                    <td><?= substr($r['review_text'],0,50) ?>...</td>
                    <td><a href="?delete_review=<?= $r['id'] ?>" class="btn btn-danger" onclick="return confirm('Delete?')">Delete</a></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
<?php
// No extra spaces after this line
?>