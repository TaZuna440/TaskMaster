<?php
session_start();
require_once __DIR__ . '/includes/security_headers.php';
require_once __DIR__ . '/includes/auth_helpers.php';
require_once __DIR__ . '/includes/db_connect.php';
require_login();

// Ensure CSRF for all dashboard forms
ensure_csrf_token();

// Flash messaging
$flash = $_SESSION['form_flash'] ?? null;
unset($_SESSION['form_flash']);

$userId = (int)$_SESSION['user']['id'];

// ✅ FIX #11 & #12: Fetch tasks with task_date
$stmt = $pdo->prepare('SELECT id, task_name, task_date, status, created_at FROM tasks WHERE user_id = ? ORDER BY task_date ASC, created_at DESC');
$stmt->execute([$userId]);
$tasks = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Task Master</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./assets/styles/dashboard.css">
  <link rel="icon" href="./assets/images/taskmaster.png">
  <script src="https://kit.fontawesome.com/2ec6a47486.js" crossorigin="anonymous"></script>
</head>
<body>
  <header class="navbar">
    <div class="container">
      <h1><a href="index.php" class="logo">Task <span>Master</span></a></h1>
      <button class="burger-menu" aria-label="Toggle menu" aria-expanded="false">
        <span></span>
        <span></span>
        <span></span>
      </button>
      <nav aria-label="Main navigation">
        <ul>
          <li><a href="index.php" role="button">Home</a></li>
          <li><a href="dashboard.php" role="button" class="active">Dashboard</a></li>
          <li><a href="includes/logout.php" role="button">Logout</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <section class="dashboard-section">
  <div class="dashboard-grid container">

    <!-- LEFT SIDE -->
    <div class="dashboard-left">
      <?php $username = htmlspecialchars($_SESSION['user']['username'] ?? 'there'); ?>
      <div class="greeting-card slide-in">
        <h2 id="greetingTitle" data-username="<?= $username ?>">&nbsp;</h2>
        <p>Stay focused and make progress. What's the next task you'll tackle?</p>
      </div>

      <?php if (!empty($flash)): ?>
        <div class="unified-message flash-message animate-message">
          <?= htmlspecialchars($flash) ?>
        </div>
      <?php endif; ?>

      <!-- ✅ FIX #11: Add task_date input field -->
      <div class="quick-add">
        <form action="includes/task_process.php" method="POST" class="quick-add-form" id="addTaskForm">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
          <input type="hidden" name="action" value="add">
          
          <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <input 
              id="quickAddInput" 
              type="text" 
              name="task_name" 
              placeholder="Add a new task..." 
              maxlength="200" 
              required
              style="flex: 1; min-width: 200px;">
            
            <input 
              type="date" 
              name="task_date" 
              required 
              value="<?= date('Y-m-d') ?>"
              style="padding: 0.75rem; border-radius: 8px; border: 1px solid rgba(0, 123, 255, 0.25); background: rgba(255, 255, 255, 0.06); color: #F5F5F5;">
            
            <button type="submit" class="btn">ADD</button>
          </div>
        </form>
      </div>

      <h2>Your Tasks</h2>

      <div class="task-list">
        <?php if (empty($tasks)): ?>
          <div class="empty-state">
            <h3>Start your day strong ✨</h3>
            <p>Use the "Add a new task" box above to create your first task.</p>
          </div>
        <?php else: ?>
          <ul class="tasks">
            <?php foreach ($tasks as $t): ?>
              <li class="task-item <?= $t['status'] === 'Done' ? 'done' : '' ?>" data-task-id="<?= (int)$t['id'] ?>">
                <div class="task-main">
                  <span class="task-name"><?= htmlspecialchars($t['task_name']) ?></span>
                  <span class="task-status" style="font-size: 0.85rem; padding: 0.25rem 0.5rem; border-radius: 4px; background: <?= $t['status'] === 'Done' ? '#22c55e' : '#f59e0b' ?>; color: white;">
                    <?= htmlspecialchars($t['status']) ?>
                  </span>
                </div>

                <div class="task-meta">
                  <small>📅 <?= htmlspecialchars(date('M d, Y', strtotime($t['task_date']))) ?></small>
                  <small style="opacity: 0.7;">• Created: <?= htmlspecialchars(date('M d, H:i', strtotime($t['created_at']))) ?></small>
                </div>

                <div class="task-controls">
                  <!-- ✅ FIX #13: Add inline edit functionality -->
                  <button class="btn-outline" onclick="editTask(<?= (int)$t['id'] ?>, '<?= htmlspecialchars($t['task_name'], ENT_QUOTES) ?>', '<?= htmlspecialchars($t['task_date']) ?>')">
                    Edit
                  </button>

                  <form action="includes/task_process.php" method="POST" class="inline-form" style="display: inline;">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                    <input type="hidden" name="action" value="toggle">
                    <input type="hidden" name="id" value="<?= (int)$t['id'] ?>">
                    <button class="btn-outline" type="submit">Toggle</button>
                  </form>

                  <form action="includes/task_process.php" method="POST" class="inline-form confirm-delete" style="display: inline;">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= (int)$t['id'] ?>">
                    <button class="btn-outline danger" type="submit">Delete</button>
                  </form>
                </div>

              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
    </div>

    <!-- RIGHT SIDE -->
    <aside class="dashboard-right">
      <div class="calendar-card" id="calendarWidget"></div>
    </aside>

  </div>
