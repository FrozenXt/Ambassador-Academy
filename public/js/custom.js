const siteNav = document.querySelector(".site-nav");

window.addEventListener("scroll", () => {
    if (window.scrollY > 50) {
        siteNav.classList.add("scrolled");
    } else {
        siteNav.classList.remove("scrolled");
    }
});

const swiper = new Swiper("#heroSwiper", {
    loop: true,
    speed: 1000,
    autoplay: {
        delay: 5500,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
    },
    effect: "fade",
    fadeEffect: {
        crossFade: true,
    },
    navigation: {
        prevEl: "#heroPrev",
        nextEl: "#heroNext",
    },
});

document.addEventListener("DOMContentLoaded", () => {
    const items = [
        ...document.querySelectorAll(".gallery-item:not(.quote-cell)"),
    ];
    const lightbox = document.getElementById("lightbox");
    const lbImg = document.getElementById("lbImg");
    const lbCap = document.getElementById("lbCaption");
    const lbTag = document.getElementById("lbTag");

    let current = 0;

    function openLightbox(index) {
        const el = items[index];
        const img = el.querySelector("img");
        lbImg.src = img.src;
        lbImg.alt = img.alt;
        lbCap.textContent = el.dataset.name;
        lbTag.textContent = el.dataset.tag;
        current = index;
        lightbox.classList.add("active");
        document.body.style.overflow = "hidden";
    }

    function closeLightbox() {
        lightbox.classList.remove("active");
        document.body.style.overflow = "";
        lbImg.src = "";
    }

    function navigate(dir) {
        current = (current + dir + items.length) % items.length;
        openLightbox(current);
    }

    items.forEach((el, i) => {
        el.addEventListener("click", () => openLightbox(i));
        el.setAttribute("tabindex", "0");
        el.addEventListener("keydown", (e) => {
            if (e.key === "Enter") openLightbox(i);
        });
    });

    document.getElementById("lbClose").addEventListener("click", closeLightbox);
    document
        .getElementById("lbPrev")
        .addEventListener("click", () => navigate(-1));
    document
        .getElementById("lbNext")
        .addEventListener("click", () => navigate(1));

    lightbox.addEventListener("click", (e) => {
        if (e.target === lightbox) closeLightbox();
    });

    document.addEventListener("keydown", (e) => {
        if (!lightbox.classList.contains("active")) return;
        if (e.key === "Escape") closeLightbox();
        if (e.key === "ArrowLeft") navigate(-1);
        if (e.key === "ArrowRight") navigate(1);
    });
});
/* ── SIDEBAR ─────────────────────────────────────────────── */
const hamburgerBtn = document.getElementById("hamburgerBtn");
const sidebarClose = document.getElementById("sidebarClose");
const sidebar = document.getElementById("sidebar");

function openSidebar() {
    sidebar.classList.add("open");
    sidebar.setAttribute("aria-hidden", "false");
    hamburgerBtn.setAttribute("aria-expanded", "true");
    document.body.style.overflow = "hidden";
}

function closeSidebar() {
    sidebar.classList.remove("open");
    sidebar.setAttribute("aria-hidden", "true");
    hamburgerBtn.setAttribute("aria-expanded", "false");
    document.body.style.overflow = "";
}

hamburgerBtn.addEventListener("click", openSidebar);
sidebarClose.addEventListener("click", closeSidebar);
sidebar
    .querySelectorAll("a")
    .forEach((l) => l.addEventListener("click", closeSidebar));
document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") closeSidebar();
});

/* ── Scroll Reveal (bidirectional) ── */
const revealEls = document.querySelectorAll(
    ".reveal, .reveal-left, .reveal-right, .reveal-scale",
);

const observer = new IntersectionObserver(
    (entries) => {
        entries.forEach((entry) => {
            const el = entry.target;
            if (entry.isIntersecting) {
                el.classList.add("visible");
                el.classList.remove("exit");
            } else {
                // Direction: if element is above viewport → exit upward
                const rect = entry.boundingClientRect;
                if (rect.top < 0) {
                    el.classList.add("exit");
                } else {
                    el.classList.remove("visible");
                    el.classList.remove("exit");
                }
            }
        });
    },
    { threshold: 0.18, rootMargin: "0px 0px -60px 0px" },
);

