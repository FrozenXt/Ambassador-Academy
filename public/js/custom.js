/* ===========================================================
   NAVBAR
=========================================================== */

const siteNav = document.querySelector(".site-nav");

window.addEventListener("scroll", () => {
    if (window.scrollY > 50) {
        siteNav.classList.add("scrolled");
    } else {
        siteNav.classList.remove("scrolled");
    }
});

/* ===========================================================
   SIDEBAR
=========================================================== */

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

if (hamburgerBtn && sidebar && sidebarClose) {
    hamburgerBtn.addEventListener("click", openSidebar);
    sidebarClose.addEventListener("click", closeSidebar);

    sidebar.querySelectorAll("a").forEach((link) => {
        link.addEventListener("click", closeSidebar);
    });

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            closeSidebar();
        }
    });
}

(function () {
    "use strict";

    document.addEventListener("DOMContentLoaded", init);

    function init() {
        gsap.registerPlugin(ScrollTrigger);

        const items = gsap.utils.toArray(".reveal-item");
        if (!items.length) return;

        items.forEach((item, i) => {
            const bullet = item.querySelector(".reveal-bullet");
            const heading = item.querySelector(".reveal-heading");
            const sub = item.querySelector(".reveal-sub");

            gsap.set(bullet, { scale: 0, transformOrigin: "center center" });
            gsap.set(heading, { opacity: 0, y: 36 });
            gsap.set(sub, { opacity: 0, y: 24 });

            gsap.timeline({
                scrollTrigger: {
                    trigger: item,
                    start: "top 82%",
                    toggleActions: "play reverse play reverse",
                },
                delay: i * 0.08,
            })
                .to(bullet, { scale: 1, duration: 0.4, ease: "back.out(2.2)" })
                .to(
                    heading,
                    { opacity: 1, y: 0, duration: 0.6, ease: "power3.out" },
                    "-=0.25",
                )
                .to(
                    sub,
                    { opacity: 1, y: 0, duration: 0.5, ease: "power2.out" },
                    "-=0.35",
                );
        });
    }
})();

(function () {
    "use strict";

    document.addEventListener("DOMContentLoaded", init);

    function init() {
        gsap.registerPlugin(ScrollTrigger);
        initMenuContentReveal();
        initMenuImageAppear();
    }

    // ---- Content: section titles + menu items fade/slide in per block ----
    function initMenuContentReveal() {
        const blocks = document.querySelectorAll(
            ".menu-wrapper .col-lg-6, .aboutPage, .aboutFeature-strip, .content-wrapper, .gallery-section, .menuStage",
        );
        if (!blocks.length) return;

        blocks.forEach((block) => {
            const title = block.querySelector(".section-title");
            const items = block.querySelectorAll(
                ".menuPage-item, .aboutPage p, .amenity, .content-wrapper .contact-card, .gallery-item img, .content-col",
            );
            const targets = [title, ...items].filter(Boolean);
            if (!targets.length) return;

            gsap.set(targets, { opacity: 0, y: 30 });

            gsap.to(targets, {
                opacity: 1,
                y: 0,
                duration: 0.7,
                ease: "power3.out",
                stagger: 0.12,
                scrollTrigger: {
                    trigger: block,
                    start: "top 80%",
                    toggleActions: "play reverse play reverse",
                },
            });
        });
    }

    // ---- Images: scale down from --qode-apperar-scale to 1 on scroll ----
    // ---- Images: emerge from center (clip-path circle grows) + scale settle ----
    function initMenuImageAppear() {
        const images = document.querySelectorAll(
            ".menu-wrapper .img-frame img, .circle-shot img, .aboutPage-media img, .plate-wrap img, .photo-arch img",
        );
        if (!images.length) return;

        images.forEach((img) => {
            gsap.set(img, {
                "--qode-apperar-scale": 1.15,
                clipPath: "circle(0% at 50% 50%)",
            });

            gsap.to(img, {
                "--qode-apperar-scale": 1,
                clipPath: "circle(75% at 50% 50%)",
                duration: 5,
                ease: "power2.out",
                scrollTrigger: {
                    trigger: img,
                    start: "top 85%",
                    toggleActions: "play reverse play reverse",
                },
            });
        });
    }
})();

