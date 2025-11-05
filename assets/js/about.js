// ABOUT PAGE SPECIFIC JAVASCRIPT

// Random Motivational Quotes (ABOUT PAGE ONLY)
const quotes = [
  "Discipline beats motivation.",
  "Small steps every day lead to big change.",
  "Your future is created by what you do today, not tomorrow.",
  "Focus on progress, not perfection.",
  "Success is built one task at a time.",
  "Organization is the key to efficiency.",
  "Time management is life management.",
  "Your productivity defines your progress."
];

function showRandomQuote() {
  const aboutQuoteEl = document.getElementById("about-quote");
  if (!aboutQuoteEl) return; // Guard: only exists on about page
  const random = Math.floor(Math.random() * quotes.length);
  const quoteText = `"${quotes[random]}"`;
  aboutQuoteEl.textContent = quoteText;
}

showRandomQuote();
setInterval(showRandomQuote, 7000);

// Scroll Animation (COMMON FUNCTIONALITY)
function handleScrollAnimation() {
  const elements = document.querySelectorAll('.slide-in');
  const windowHeight = window.innerHeight;

  elements.forEach(element => {
    const elementPosition = element.getBoundingClientRect().top;
    const elementVisible = 150;

    if (elementPosition < windowHeight - elementVisible) {
      element.classList.add('visible');
    }
  });
}

// Throttle scroll event for better performance
let isScrolling = false;

// Initialize scroll animation on load
handleScrollAnimation();
window.addEventListener('scroll', () => {
  if (!isScrolling) {
    window.requestAnimationFrame(() => {
      handleScrollAnimation();
      isScrolling = false;
    });
    isScrolling = true;
  }
});

// Responsive Navigation Menu (COMMON FUNCTIONALITY)
document.addEventListener('DOMContentLoaded', () => {
    const burgerMenu = document.querySelector('.burger-menu');
    const nav = document.querySelector('nav');
    const overlay = document.createElement('div');
    overlay.classList.add('nav-overlay');
    document.body.appendChild(overlay);

  // Ensure the burger button has an initial aria-expanded attribute
  if (burgerMenu && !burgerMenu.hasAttribute('aria-expanded')) {
    burgerMenu.setAttribute('aria-expanded', 'false');
  }

  // Toggle menu
  function toggleMenu() {
    burgerMenu.classList.toggle('active');
    nav.classList.toggle('active');
    document.body.classList.toggle('no-scroll');

    // Keep aria-expanded in sync for screen readers
    if (burgerMenu) {
      const expanded = nav.classList.contains('active') ? 'true' : 'false';
      burgerMenu.setAttribute('aria-expanded', expanded);
    }

    if (!nav.classList.contains('active')) {
      // Closing animation
      overlay.style.opacity = '0';
      setTimeout(() => {
        overlay.classList.remove('active');
        overlay.style.opacity = '';
      }, 400); // Match the CSS transition duration
    } else {
      // Opening
      overlay.classList.add('active');
    }
  }

    // Click events
    burgerMenu.addEventListener('click', toggleMenu);
    overlay.addEventListener('click', toggleMenu);

    // Close menu when clicking a link
    const navLinks = document.querySelectorAll('nav a');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (nav.classList.contains('active')) {
                toggleMenu();
            }
        });
    });

    // Close menu on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && nav.classList.contains('active')) {
            toggleMenu();
        }
    });
});
