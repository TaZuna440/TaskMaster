<?php
session_start();
require_once __DIR__ . '/includes/auth_helpers.php';
require_once __DIR__ . '/includes/db_connect.php';
require_login();

// Ensure CSRF for all dashboard forms
ensure_csrf_token();

// Flash messaging
$flash = $_SESSION['form_flash'] ?? null;
unset($_SESSION['form_flash']);

$userId = (int)$_SESSION['user']['id'];

// Fetch tasks for user
$stmt = $pdo->prepare('SELECT id, task_name, status, created_at FROM tasks WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$userId]);
$tasks = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
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
        <p>Stay focused and make progress. What’s the next task you’ll tackle?</p>
      </div>

      <?php if (!empty($flash)): ?>
        <div class="unified-message flash-message animate-message">
          <?= htmlspecialchars($flash) ?>
        </div>
      <?php endif; ?>

      <div class="quick-add">
        <form action="includes/task_process.php" method="POST" class="quick-add-form">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
          <input type="hidden" name="action" value="add">
          <input id="quickAddInput" type="text" name="task_name" placeholder="Add a new task..." maxlength="200" required>
          <button type="submit" class="btn">ADD</button>
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
              <li class="task-item <?= $t['status'] === 'Done' ? 'done' : '' ?>">
                <div class="task-main">
                  <span class="task-name"><?= htmlspecialchars($t['task_name']) ?></span>
                  <span class="task-status"><?= htmlspecialchars($t['status']) ?></span>
                </div>

                <div class="task-meta">
                  <small><?= htmlspecialchars(date('M d, Y H:i', strtotime($t['created_at']))) ?></small>
                </div>

                <div class="task-controls">
                  <a class="btn-outline" href="task_edit.php?id=<?= (int)$t['id'] ?>">Edit</a>

                  <form action="includes/task_process.php" method="POST" class="inline-form">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                    <input type="hidden" name="action" value="toggle">
                    <input type="hidden" name="id" value="<?= (int)$t['id'] ?>">
                    <button class="btn-outline">Toggle</button>
                  </form>

                  <form action="includes/task_process.php" method="POST" class="inline-form">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= (int)$t['id'] ?>">
                    <button class="btn-outline danger">Delete</button>
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

<footer>
    <a href="terms.php" class="link-button">Terms of Service</a>
    <a href="privacy.php" class="link-button">Privacy</a>
    <a href="faq.php" class="link-button">FAQ</a>
    <p>© <?= date('Y') ?> Task Master. Built with focus and passion.</p>
  </footer>

  <script defer src="./assets/js/main.js"></script>
  <script></script>
</body>
</html>