const eventSwiper = new Swiper(".event-swiper", {
    loop: true,
    speed: 900,
    effect: "fade",

    fadeEffect: {
        crossFade: true,
    },

    autoplay: {
        delay: 4500,
        disableOnInteraction: false,
    },

    pagination: {
        el: ".event-pagination",
        clickable: true,
        enabled: false, // desktop
    },

    navigation: {
        prevEl: ".event-arrow.prev",
        nextEl: ".event-arrow.next",
        enabled: true, // desktop
    },

    breakpoints: {
        0: {
            navigation: {
                enabled: false,
            },
            pagination: {
                enabled: true,
            },
        },

        768: {
            navigation: {
                enabled: true,
            },
            pagination: {
                enabled: false,
            },
        },
    },

    on: {
        slideChangeTransitionStart() {
            document
                .querySelectorAll(".event-swiper .swiper-slide")
                .forEach((slide) => {
                    slide
                        .querySelectorAll(
                            ".event-eyebrow, .event-title, .event-meta",
                        )
                        .forEach((el) => {
                            el.style.animation = "none";
                            el.offsetHeight;
                            el.style.animation = "";
                        });
                });
        },
    },
});

(function () {
    "use strict";

    document.addEventListener("DOMContentLoaded", init);

    function init() {
        gsap.registerPlugin(ScrollTrigger, ScrollToPlugin);

        const galleryPin = document.getElementById("galleryPin");
        const galleryIntro = document.getElementById("galleryIntro");
        const thumb1 = document.getElementById("thumb1");
        const thumb2 = document.getElementById("thumb2");
        const thumb3 = document.getElementById("thumb3");
        const filmstrip = document.getElementById("filmstrip");
        const filmTrack = document.getElementById("filmTrack");
        const filmOverlay = document.getElementById("filmOverlay");
        const filmTitle = document.getElementById("filmTitle");
        const filmNav = document.getElementById("filmNav");
        const prevBtn = document.getElementById("prevBtn");
        const nextBtn = document.getElementById("nextBtn");

        const slides = Array.from(filmTrack.children);
        const slideCount = slides.length;

        function setActiveSlide(i) {
            filmTitle.textContent = slides[i].dataset.title;
        }

        // position the fixed filmstrip exactly over thumb3, relative to the pin container
        function syncFilmstripRect() {
            const pinRect = galleryPin.getBoundingClientRect();
            const r = thumb3.getBoundingClientRect();
            gsap.set(filmstrip, {
                top: r.top - pinRect.top,
                left: r.left - pinRect.left,
                width: r.width,
                height: r.height,
            });
        }
        syncFilmstripRect();
        window.addEventListener("load", syncFilmstripRect);

        const SCROLL_LENGTH = "+=150%";

        let tl;
        function buildTimeline() {
            if (tl) tl.scrollTrigger && tl.scrollTrigger.kill();
            gsap.set(filmstrip, { opacity: 0 });
            gsap.set(filmOverlay, { opacity: 0 });
            gsap.set(filmNav, { opacity: 0, pointerEvents: "none" });
            filmOverlay.classList.remove("is-active");
            syncFilmstripRect();

            tl = gsap.timeline({
                scrollTrigger: {
                    trigger: galleryPin,
                    start: "top top",
                    end: SCROLL_LENGTH,
                    scrub: 1,
                    pin: true,
                    anticipatePin: 1,
                    onUpdate: (self) => {
                        // phase C spans progress 0.62 -> 1
                        if (self.progress > 0.62) {
                            const p = (self.progress - 0.62) / (1 - 0.62);
                            const idx = Math.min(
                                slideCount - 1,
                                Math.round(p * (slideCount - 1)),
                            );
                            setActiveSlide(idx);
                        } else {
                            setActiveSlide(0);
                        }
                    },
                },
                defaults: { ease: "none" },
            });

            // ---- phase A: hold / subtle parallax (0 -> 1) ----
            tl.to(thumb1, { yPercent: -4, duration: 1 }, 0);
            tl.to(thumb2, { yPercent: 2, duration: 1 }, 0);

            // ---- phase B: expand thumb3 into fullscreen (1 -> 2.2) ----
            tl.to(
                thumb1,
                { opacity: 0, scale: 0.92, duration: 0.5, ease: "power1.in" },
                1,
            );
            tl.to(
                thumb2,
                { opacity: 0, scale: 0.92, duration: 0.5, ease: "power1.in" },
                1,
            );
            tl.to(
                galleryIntro.querySelector(".gallery-title"),
                { opacity: 0, y: -24, duration: 0.5 },
                1,
            );
            tl.to(
                galleryIntro.querySelector(".beer-icon"),
                { opacity: 0, duration: 0.5 },
                1,
            );

            tl.to(filmstrip, { opacity: 1, duration: 0.2 }, 1);
            tl.to(
                filmstrip,
                {
                    top: 0,
                    left: 0,
                    width: "100%",
                    height: "100%",
                    duration: 1.2,
                    ease: "power2.inOut",
                },
                1.15,
            );
            tl.to(
                filmOverlay,
                {
                    opacity: 1,
                    duration: 0.4,
                    onStart: () => filmOverlay.classList.add("is-active"),
                },
                2.0,
            );
            tl.to(
                filmNav,
                {
                    opacity: 1,
                    duration: 0.4,
                    onComplete: () => (filmNav.style.pointerEvents = "auto"),
                },
                2.0,
            );

            // ---- phase C: horizontal slide through remaining images (2.2 -> 3.5) ----
            tl.to(
                filmTrack,
                {
                    xPercent: -100 * (slideCount - 1),
                    duration: 1.3,
                    ease: "none",
                },
                2.2,
            );
        }

        buildTimeline();
        window.addEventListener("resize", () => {
            ScrollTrigger.refresh();
        });

        // nav buttons move the actual page scroll so the single ScrollTrigger stays the source of truth
        function goToSlide(targetIdx) {
            targetIdx = Math.max(0, Math.min(slideCount - 1, targetIdx));
            const st = tl.scrollTrigger;
            const pStart = 0.62,
                pEnd = 1;
            const p = slideCount > 1 ? targetIdx / (slideCount - 1) : 0;
            const overallProgress = pStart + p * (pEnd - pStart);
            const scrollY = st.start + overallProgress * (st.end - st.start);
            gsap.to(window, {
                duration: 0.9,
                scrollTo: { y: scrollY },
                ease: "power2.inOut",
            });
        }

        let currentIdx = 0;
        prevBtn.addEventListener("click", () => {
            currentIdx = Math.max(0, currentIdx - 1);
            goToSlide(currentIdx);
        });
        nextBtn.addEventListener("click", () => {
            currentIdx = Math.min(slideCount - 1, currentIdx + 1);
            goToSlide(currentIdx);
        });
    } // end init
})();

