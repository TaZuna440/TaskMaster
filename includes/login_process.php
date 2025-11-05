<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/auth_helpers.php';

function redirect_login(array $errors = [], string $flash = ''): void {
    store_post_and_errors($errors);
    if ($flash !== '') set_flash($flash);
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

if (!validate_csrf($_POST['csrf_token'] ?? '')) {
    redirect_login([], 'Security validation failed. Try again.');
}

if (!empty($_POST['website'] ?? '')) {
    header('Location: ../login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$errors = [];
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) $errors[] = 'Please enter a valid email.';
if ($password === '') $errors[] = 'Password is required.';

if ($errors) redirect_login($errors);

$stmt = $pdo->prepare('SELECT id, username, email, password, role FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    redirect_login(['Invalid email or password.']);
}

$_SESSION['user'] = [
    'id' => (int)$user['id'],
    'username' => $user['username'],
    'email' => $user['email'],
    'role' => $user['role'],
];

set_flash('Welcome back, ' . htmlspecialchars($user['username']) . '!');
header('Location: ../dashboard.php');
exit;