revealEls.forEach((el) => observer.observe(el));

// ── Update "View All" link helper (ADD THIS FUNCTION at the top) ──
function updateViewAllLink(tab) {
    const viewAllBtn = document.querySelector(".view-all-btn");
    if (viewAllBtn) viewAllBtn.href = `/menu#${tab}`;
}

// ── Tab switching ──
document.querySelectorAll(".tab-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
        document
            .querySelectorAll(".tab-btn")
            .forEach((b) => b.classList.remove("active"));
        document.querySelectorAll(".course-tab-wrapper").forEach((s) => {
            s.classList.remove("active");
            s.classList.remove("visible");
        });

        btn.classList.add("active");
        const section = document.getElementById("tab-" + btn.dataset.tab);
        section.classList.add("active");

        requestAnimationFrame(() => {
            section.classList.add("visible");
        });

        section.querySelectorAll(".course-card").forEach((c, i) => {
            c.style.animation = "none";
            c.offsetHeight;
            c.style.animation = `cardReveal 0.5s ${i * 0.05}s ease both`;
        });

        updateViewAllLink(btn.dataset.tab); // ← ADD THIS LINE inside the click handler
    });
});

// Set default on load (ADD THIS LINE after the forEach) ──
updateViewAllLink("main");

// ── Modal ──
const modal = document.getElementById("itemModal");
const mImg = document.getElementById("modalImg");
const mName = document.getElementById("modalName");
const mDesc = document.getElementById("modalDesc");
const mPrice = document.getElementById("modalPrice");

document.querySelectorAll(".course-card").forEach((card) => {
    card.addEventListener("click", () => {
        const imgSrc = card.querySelector(".card-img-wrap img").src;
        mImg.src = imgSrc;
        mName.textContent = card.dataset.name || "";
        mDesc.textContent = card.dataset.desc || "";
        mPrice.textContent = card.dataset.price || "";
        modal.classList.add("open");
    });
});

document
    .getElementById("modalClose")
    .addEventListener("click", () => modal.classList.remove("open"));
modal.addEventListener("click", (e) => {
    if (e.target === modal) modal.classList.remove("open");
});
document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") modal.classList.remove("open");
});
// ── Activate tab from URL hash on page load ──

(function () {
    const tabButtons = document.querySelectorAll(".tab-btn");
    const tabSections = document.querySelectorAll(".course-tab-wrapper");
    if (!tabButtons.length) return;

    const hash = window.location.hash.replace("#", "");
    let targetBtn = hash
        ? document.querySelector(`.tab-btn[data-tab="${hash}"]`)
        : null;

    // No valid hash match → fall back to whichever button Blade already marked active,
    // or just the first button if none are marked.
    if (!targetBtn) {
        targetBtn = document.querySelector(".tab-btn.active") || tabButtons[0];
    }

    const targetTab = targetBtn.dataset.tab;
    const targetSection = document.getElementById(`tab-${targetTab}`);

    tabButtons.forEach((b) => b.classList.remove("active"));
    tabSections.forEach((s) => {
        s.classList.remove("active");
        s.classList.remove("visible");
    });

    targetBtn.classList.add("active");
    if (targetSection) {
        targetSection.classList.add("active");
        targetSection.classList.add("visible");
    }

    updateViewAllLink(targetTab);
})();
const swiper1 = new Swiper(".testimonial-swiper", {
    slidesPerView: 1,
    spaceBetween: 24,
    centeredSlides: true,
    initialSlide: 0,
    loop: true,
    grabCursor: true,
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
    navigation: false,
    breakpoints: {
        0: { slidesPerView: 1, spaceBetween: 20 },
        768: { slidesPerView: 2, spaceBetween: 20 },
        992: { slidesPerView: 3, spaceBetween: 24 },
    },
    autoplay: {
        delay: 5000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
    },
});
const aboutSwiper = new Swiper(".aboutSwiper", {
    loop: true,
    spaceBetween: 20,
    speed: 800,

    autoplay: {
        delay: 3000,
        disableOnInteraction: false,
    },

    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },

    navigation: false,

    breakpoints: {
        0: {
            slidesPerView: 1,
        },
        576: {
            slidesPerView: 2,
        },
        992: {
            slidesPerView: 3,
        },
    },
});