function positionFeatures() {
    // Stop curve positioning on tablets & mobiles
    if (window.innerWidth < 992) {
        document.querySelectorAll(".feature").forEach((feature) => {
            gsap.set(feature, {
                clearProps: "all",
            });
        });

        return;
    }

    const stage = document.getElementById("curveStage");
    if (!stage) return;

    const container = stage.closest(".container");
    if (!container) return;

    const features = document.querySelectorAll(".feature");
    if (!features.length) return;

    const stageRect = stage.getBoundingClientRect();
    const containerRect = container.getBoundingClientRect();

    const offsetX = containerRect.left - stageRect.left;

    const w = containerRect.width;
    const h = stageRect.height;

    const cx = offsetX + w / 2;
    const cy = 0;

    let rx;
    let ry;
    let topOffset;
    let start;
    let end;

    // =============================
    // Desktop
    // =============================
    if (window.innerWidth >= 1400) {
        rx = w * 0.75;
        ry = h * 0.9;
        topOffset = 30;
        start = Math.PI * 0.72;
        end = Math.PI * 0.28;
    }

    // =============================
    // Laptop
    // =============================
    else if (window.innerWidth >= 1200) {
        rx = w * 0.72;
        ry = h * 0.88;
        topOffset = 25;
        start = Math.PI * 0.72;
        end = Math.PI * 0.28;
    }

    // =============================
    // Tablet Landscape
    // =============================
    else if (window.innerWidth >= 992) {
        rx = w * 0.68;
        ry = h * 0.85;
        topOffset = 20;
        start = Math.PI * 0.73;
        end = Math.PI * 0.27;
    }

    // =============================
    // Tablet Portrait
    // =============================
    else if (window.innerWidth >= 768) {
        rx = 768 * 0.99;
        ry = h * 0.8;
        topOffset = 10;
        start = Math.PI * 0.75;
        end = Math.PI * 0.25;
    }

    // =============================
    // Mobile
    // =============================
    else if (window.innerWidth >= 576) {
        rx = w * 0.52;
        ry = h * 0.74;
        topOffset = 0;
        start = Math.PI * 0.77;
        end = Math.PI * 0.23;
    }

    // =============================
    // Small Mobile
    // =============================
    else {
        rx = w * 0.45;
        ry = h * 0.68;
        topOffset = -10;
        start = Math.PI * 0.79;
        end = Math.PI * 0.21;
    }

    features.forEach((feature, i) => {
        const progress =
            features.length === 1 ? 0.5 : i / (features.length - 1);

        const angle = start + (end - start) * progress;

        const x = cx + rx * Math.cos(angle);
        const y = cy + ry * Math.sin(angle);

        gsap.set(feature, {
            left: x,
            top: y + topOffset,
            xPercent: -50,
            yPercent: -50,
        });
    });

    ScrollTrigger.refresh();
}

