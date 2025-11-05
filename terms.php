<?php 
  session_start();
  require_once __DIR__ . '/includes/auth_helpers.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./assets/styles/styles.css" />
  <link rel="icon" href="./assets/images/taskmaster.png">
  <script src="https://kit.fontawesome.com/2ec6a47486.js" crossorigin="anonymous"></script>
  <script defer src="./assets/js/index.js"></script>
  <title>Terms of Service - Task Master</title>
</head>
<body>
  <header class="navbar">
    <div class="container">
      <h1><a href="<?= is_logged_in() ? 'dashboard.php' : 'index.php' ?>" class="logo">Task <span>Master</span></a></h1>
      
      <button class="burger-menu" aria-label="Toggle menu" aria-expanded="false">
        <span></span>
        <span></span>
        <span></span>
      </button>

      <nav aria-label="Main navigation">
        <ul>
          <li><a href="<?= is_logged_in() ? 'dashboard.php' : 'index.php' ?>" role="button">Home</a></li>
          <li><a href="about.php" role="button">About</a></li>
          <li><a href="contact.php" role="button">Contact</a></li>

          <?php if (is_logged_in()): ?>
            <li><a href="dashboard.php" class="btn" role="button">Dashboard</a></li>
            <li><a href="includes/logout.php" role="button">Logout</a></li>
          <?php else: ?>
            <li><a href="login.php" role="button">Login</a></li>
            <li><a href="register.php" class="btn" role="button">Get Started</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </header>

  <section class="hero about-hero">
    <div class="hero-content fade-in">
      <h2>Terms of Service</h2>
      <p>By using Task Master, you agree to these terms.</p>
    </div>
  </section>

  <div class="dim">
    <section class="privacy-section slide-in">
      <div class="container">
        <h2>Acceptance of Terms</h2>
        <p>By accessing and using Task Master, you accept and agree to be bound by the terms and provision of this agreement. In addition, when using these particular services, you shall be subject to any posted guidelines or rules applicable to such services.</p>

        <h2>User Responsibilities</h2>
        <ul>
            <li>You must provide accurate information</li>
            <li>You must not misuse the platform</li>
        </ul>

        <h2>Account Termination</h2>
        <p>We may suspend accounts that violate our rules.</p>
      </div>

      <h2>Disclaimer</h2>
        <p>Task Master is provided "as is" without warranties.</p>
    </section>
  </div>

  <footer>
    <a href="terms.php" class="link-button">Terms of Service</a>
    <a href="privacy.php" class="link-button">Privacy</a>
    <a href="faq.php" class="link-button">FAQ</a>
    <p>© <?= date('Y') ?> Task Master. Built with focus and passion.</p>
  </footer>
</body>
</html>