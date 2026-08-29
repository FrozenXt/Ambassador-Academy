/* =========================================================
   AMBASSADOR SCHOOL — SHARED JAVASCRIPT
   Used by: index.html (home) and about.html (about page)
   ========================================================= */

document.addEventListener("DOMContentLoaded", function () {
    /* ---------- Broken image safety net (prevents any onerror retry-loop) ---------- */
    document.querySelectorAll("img").forEach((img) => {
        img.addEventListener(
            "error",
            function onImgError() {
                this.removeEventListener("error", onImgError); // fire once, never loop
                this.style.background = "#eee6de";
                this.style.minHeight = "120px";
            },
            { once: true },
        );
    });

    /* ---------- Preloader ---------- */
    const preloader = document.getElementById("preloader");
    window.addEventListener("load", function () {
        setTimeout(() => preloader && preloader.classList.add("loaded"), 300);
    });
    // fallback in case 'load' already fired
    setTimeout(() => preloader && preloader.classList.add("loaded"), 1500);

    /* ---------- AOS init ---------- */
    if (window.AOS) {
        AOS.init({
            duration: 800,
            easing: "ease-out-cubic",
            once: true,
            offset: 60,
        });
    }

    /* ---------- Mobile nav toggle ---------- */
    const hamburger = document.getElementById("hamburger");
    const mainNav = document.getElementById("mainNav");
    if (hamburger && mainNav) {
        hamburger.addEventListener("click", function () {
            mainNav.classList.toggle("open");
            hamburger.classList.toggle("active");
        });
        mainNav.querySelectorAll("a").forEach((link) => {
            link.addEventListener("click", () => {
                mainNav.classList.remove("open");
                hamburger.classList.remove("active");
            });
        });
    }

    /* ---------- Search toggle ---------- */
    const searchToggle = document.getElementById("searchToggle");
    const searchBox = document.getElementById("searchBox");
    const searchClose = document.getElementById("searchClose");
    if (searchToggle && searchBox) {
        searchToggle.addEventListener("click", () => {
            searchBox.classList.toggle("open");
            if (searchBox.classList.contains("open")) {
                searchBox.querySelector("input").focus();
            }
        });
    }
    if (searchClose) {
        searchClose.addEventListener("click", () =>
            searchBox.classList.remove("open"),
        );
    }

    /* ---------- Sticky header shadow on scroll ---------- */
    const header = document.getElementById("siteHeader");
    window.addEventListener("scroll", function () {
        if (header) {
            header.style.boxShadow =
                window.scrollY > 20
                    ? "0 4px 20px rgba(0,0,0,.10)"
                    : "0 2px 14px rgba(0,0,0,.05)";
        }

        /* Back to top button */
        const backToTop = document.getElementById("backToTop");
        if (backToTop) {
            backToTop.classList.toggle("show", window.scrollY > 400);
        }
    });

    /* ---------- Back to top click ---------- */
    const backToTop = document.getElementById("backToTop");
    if (backToTop) {
        backToTop.addEventListener("click", function (e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: "smooth" });
        });
    }

    /* ---------- Hero Slider (home page) ---------- */
    if (document.querySelector(".heroSlider") && window.Swiper) {
        new Swiper(".heroSlider", {
            loop: true,
            autoplay: { delay: 5500, disableOnInteraction: false },
            effect: "fade",
            fadeEffect: { crossFade: true },
            speed: 900,
            navigation: {
                nextEl: "#heroNext",
                prevEl: "#heroPrev",
            },
            pagination: {
                el: ".heroPagination",
                clickable: true,
            },
        });
    }

    /* ---------- Featured Events Swiper (events page) ---------- */
    if (document.querySelector(".featuredSwiper") && window.Swiper) {
        new Swiper(".featuredSwiper", {
            loop: true,
            spaceBetween: 24,
            slidesPerView: 1.15,
            autoplay: { delay: 4500, disableOnInteraction: false },
            navigation: {
                nextEl: "#featNext",
                prevEl: "#featPrev",
            },
            pagination: {
                el: ".featuredSwiper .swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                640: { slidesPerView: 2 },
                992: { slidesPerView: 3 },
            },
        });
    }

    /* ---------- Events Calendar widget (events page) ---------- */
    const calGrid = document.getElementById("calGrid");
    if (calGrid) {
        const monthLabel = document.getElementById("calMonthLabel");
        const monthNames = [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December",
        ];
        const dowNames = ["SUN", "MON", "TUE", "WED", "THU", "FRI", "SAT"];

        // Demo event highlights (only shown for May 2025, matching the school's featured events)
        const eventDays = {
            "2025-4": { 10: "gold", 18: "maroon", 21: "green", 25: "maroon" },
        };

        let current = new Date(2025, 4, 1); // May 2025

        function renderCalendar() {
            const year = current.getFullYear();
            const month = current.getMonth();
            monthLabel.textContent = `${monthNames[month]} ${year}`;

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const daysInPrevMonth = new Date(year, month, 0).getDate();
            const highlights = eventDays[`${year}-${month}`] || {};

            let html = dowNames
                .map((d) => `<div class="cal-dow">${d}</div>`)
                .join("");

            // leading muted days from previous month
            for (let i = firstDay - 1; i >= 0; i--) {
                html += `<div class="cal-day muted">${daysInPrevMonth - i}</div>`;
            }
            // current month days
            for (let d = 1; d <= daysInMonth; d++) {
                const color = highlights[d];
                const cls = color ? `cal-day active tag-${color}` : "cal-day";
                html += `<div class="${cls}">${d}</div>`;
            }
            // trailing days to complete the final week row
            const totalCells = firstDay + daysInMonth;
            const trailing = (7 - (totalCells % 7)) % 7;
            for (let d = 1; d <= trailing; d++) {
                html += `<div class="cal-day muted">${d}</div>`;
            }

            calGrid.innerHTML = html;
        }

        document.getElementById("calPrev").addEventListener("click", () => {
            current.setMonth(current.getMonth() - 1);
            renderCalendar();
        });
        document.getElementById("calNext").addEventListener("click", () => {
            current.setMonth(current.getMonth() + 1);
            renderCalendar();
        });

        renderCalendar();
    }

    /* ---------- Testimonial Swiper (home page) ---------- */
    if (document.querySelector(".testimonial-swiper") && window.Swiper) {
        new Swiper(".testimonial-swiper", {
            loop: true,
            autoplay: { delay: 5000, disableOnInteraction: false },
            speed: 700,
            pagination: {
                el: ".testimonial-pagination",
                clickable: true,
            },
        });
    }

    /* ---------- Leadership Swiper (about page, if present) ---------- */
    if (document.querySelector(".leadership-swiper") && window.Swiper) {
        new Swiper(".leadership-swiper", {
            slidesPerView: 1,
            spaceBetween: 24,
            loop: true,
            autoplay: { delay: 3500, disableOnInteraction: false },
            pagination: { el: ".leadership-pagination", clickable: true },
            breakpoints: {
                640: { slidesPerView: 2 },
                992: { slidesPerView: 4 },
            },
        });
    }

    /* ---------- Animated stat counters (about page) ---------- */
    const counters = document.querySelectorAll("[data-count]");
    if (counters.length) {
        const runCounter = (el) => {
            const target = parseInt(el.getAttribute("data-count"), 10);
            const suffix = el.getAttribute("data-suffix") || "";
            let current = 0;
            const step = Math.max(target / 60, 1);
            const tick = () => {
                current += step;
                if (current >= target) {
                    el.textContent = target + suffix;
                } else {
                    el.textContent = Math.floor(current) + suffix;
                    requestAnimationFrame(tick);
                }
            };
            tick();
        };

        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        runCounter(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.5 },
        );

        counters.forEach((c) => observer.observe(c));
    }

    /* ---------- Contact form (contact page, demo submit) ---------- */
    const contactForm = document.getElementById("contactForm");
    if (contactForm) {
        contactForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = 'Message Sent <i class="fa-solid fa-check"></i>';
            btn.disabled = true;
            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.disabled = false;
                this.reset();
            }, 2500);
        });
    }

    /* ---------- Application form (apply page, demo submit) ---------- */
    const applicationForm = document.getElementById("applicationForm");
    if (applicationForm) {
        applicationForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = 'Submitted <i class="fa-solid fa-check"></i>';
            btn.disabled = true;
            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.disabled = false;
            }, 2500);
        });
    }

    /* ---------- Newsletter form (demo submit) ---------- */
    const newsletterForm = document.getElementById("newsletterForm");
    if (newsletterForm) {
        newsletterForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const input = this.querySelector('input[type="email"]');
            const btn = this.querySelector("button");
            const originalText = btn.textContent;
            btn.textContent = "Subscribed ✓";
            btn.disabled = true;
            setTimeout(() => {
                btn.textContent = originalText;
                btn.disabled = false;
                input.value = "";
            }, 2500);
        });
    }
});