window.addEventListener("load", positionFeatures);

window.addEventListener("resize", () => {
    clearTimeout(window.featureResize);

    window.featureResize = setTimeout(() => {
        positionFeatures();
    }, 150);
});

(function () {
    "use strict";

    document.addEventListener("DOMContentLoaded", init);

    function init() {
        gsap.registerPlugin(ScrollTrigger);

        const heroSection = document.getElementById("stage");
        const curveSection = document.getElementById("stage2");

        const halfCircle = document.getElementById("halfCircle");
        const halfCirclebg = document.getElementById("halfCirclebg");
        const halfCurve = document.getElementById("halfCurve");

        // Hero dome: 1x -> 5x while the hero section scrolls out of view
        if (heroSection && halfCircle && halfCirclebg) {
            gsap.fromTo(
                [halfCircle, halfCirclebg],
                { scale: 1 },
                {
                    scale: window.innerWidth < 768 ? 2.5 : 5,
                    ease: "none",
                    scrollTrigger: {
                        trigger: heroSection,
                        start: "top top",
                        end: "bottom top",
                        scrub: true,
                    },
                },
            );
        }

        // Curve arc: 5x -> 1x while the curve section scrolls into view
        // (reverse direction of the hero dome's zoom above)
        if (curveSection && halfCurve) {
            gsap.fromTo(
                halfCurve,
                { scale: window.innerWidth < 768 ? 2.5 : 5 },
                {
                    scale: 1,
                    ease: "none",
                    scrollTrigger: {
                        trigger: curveSection,
                        start: "top bottom",
                        end: "top top",
                        scrub: true,
                    },
                },
            );
        }
    }
})();

