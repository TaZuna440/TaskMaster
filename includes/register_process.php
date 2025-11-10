<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/auth_helpers.php';

function redirect_with(array $errors = [], string $flash = ''): void {
    store_post_and_errors($errors);
    if ($flash !== '') set_flash($flash);
    header('Location: ../register.php');
    exit;
}

// ✅ FIX #: Stronger password validation
function validate_password(string $password): array {
    $errors = [];
    
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = 'Password must contain at least one uppercase letter.';
    }
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = 'Password must contain at least one lowercase letter.';
    }
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = 'Password must contain at least one number.';
    }
    
    return $errors;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

if (!validate_csrf($_POST['csrf_token'] ?? '')) {
    redirect_with([], 'Security validation failed. Try again.');
}

if (!empty($_POST['website'] ?? '')) {
    header('Location: ../register.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

$errors = [];
if ($username === '' || strlen($username) > 50) {
    $errors[] = 'Please provide a valid username (max 50 characters).';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
    $errors[] = 'Please provide a valid email (max 100 characters).';
}

// ✅ FIX #6: Use stronger password validation
$passwordErrors = validate_password($password);
if ($passwordErrors) {
    $errors = array_merge($errors, $passwordErrors);
}

if ($password !== $confirm) {
    $errors[] = 'Passwords do not match.';
}

if ($errors) redirect_with($errors);

// Unique checks
$stmt = $pdo->prepare('SELECT COUNT(*) AS c FROM users WHERE email = ? OR username = ?');
$stmt->execute([$email, $username]);
$exists = (int)$stmt->fetchColumn();
if ($exists > 0) {
    redirect_with(['Email or username already exists.']);
}

$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare('INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, "user")');
    $stmt->execute([$username, $email, $hash]);
} catch (PDOException $e) {
    error_log('Register DB error: ' . $e->getMessage());
    redirect_with([], 'Server error. Please try again later.');
}

set_flash('Registration successful! You can now login.');
header('Location: ../login.php');
exit;