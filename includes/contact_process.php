<?php
// contact_process.php
session_start();
require_once __DIR__ . '/db_connect.php'; // provides $pdo

// Configuration
$RATE_LIMIT_WINDOW = 60; // seconds
$RATE_LIMIT_MAX = 5;    // max submissions allowed per window per session

function respond_and_redirect(array $errors = [], string $flash = '') {
    $_SESSION['old_input'] = $_POST;
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_flash'] = $flash;
    header('Location: contact.php');
    exit;
}

// Must be POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

// Rate Limit
$now = time();
if (!isset($_SESSION['contact_rate'])) {
    $_SESSION['contact_rate'] = ['ts' => $now, 'count' => 0];
}
$rate = &$_SESSION['contact_rate'];
if ($now - $rate['ts'] > $RATE_LIMIT_WINDOW) {
    $rate['ts'] = $now;
    $rate['count'] = 0;
}
$rate['count']++;
if ($rate['count'] > $RATE_LIMIT_MAX) {
    respond_and_redirect([], 'Too many messages sent. Please wait and try again.');
}

// CSRF Validation
$sent_csrf = $_POST['csrf_token'] ?? '';
if (empty($sent_csrf) || !hash_equals($_SESSION['csrf_token'] ?? '', $sent_csrf)) {
    respond_and_redirect([], 'Security validation failed. Try again.');
}

// Honeypot
if (!empty($_POST['website'])) {
    // Pretend success if bot
    header('Location: contact.php');
    exit;
}

// Validate Inputs
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');
$errors = [];

if ($name === '' || strlen($name) > 100) $errors[] = 'Please enter a valid name.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) $errors[] = 'Invalid email address.';
if ($message === '' || strlen($message) > 5000) $errors[] = 'Message cannot be empty.';

if ($errors) {
    respond_and_redirect($errors);
}

// Insert into DB
try {
    $stmt = $pdo->prepare("
        INSERT INTO contacts (name, email, message)
        VALUES (:name, :email, :message)
    ");
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':message' => $message,
    ]);
} catch (PDOException $e) {
    error_log("DB error: " . $e->getMessage());
    respond_and_redirect([], 'Server error. Please try again later.');
}

// Success
respond_and_redirect([], '✅ Message sent successfully and saved!');