(function () {
    "use strict";

    document.addEventListener("DOMContentLoaded", init);

    function init() {
        gsap.registerPlugin(ScrollTrigger);
        initHeroIntro();
        initFeatureReveal();
        initStoryReveal();
        initMenuReveal();
        initFooterReveal();
    }

    // Hide elements, then reveal them (staggered) when `trigger` scrolls
    // to `start`; reverses when scrolled back above it.
    function revealOnScroll(
        els,
        trigger,
        { start = "top 85%", y = 30, stagger = 0.12 } = {},
    ) {
        const targets = gsap.utils.toArray(els).filter(Boolean);
        if (!targets.length) return;

        gsap.set(targets, { opacity: 0, y });

        gsap.to(targets, {
            opacity: 1,
            y: 0,
            duration: 0.7,
            ease: "power3.out",
            stagger,
            scrollTrigger: {
                trigger,
                start,
                toggleActions: "play reverse play reverse",
            },
        });
    }

    // ---- Hero: fades/slides in on load, no scroll needed ----
    function initHeroIntro() {
        const eyebrow = document.getElementById("heroEyebrow");
        const heading = document.getElementById("heroHeading");
        const btn = document.getElementById("heroBtn");
        if (!eyebrow || !heading || !btn) return;

        gsap.set([eyebrow, heading, btn], { opacity: 0, y: 24 });

        gsap.to([eyebrow, heading, btn], {
            opacity: 1,
            y: 0,
            duration: 0.9,
            ease: "power3.out",
            stagger: 0.15,
            delay: 0.3,
        });
    }

    // ---- Curve section: feature rings pop/fade in one by one ----
    function initFeatureReveal() {
        const curveSection = document.getElementById("stage2");
        const rings = document.querySelectorAll(".feature .ring");
        const labels = document.querySelectorAll(".feature span");
        if (!curveSection || !rings.length) return;

        // Animate the ring + label only, never the .feature wrapper itself —
        // positionFeatures() owns that element's transform for layout.
        gsap.set(rings, {
            opacity: 0,
            scale: 0.5,
            transformOrigin: "center center",
        });
        gsap.set(labels, { opacity: 0, y: 10 });

        const tl = gsap.timeline({
            scrollTrigger: {
                trigger: curveSection,
                start: "top 60%",
                toggleActions: "play reverse play reverse",
            },
        });

        tl.to(rings, {
            opacity: 1,
            scale: 1,
            duration: 0.5,
            ease: "back.out(2)",
            stagger: 0.08,
        }).to(
            labels,
            {
                opacity: 1,
                y: 0,
                duration: 0.4,
                ease: "power2.out",
                stagger: 0.08,
            },
            "-=0.35",
        );
    }

    // ---- Story section: title + paragraphs slide up ----
    function initStoryReveal() {
        const storySection = document.getElementById("stage3");
        if (!storySection) return;

        const title = storySection.querySelector(".story-title");
        const paras = storySection.querySelectorAll(".story-text");

        revealOnScroll([title, ...paras], storySection, {
            start: "top 75%",
            y: 30,
            stagger: 0.15,
        });
    }

    // ---- Menu section: title, items, gallery images ----
    function initMenuReveal() {
        const menuSection = document.querySelector(".menu-section");
        if (!menuSection) return;

        const title = menuSection.querySelector(".menu-title");
        const menuItems = menuSection.querySelectorAll(".menu-item");
        const galleryImgs = menuSection.querySelectorAll(
            ".menu-gallery .g-img",
        );

        revealOnScroll(title, menuSection, {
            start: "top 75%",
            y: 24,
            stagger: 0,
        });
        revealOnScroll(menuItems, menuSection, {
            start: "top 70%",
            y: 26,
            stagger: 0.12,
        });
        revealOnScroll(galleryImgs, menuSection, {
            start: "top 65%",
            y: 40,
            stagger: 0.15,
        });
    }

    // ---- Footer: info columns + bottom row ----
    function initFooterReveal() {
        const footer = document.querySelector(".site-footer");
        if (!footer) return;

        const infoItems = footer.querySelectorAll(".info-item");
        const middleRow = footer.querySelectorAll(".middle-row > div");

        revealOnScroll(infoItems, footer, {
            start: "top 85%",
            y: 26,
            stagger: 0.12,
        });
        revealOnScroll(middleRow, footer, {
            start: "top 60%",
            y: 20,
            stagger: 0.1,
        });
    }
})();

