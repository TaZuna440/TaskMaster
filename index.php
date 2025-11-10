<?php
session_start();
require_once __DIR__ . '/includes/security_headers.php';
require_once __DIR__ . '/includes/auth_helpers.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Welcome Home</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./assets/styles/styles.css" />
  <link rel="icon" href="./assets/images/taskmaster.png">
  <script src="https://kit.fontawesome.com/2ec6a47486.js" crossorigin="anonymous"></script>
  <script defer src="./assets/js/index.js"></script>
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

  <!-- HERO SECTION -->
  <section class="hero">
    <div class="hero-content fade-in">
      <h2><span class="highlight-text">Take Control</span> of Your Day</h2>
      <p>Plan, track, and achieve your goals — all in one place.</p>
      <div class="hero-buttons">
      <?php if (is_logged_in()): ?>
        <a href="dashboard.php" class="btn-primary" role="button">Go to Dashboard</a>
        <a href="includes/logout.php" class="btn-outline" role="button">Logout</a>
      <?php else: ?>
        <a href="register.php" class="btn-primary" role="button">Get Started</a>
        <a href="login.php" class="btn-outline" role="button">Login</a>
      <?php endif; ?>
</div>

    </div>
  </section>

  <!-- CLOCK & QUOTE SECTION -->
  <section class="clock-section">
    <div class="clock-card">
      <h3 id="clock">00:00:00</h3>
      <p id="quote">"Loading quote..."</p>
    </div>
  </section>

  <!-- FEATURES SECTION -->
  <section class="features">
    <div class="container">
      <h2>Why Choose Task Master?</h2>
      <div class="feature-grid">
        <div class="card">
          <h3>✅ Task Management</h3>
          <p>Add, track, and complete your goals easily.</p>
        </div>
        <div class="card">
          <h3>💡 Smart Dashboard</h3>
          <p>Visualize your progress in one view.</p>
        </div>
        <div class="card">
          <h3>🔐 Secure Login</h3>
          <p>Your data is safe and private with hashed passwords.</p>
        </div>
        <div class="card">
          <h3>💬 Contact Support</h3>
          <p>Reach out directly when you need help.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- TESTIMONIAL / MOTIVATION SECTION -->
  <section class="motivation">
    <blockquote>
      "Small progress every day leads to big results."
    </blockquote>
  </section>

  <!-- CONTACT PREVIEW -->
  <section class="contact-preview">
    <h2>Got Questions or Feedback?</h2>
    <p>Reach out — let’s build better habits together.</p>
    <a href="<?= is_logged_in() ? 'contact.php' : 'login.php' ?>" class="btn-primary">Contact Us</a>
  </section>

  <!-- FOOTER -->
  <footer>
    <a href="terms.php" class="link-button">Terms of Service</a>
    <a href="privacy.php" class="link-button">Privacy</a>
    <a href="faq.php" class="link-button">FAQ</a>
    <p>© <?= date('Y') ?> Task Master. Built with focus and passion.</p>
  </footer>

</body>
</html>
