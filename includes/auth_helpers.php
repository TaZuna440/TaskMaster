<?php
// auth_helpers.php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// CSRF
function ensure_csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validate_csrf(string $token): bool {
    return !empty($token) && hash_equals($_SESSION['csrf_token'] ?? '', $token);
}

// Flash helpers
function set_flash(string $message): void {
    $_SESSION['form_flash'] = $message;
}

function get_flash(): ?string {
    $msg = $_SESSION['form_flash'] ?? null;
    unset($_SESSION['form_flash']);
    return $msg;
}

// Old input / errors
function store_post_and_errors(array $errors = []): void {
    $_SESSION['old_input'] = $_POST;
    $_SESSION['form_errors'] = $errors;
}

function consume_old_and_errors(): array {
    $old = $_SESSION['old_input'] ?? [];
    $errors = $_SESSION['form_errors'] ?? [];
    unset($_SESSION['old_input'], $_SESSION['form_errors']);
    return [$old, $errors];
}

// Auth helpers
function is_logged_in(): bool {
    return isset($_SESSION['user']) && isset($_SESSION['user']['id']);
}

function require_login(): void {
    if (!is_logged_in()) {
        set_flash('Please login to continue.');
        header('Location: login.php');
        exit;
    }
}

function is_admin(): bool {
    return is_logged_in() && ($_SESSION['user']['role'] ?? 'user') === 'admin';
}

function require_admin(): void {
    if (!is_admin()) {
        http_response_code(403);
        echo 'Forbidden';
        exit;
    }
}