(function () {
    "use strict";

    document.addEventListener("DOMContentLoaded", init);

    function init() {
        gsap.registerPlugin(ScrollTrigger);
        initBottleJourney();
        initBurgerJourney();
    }

    function lerp(a, b, t) {
        return a + (b - a) * t;
    }
    function docRect(el) {
        const r = el.getBoundingClientRect();
        return {
            top: r.top + window.scrollY,
            left: r.left + window.scrollX,
            width: r.width,
            height: r.height,
        };
    }

    function applyRect(el, a, b, t) {
        if (!a || !b) return;
        gsap.set(el, {
            top: lerp(a.top, b.top, t) - window.scrollY,
            left: lerp(a.left, b.left, t) - window.scrollX,
            width: lerp(a.width, b.width, t),
            height: lerp(a.height, b.height, t),
        });
    }

    function createLeg({
        triggerEl,
        start,
        endTriggerEl,
        end,
        wrapA,
        wrapB,
        flyContainer,
        legs,
        onCapture,
    }) {
        function showMarker(target) {
            gsap.set(flyContainer, { opacity: 0 });
            gsap.set(wrapA, { visibility: "hidden" });
            gsap.set(wrapB, { visibility: "hidden" });
            gsap.set(target, { visibility: "visible" });
        }

        function showFly(t) {
            gsap.set(wrapA, { visibility: "hidden" });
            gsap.set(wrapB, { visibility: "hidden" });
            gsap.set(flyContainer, { opacity: 1 });
            legs.forEach(({ el, getA, getB }) =>
                applyRect(el, getA(), getB(), t),
            );
        }

        function sync(self) {
            if (!self.isActive && self.progress <= 0) showMarker(wrapA);
            else if (!self.isActive && self.progress >= 1) showMarker(wrapB);
            else showFly(self.progress);
        }

        const st = ScrollTrigger.create({
            trigger: triggerEl,
            start,
            endTrigger: endTriggerEl,
            end,
            scrub: true,
            onUpdate: sync,
            onEnter: sync,
            onLeave: sync,
            onEnterBack: sync,
            onLeaveBack: sync,
            onRefresh: (self) => {
                if (onCapture) onCapture();
                sync(self);
            },
        });

        sync(st);
        return st;
    }

    function initBottleJourney() {
        const heroSection = document.getElementById("stage");
        const revealSection = document.getElementById("stage1");
        const curveSection = document.getElementById("stage2");

        const fly = document.getElementById("bottleFly");
        const flyBack = document.getElementById("bottleFlyBack");
        const flyFront = document.getElementById("bottleFlyFront");

        const heroWrap = document.getElementById("bottleWrapHero");
        const revealWrap = document.getElementById("bottleWrapReveal");
        const curveWrap = document.getElementById("bottleWrapCurve");

        const heroBack = document.getElementById("bottleBackHero");
        const heroFront = document.getElementById("bottleFrontHero");
        const revealBack = document.getElementById("bottleBackReveal");
        const revealFront = document.getElementById("bottleFrontReveal");
        const curveBack = document.getElementById("bottleBackCurve");
        const curveFront = document.getElementById("bottleFrontCurve");

        if (!fly || !flyBack || !flyFront) return;
        if (!heroWrap || !revealWrap || !curveWrap) return;

        const rects = {};
        function capture() {
            rects.heroBack = docRect(heroBack);
            rects.heroFront = docRect(heroFront);
            rects.revealBack = docRect(revealBack);
            rects.revealFront = docRect(revealFront);
            rects.curveBack = docRect(curveBack);
            rects.curveFront = docRect(curveFront);
        }
        capture();

        // Leg 1: hero -> reveal (fires as hero scrolls out of view)
        createLeg({
            triggerEl: heroSection,
            start: "bottom bottom",
            endTriggerEl: revealSection,
            end: "top top",
            wrapA: heroWrap,
            wrapB: revealWrap,
            flyContainer: fly,
            legs: [
                {
                    el: flyBack,
                    getA: () => rects.heroBack,
                    getB: () => rects.revealBack,
                },
                {
                    el: flyFront,
                    getA: () => rects.heroFront,
                    getB: () => rects.revealFront,
                },
            ],
            onCapture: capture,
        });

        // Leg 2: reveal -> curve (fires as reveal scrolls out of view)
        createLeg({
            triggerEl: revealSection,
            start: "bottom bottom",
            endTriggerEl: curveSection,
            end: "top top",
            wrapA: revealWrap,
            wrapB: curveWrap,
            flyContainer: fly,
            legs: [
                {
                    el: flyBack,
                    getA: () => rects.revealBack,
                    getB: () => rects.curveBack,
                },
                {
                    el: flyFront,
                    getA: () => rects.revealFront,
                    getB: () => rects.curveFront,
                },
            ],
            onCapture: capture,
        });

        fly.classList.add("is-ready");

        window.addEventListener("load", () => {
            capture();
            ScrollTrigger.refresh();
        });
        window.addEventListener("resize", () => {
            capture();
            ScrollTrigger.refresh();
        });
    }

    function initBurgerJourney() {
        const curveSection = document.getElementById("stage2");
        const storySection = document.getElementById("stage3");
        const fly = document.getElementById("burgerFly");
        const flyImg = document.getElementById("burgerFlyImg");

        const curveWrap = document.getElementById("burgerWrapCurve");
        const storyWrap = document.getElementById("burgerWrapStory");
        const curveImg = document.getElementById("burgerCurve");
        const storyImg = document.getElementById("burgerStory");

        if (!fly || !flyImg || !curveWrap || !storyWrap) return;

        const BREAKPOINT = 768;
        const rects = {};
        let st = null;

        function capture() {
            rects.curve = docRect(curveImg);
            rects.story = docRect(storyImg);
        }

        function enable() {
            if (st) return;
            capture();
            st = createLeg({
                triggerEl: curveSection,
                start: "bottom bottom",
                endTriggerEl: storySection,
                end: "top top",
                wrapA: curveWrap,
                wrapB: storyWrap,
                flyContainer: fly,
                legs: [
                    {
                        el: flyImg,
                        getA: () => rects.curve,
                        getB: () => rects.story,
                    },
                ],
                onCapture: capture,
            });
            fly.classList.add("is-ready");
        }

        function disable() {
            if (st) {
                st.kill();
                st = null;
            }
            fly.classList.remove("is-ready");
            gsap.set(fly, { opacity: 0 });
        }

        function refreshForSize() {
            if (window.innerWidth < BREAKPOINT) {
                disable();
            } else {
                enable();
            }
        }

        refreshForSize();

        window.addEventListener("load", () => {
            if (st) {
                capture();
                ScrollTrigger.refresh();
            }
        });
        window.addEventListener("resize", () => {
            clearTimeout(window.burgerResize);
            window.burgerResize = setTimeout(() => {
                refreshForSize();
                ScrollTrigger.refresh();
            }, 150);
        });
    }
})();

