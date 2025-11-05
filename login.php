<?php
session_start();
require_once __DIR__ . '/includes/auth_helpers.php';
ensure_csrf_token();

$old = $_SESSION['old_input'] ?? [];
$errors = $_SESSION['form_errors'] ?? [];
$flash = $_SESSION['form_flash'] ?? null;
unset($_SESSION['old_input'], $_SESSION['form_errors'], $_SESSION['form_flash']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./assets/styles/styles.css">
  <link rel="icon" href="./assets/images/taskmaster.png">
  <script src="https://kit.fontawesome.com/2ec6a47486.js" crossorigin="anonymous"></script>
  <script defer src="./assets/js/contact.js"></script>
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
          <li><a href="about.php" role="button">About</a></li>
          <li><a href="contact.php" role="button">Contact</a></li>
          <li><a href="login.php" role="button" class="active">Login</a></li>
          <li><a href="register.php" class="btn" role="button">Get Started</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <section class="login-section">
    <div class="contact-container fade-in">
      <div class="contact-left">
        <h2 class="section-title">Welcome Back</h2>
        <p class="section-subtitle">Log in to access your dashboard, manage tasks, and keep your focus on what truly matters.</p>
        <p>Don’t have an account yet? <a href="register.php" role="button" class="link-button">Create one here</a>.</p>
      </div>

      <div class="contact-right">
        <h2 class="section-title">Login</h2>

        <?php if (!empty($flash)): ?>
          <div class="unified-message flash-message animate-message"><?= htmlspecialchars($flash) ?></div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
          <div class="unified-message form-errors animate-message">
            <ul>
              <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <form action="includes/login_process.php" method="POST" class="contact-form" novalidate>
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

          <!-- Honeypot -->
          <div style="position:absolute; left:-9999px; top:auto; width:1px; height:1px; overflow:hidden;">
            <label>Leave this field empty</label>
            <input type="text" name="website" tabindex="-1" autocomplete="off">
          </div>

          <input type="email" name="email" placeholder="Email" required value="<?= htmlspecialchars($old['email'] ?? '') ?>">
          <input type="password" name="password" placeholder="Password" required>
          <button type="submit" class="btn">Login</button>
        </form>
      </div>
    </div>
  </section>

  <footer>
    <a href="terms.php" class="link-button">Terms of Service</a>
    <a href="privacy.php" class="link-button">Privacy</a>
    <a href="faq.php" class="link-button">FAQ</a>
    <p>© <?= date('Y') ?> Task Master. Built with focus and passion.</p>
  </footer>

  <script>
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
