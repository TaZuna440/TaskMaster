<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/auth_helpers.php';

require_login();

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
    json_response(['status' => 'error', 'msg' => 'Security validation failed. Try again.']);
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
                json_response(['status' => 'error', 'msg' => 'Please enter a valid task (max 200 characters).']);
            }

            if ($taskDate === '') {
                json_response(['status' => 'error', 'msg' => 'Please choose a date.']);
            }

            $stmt = $pdo->prepare(
                'INSERT INTO tasks (user_id, task_name, task_date, status, created_at) 
                 VALUES (?, ?, ?, "Todo", NOW())'
            );
            $stmt->execute([$userId, $taskName, $taskDate]);

            json_response(['status' => 'success', 'msg' => 'Task added successfully.']);
            break;

        /* 🟡 TOGGLE STATUS */
        case 'toggle':
            $id = (int)($_POST['id'] ?? 0);

            $stmt = $pdo->prepare('SELECT status FROM tasks WHERE id = ? AND user_id = ? LIMIT 1');
            $stmt->execute([$id, $userId]);
            $row = $stmt->fetch();

            if (!$row) {
                json_response(['status' => 'error', 'msg' => 'Task not found.']);
            }

            $newStatus = ($row['status'] === 'Done') ? 'Todo' : 'Done';

            $upd = $pdo->prepare('UPDATE tasks SET status = ? WHERE id = ? AND user_id = ?');
            $upd->execute([$newStatus, $id, $userId]);

            json_response(['status' => 'success', 'msg' => 'Task status updated.']);
            break;

        /* 🔴 DELETE TASK */
        case 'delete':
            $id = (int)($_POST['id'] ?? 0);

            $del = $pdo->prepare('DELETE FROM tasks WHERE id = ? AND user_id = ?');
            $del->execute([$id, $userId]);

            json_response(['status' => 'success', 'msg' => 'Task deleted.']);
            break;

        default:
            json_response(['status' => 'error', 'msg' => 'Unknown action.']);
    }

} catch (PDOException $e) {
    error_log('Task process error: ' . $e->getMessage());
    json_response(['status' => 'error', 'msg' => 'Server error. Please try again.']);
}