(function () {
    const filterBtns = document.querySelectorAll(".filter-btn");
    const items = document.querySelectorAll(".gallery-item");

    const galleryGrid = document.getElementById("galleryGrid");

    filterBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            filterBtns.forEach((b) => b.classList.remove("active"));
            btn.classList.add("active");
            const filter = btn.dataset.filter;

            galleryGrid.classList.toggle("mode-all", filter === "all");
            galleryGrid.classList.toggle("mode-filtered", filter !== "all");

            items.forEach((item) => {
                const match =
                    filter === "all" || item.dataset.category === filter;
                item.classList.toggle("hide", !match);
            });
        });
    });

    // Lightbox
    const lightbox = document.getElementById("lightbox");
    const lightboxImg = document.getElementById("lightboxImg");
    const closeBtn = document.getElementById("lightboxClose");
    const prevBtn = document.getElementById("lightboxPrev");
    const nextBtn = document.getElementById("lightboxNext");
    let currentIndex = 0;

    function getVisibleItems() {
        return Array.from(items).filter((i) => !i.classList.contains("hide"));
    }

    function openLightbox(index) {
        const visible = getVisibleItems();
        currentIndex = index;
        const img = visible[currentIndex].querySelector("img");
        lightboxImg.src = img.src;
        lightboxImg.alt = img.alt;
        lightbox.classList.add("show");
    }

    items.forEach((item) => {
        item.addEventListener("click", () => {
            const visible = getVisibleItems();
            const idx = visible.indexOf(item);
            openLightbox(idx);
        });
    });

    function showDelta(delta) {
        const visible = getVisibleItems();
        currentIndex = (currentIndex + delta + visible.length) % visible.length;
        const img = visible[currentIndex].querySelector("img");
        lightboxImg.src = img.src;
        lightboxImg.alt = img.alt;
    }

    prevBtn.addEventListener("click", () => showDelta(-1));
    nextBtn.addEventListener("click", () => showDelta(1));
    closeBtn.addEventListener("click", () => lightbox.classList.remove("show"));
    lightbox.addEventListener("click", (e) => {
        if (e.target === lightbox) lightbox.classList.remove("show");
    });
    document.addEventListener("keydown", (e) => {
        if (!lightbox.classList.contains("show")) return;
        if (e.key === "Escape") lightbox.classList.remove("show");
        if (e.key === "ArrowRight") showDelta(1);
        if (e.key === "ArrowLeft") showDelta(-1);
    });
})();
