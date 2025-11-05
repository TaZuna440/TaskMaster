// Scroll to top feature
function handleScroll() {
  const homeLink = document.querySelector('nav a[href="#"]');
  if (!homeLink) return; // Guard: some pages don't include a home '#' link
  const scrollThreshold = 300; // Show after scrolling 300px

  if (window.scrollY > scrollThreshold) {
    homeLink.classList.add('scroll-visible');
  } else {
    homeLink.classList.remove('scroll-visible');
  }
}

// Throttle scroll event for better performance
let isScrolling = false;
window.addEventListener('scroll', () => {
  if (!isScrolling) {
    window.requestAnimationFrame(() => {
      handleScroll();
      isScrolling = false;
    });
    isScrolling = true;
  }
});

// Live Clock (safe on pages without #clock)
function updateClock() {
  const clock = document.getElementById("clock");
  if (!clock) return; // guard for pages without the clock element
  const now = new Date();
  clock.textContent = now.toLocaleTimeString();
}
// Initialize only if #clock exists
if (document.getElementById("clock")) {
  setInterval(updateClock, 1000);
  updateClock();
}

// Random Motivational Quotes
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
  const quoteEl = document.getElementById("quote");
  const aboutQuoteEl = document.getElementById("about-quote");
  const random = Math.floor(Math.random() * quotes.length);
  const quoteText = `"${quotes[random]}"`;
  
  if (quoteEl) quoteEl.textContent = quoteText;
  if (aboutQuoteEl) aboutQuoteEl.textContent = quoteText;
}

showRandomQuote();
setInterval(showRandomQuote, 7000);

// Scroll Animation
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

// Responsive Navigation Menu
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

    // Dashboard enhancements
    const quickAdd = document.getElementById('quickAddInput');
    if (quickAdd) {
      quickAdd.focus();
    }

    document.querySelectorAll('form.confirm-delete').forEach(form => {
      form.addEventListener('submit', (e) => {
        if (!confirm('Delete this task?')) {
          e.preventDefault();
        }
      });
    });

    renderCalendar('calendarWidget');

    // Client-side greeting based on local time
    function computeGreeting() {
      const now = new Date();
      const hour = now.getHours();
      if (hour < 12) return 'Good morning';
      if (hour < 18) return 'Good afternoon';
      return 'Good evening';
    }

    function updateGreetingTitle() {
      const el = document.getElementById('greetingTitle');
      if (!el) return;
      const name = el.getAttribute('data-username') || 'there';
      el.textContent = `${computeGreeting()}, ${name} 👋`;
    }

    updateGreetingTitle();
    // Refresh at the start of the next hour, then hourly
    (function scheduleHourlyUpdate() {
      const now = new Date();
      const msUntilNextHour = (60 - now.getMinutes()) * 60 * 1000 - now.getSeconds() * 1000 - now.getMilliseconds();
      setTimeout(() => {
        updateGreetingTitle();
        setInterval(updateGreetingTitle, 60 * 60 * 1000);
      }, Math.max(1000, msUntilNextHour));
    })();
});
