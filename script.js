document.addEventListener("DOMContentLoaded", () => {
  // 1. MOBILE NAVIGATION MENU TOGGLE
  const navbar = document.querySelector(".navbar");
  const navLinks = document.querySelector(".nav-links");

  const mobileMenuBtn = document.createElement("button");
  mobileMenuBtn.classList.add("mobile-menu-btn");
  mobileMenuBtn.innerHTML = "&#9776;";
  mobileMenuBtn.setAttribute("aria-label", "Toggle navigation menu");

  if (navbar && navLinks) {
    navbar.insertBefore(mobileMenuBtn, navLinks);

    mobileMenuBtn.addEventListener("click", () => {
      navLinks.classList.toggle("nav-active");
    });
  }

  // 2. SMOOTH SCROLLING
  const links = document.querySelectorAll('a[href^="#"]');

  links.forEach((link) => {
    link.addEventListener("click", function (e) {
      const targetId = this.getAttribute("href");
      if (targetId === "#") return;

      const targetElement = document.querySelector(targetId);
      if (targetElement) {
        e.preventDefault();

        if (navLinks) navLinks.classList.remove("nav-active");

        const navHeight = navbar ? navbar.offsetHeight : 0;
        const elementPosition = targetElement.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - navHeight;

        window.scrollTo({
          top: offsetPosition,
          behavior: "smooth",
        });
      }
    });
  });

  // 3. HIGHLIGHT ACTIVE NAV LINK ON SCROLL
  const sections = document.querySelectorAll("section[id]");

  window.addEventListener("scroll", () => {
    let currentSection = "";
    const scrollPosition = window.pageYOffset + 150;

    sections.forEach((section) => {
      const sectionTop = section.offsetTop;
      const sectionHeight = section.offsetHeight;

      if (
        scrollPosition >= sectionTop &&
        scrollPosition < sectionTop + sectionHeight
      ) {
        currentSection = section.getAttribute("id");
      }
    });

    const allNavAnchors = document.querySelectorAll(".nav-links a");
    allNavAnchors.forEach((a) => {
      a.classList.remove("active");
      if (a.getAttribute("href") === `#${currentSection}`) {
        a.classList.add("active");
      }
    });
  });

  // 4. ANIMATED STAT COUNTER
  const statNumbers = document.querySelectorAll(".stat-item h2");
  let animated = false;

  const animateStats = () => {
    const statsSection = document.querySelector(".stats-section");
    if (!statsSection) return;

    const sectionPos = statsSection.getBoundingClientRect().top;
    const screenPos = window.innerHeight;

    if (sectionPos < screenPos && !animated) {
      animated = true;

      statNumbers.forEach((stat) => {
        const rawText = stat.innerText;
        const targetNumber = parseInt(rawText.replace(/\D/g, ""), 10);
        const suffix = rawText.replace(/[0-9]/g, "");

        if (isNaN(targetNumber)) return;

        let count = 0;
        const duration = 2000;
        const increment = Math.ceil(targetNumber / (duration / 16));

        const updateCount = () => {
          count += increment;
          if (count < targetNumber) {
            stat.innerText = count + suffix;
            requestAnimationFrame(updateCount);
          } else {
            stat.innerText = targetNumber + suffix;
          }
        };

        updateCount();
      });
    }
  };

  window.addEventListener("scroll", animateStats);
});