<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/auth_helpers.php';

require_login();

// ================== JSON FETCH FOR CALENDAR ==================
if (isset($_GET['action']) && $_GET['action'] === 'fetch') {
    header('Content-Type: application/json');
    try {
        $stmt = $pdo->prepare(
            'SELECT id, task_name AS title, task_date AS due_date 
             FROM tasks WHERE user_id = ? ORDER BY task_date ASC'
        );
        $stmt->execute([(int)($_SESSION['user']['id'] ?? 0)]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}


function json_response(array $data): never {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function redirect_back(string $flash = ''): never {
    if ($flash !== '') set_flash($flash);
    header('Location: ../dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

if (!validate_csrf($_POST['csrf_token'] ?? '')) {
    redirect_back('Security validation failed. Try again.');
}

$action = $_POST['action'] ?? '';
$userId = (int)($_SESSION['user']['id'] ?? 0);

try {
    switch ($action) {
        
        /* 🟢 ADD NEW TASK */
        case 'add':
            $taskName = trim($_POST['task_name'] ?? '');
            $taskDate = trim($_POST['task_date'] ?? '');

            if ($taskName === '' || mb_strlen($taskName) > 200) {
                redirect_back('Please enter a valid task (max 200 characters).');
            }

            if ($taskDate === '') {
                redirect_back('Please choose a date.');
            }

            // Validate date format
            $dateObj = DateTime::createFromFormat('Y-m-d', $taskDate);
            if (!$dateObj || $dateObj->format('Y-m-d') !== $taskDate) {
                redirect_back('Invalid date format.');
            }

            $stmt = $pdo->prepare(
                'INSERT INTO tasks (user_id, task_name, task_date, status, created_at) 
                 VALUES (?, ?, ?, "Todo", NOW())'
            );
            $stmt->execute([$userId, $taskName, $taskDate]);

            redirect_back('Task added successfully! 🎉');
            break;

        /* 🟡 TOGGLE STATUS */
        case 'toggle':
            $id = (int)($_POST['id'] ?? 0);

            $stmt = $pdo->prepare('SELECT status FROM tasks WHERE id = ? AND user_id = ? LIMIT 1');
            $stmt->execute([$id, $userId]);
            $row = $stmt->fetch();

            if (!$row) {
                redirect_back('Task not found.');
            }

            $newStatus = ($row['status'] === 'Done') ? 'Todo' : 'Done';

            $upd = $pdo->prepare('UPDATE tasks SET status = ? WHERE id = ? AND user_id = ?');
            $upd->execute([$newStatus, $id, $userId]);

            redirect_back('Task status updated! ✓');
            break;

        /* 🔵 UPDATE TASK */
        case 'update':
            $id = (int)($_POST['id'] ?? 0);
            $taskName = trim($_POST['task_name'] ?? '');
            $taskDate = trim($_POST['task_date'] ?? '');

            if ($taskName === '' || mb_strlen($taskName) > 200) {
                redirect_back('Please enter a valid task (max 200 characters).');
            }

            if ($taskDate === '') {
                redirect_back('Please choose a date.');
            }

            // Validate date format
            $dateObj = DateTime::createFromFormat('Y-m-d', $taskDate);
            if (!$dateObj || $dateObj->format('Y-m-d') !== $taskDate) {
                redirect_back('Invalid date format.');
            }

            $stmt = $pdo->prepare(
                'UPDATE tasks SET task_name = ?, task_date = ? WHERE id = ? AND user_id = ?'
            );
            $stmt->execute([$taskName, $taskDate, $id, $userId]);

            if ($stmt->rowCount() === 0) {
                redirect_back('Task not found or no changes made.');
            }

            redirect_back('Task updated successfully! ✓');
            break;

        /* 🔴 DELETE TASK */
        case 'delete':
            $id = (int)($_POST['id'] ?? 0);

            $del = $pdo->prepare('DELETE FROM tasks WHERE id = ? AND user_id = ?');
            $del->execute([$id, $userId]);

            if ($del->rowCount() === 0) {
                redirect_back('Task not found.');
            }

            redirect_back('Task deleted! 🗑️');
            break;

        default:
            redirect_back('Unknown action.');
    }

} catch (PDOException $e) {
    error_log('Task process error: ' . $e->getMessage());
    redirect_back('Server error. Please try again.');
}