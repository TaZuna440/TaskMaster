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
  <title>About - Task Master</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./assets/styles/styles.css" />
  <link rel="icon" href="./assets/images/taskmaster.png">
  <script src="https://kit.fontawesome.com/2ec6a47486.js" crossorigin="anonymous"></script>
  <script defer src="./assets/js/about.js"></script>
</head>
<body>
  <!-- NAVBAR --> 
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
<?php if (!empty($_SESSION['user'])): ?>
          <li><a href="dashboard.php" role="button">Dashboard</a></li>
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
  <section class="hero about-hero">
    <div class="hero-content fade-in">
      <h2>About Task Master</h2>
      <p>Your journey to peak productivity starts here.</p>
    </div>
  </section>

  <!-- MISSION SECTION -->
  <section class="mission-section slide-in">
    <div class="container">
      <h2>Our Mission</h2>
      <div class="mission-card">
        <p>To empower individuals and teams with intuitive tools that transform the way they manage time, tasks, and goals. We believe that everyone deserves a clear path to their highest potential.</p>
      </div>
    </div>
  </section>

  <!-- STORY SECTION -->
  <section class="story-section slide-in">
    <div class="container">
      <h2>Our Story</h2>
      <div class="story-content">
        <div class="story-card">
          <h3>The Beginning</h3>
          <p>Born from the real-world challenges of balancing multiple projects, deadlines, and life goals, Task Master emerged as a solution to the universal struggle with productivity and time management.</p>
        </div>
        <div class="story-card">
          <h3>The Vision</h3>
          <p>We envisioned a tool that doesn't just list tasks but transforms how people approach their daily goals, making productivity feel natural and achievement accessible.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- FEATURES HIGHLIGHT -->
  <section class="features about-features slide-in">
    <div class="container">
      <h2>Why Task Master?</h2>
      <div class="feature-grid">
        <div class="card">
          <h3>🎯 Smart Task Management</h3>
          <p>Intuitive organization and prioritization systems that adapt to your workflow.</p>
        </div>
        <div class="card">
          <h3>📊 Insightful Dashboard</h3>
          <p>Visual progress tracking and productivity analytics at your fingertips.</p>
        </div>
        <div class="card">
          <h3>🔒 Privacy First</h3>
          <p>Enterprise-grade security ensuring your data stays private and protected.</p>
        </div>
        <div class="card">
          <h3>🤝 Community Support</h3>
          <p>Join a growing community of focused achievers and productivity enthusiasts.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- CREATOR SECTION -->
  <section class="creator-section slide-in">
    <div class="container">
      <div class="creator-card">
        <h2>Developer Info</h2> 
        <p>Developed by: EJ Boy Pomasin</p>
        <p>Year Level | Semester: Sophomore | 1st Semester</p>
        <p>Section: IT202</p>
        <p>Subject: Web System and Technologies</p>
        <p>Instructor: Richard De Guzman</p>
        <br>
        <p>Student of Global Reciprocal Colleges</p>
      </div>
    </div>
  </section>

  <!-- QUOTE SECTION -->
  <section class="motivation about-quote slide-in">
    <blockquote id="about-quote">
      "The secret to getting ahead is getting started."
    </blockquote>
  </section>

  <!-- CTA SECTION -->
  <section class="cta-section slide-in">
    <div class="container">
      <h2>Ready to Transform Your Productivity?</h2>
      <p>Join thousands of users who have already taken control of their time and goals.</p>
      <div class="cta-buttons">
        <a href="register.php" class="btn-primary">Start Your Journey</a>
        <a href="contact.php" class="btn-outline">Learn More</a>
      </div>
    </div>
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