</section>

<!-- ✅ FIX #13: Edit Task Modal -->
<div id="editTaskModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); z-index: 1000; align-items: center; justify-content: center;">
  <div style="background: #1F2937; padding: 2rem; border-radius: 12px; max-width: 500px; width: 90%;">
    <h3 style="margin-bottom: 1.5rem; color: #F97316;">Edit Task</h3>
    <form action="includes/task_process.php" method="POST" id="editTaskForm">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
      <input type="hidden" name="action" value="update">
      <input type="hidden" name="id" id="editTaskId">
      
      <div style="margin-bottom: 1rem;">
        <label style="display: block; margin-bottom: 0.5rem;">Task Name:</label>
        <input 
          type="text" 
          name="task_name" 
          id="editTaskName" 
          required 
          maxlength="200"
          style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid rgba(0, 123, 255, 0.25); background: rgba(255, 255, 255, 0.06); color: #F5F5F9;">
      </div>
      
      <div style="margin-bottom: 1.5rem;">
        <label style="display: block; margin-bottom: 0.5rem;">Date:</label>
        <input 
          type="date" 
          name="task_date" 
          id="editTaskDate" 
          required
          style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid rgba(0, 123, 255, 0.25); background: rgba(255, 255, 255, 0.06); color: #F5F5F9;">
      </div>
      
      <div style="display: flex; gap: 1rem; justify-content: flex-end;">
        <button type="button" class="btn-outline" onclick="closeEditModal()">Cancel</button>
        <button type="submit" class="btn" style="background: #F97316; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; cursor: pointer;">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<footer>
    <a href="terms.php" class="link-button">Terms of Service</a>
    <a href="privacy.php" class="link-button">Privacy</a>
    <a href="faq.php" class="link-button">FAQ</a>
    <p>© <?= date('Y') ?> Task Master. Built with focus and passion.</p>
  </footer>

  <script defer src="./assets/js/main.js"></script>
  <script defer src="./assets/js/calendar.js"></script>
  <script>
    // ✅ FIX #13: Edit task functionality
    function editTask(id, name, date) {
      document.getElementById('editTaskId').value = id;
      document.getElementById('editTaskName').value = name;
      document.getElementById('editTaskDate').value = date;
      document.getElementById('editTaskModal').style.display = 'flex';
    }

    function closeEditModal() {
      document.getElementById('editTaskModal').style.display = 'none';
    }

    // Close modal on outside click
    document.getElementById('editTaskModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeEditModal();
      }
    });

    // Auto-hide flash messages
    setTimeout(() => {
      const flash = document.querySelector('.flash-message');
      if (flash) {
        flash.style.transition = "opacity 0.5s ease";
        flash.style.opacity = "0";
        setTimeout(() => flash.remove(), 500);
      }
    }, 4000);
  </script>
</body>
</html>