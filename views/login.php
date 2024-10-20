<?php
require_once '../core/func/csrf_protection.php';
require_once '../core/v2/database.php';
require_once '../core/v2/.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POSTconfig['csrf_token'];
    if (!validate_csrf_token($csrf_token)) {
        die("Invalid CSRF Token");
    }

    // Process login form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Koneksi database
    $conn = connect_db();
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user) {
        $_SESSION['user'] = $user['username'];
        header('Location: /dashboard');
    } else {
        echo "Username atau password salah.";
    }
}
?>

<form method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
    <label>Username:</label>
    <input type="text" name="username" required>
    <label>Password:</label>
    <input type="password" name="password" required>
    <button type="submit">Login</button>
</form>
