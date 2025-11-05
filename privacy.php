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
  <title>Privacy - Task Master</title>
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
      <h2>Privacy Policy</h2>
      <p>Your privacy is important to us. This policy explains how we collect, use, and protect your information.</p>
    </div>
  </section>

  <div class="dim">
    <section class="privacy-section slide-in">
      <div class="container">
        <h2>Information We Collect</h2>
        <p>We collect information you provide directly to us, such as when you create an account, update your profile, or use our services. This may include your name, email address, and any other information you choose to provide.</p>

        <h2>How We Use Your Information</h2>
        <ul> 
          <li>Account information (email, username)</li>
          <li>Session data</li>
          <li>Task-related data</li>
        </ul>
        <p>We use this information to provide, maintain, and improve our services, communicate with you, and ensure the security of your account.</p>

        <h2>Cookies</h2>
        <p>We use cookies to maintain session and login functionality.</p>

        <h2>Data Security</h2>
        <p>We implement appropriate security measures to protect your information from unauthorized access, alteration, disclosure, or destruction.</p>

        <h2>Your Choices</h2>
        <p>You can update or delete your account information at any time. You may also opt out of receiving promotional communications from us.</p>

        <h2>Contact Us</h2>
        <p>If you have any questions about this Privacy Policy, please <a href="<?= is_logged_in() ? 'contact.php' : 'login.php' ?>" class="link-button">Contact us</a>. <br>
        Note: In order to contact please Sign-Up. For urgent message <a href="markraymund2004@gmail.com" class="link-button">markraymund2004@gmail.com</a></p>
      </div>
    </section>

  <footer>
    <a href="terms.php" class="link-button">Terms of Service</a>
    <a href="privacy.php" class="link-button">Privacy</a>
    <a href="faq.php" class="link-button">FAQ</a>
    <p>© <?= date('Y') ?> Task Master. Built with focus and passion.</p>
  </footer>
</body>
</html>