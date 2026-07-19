<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Papa's Bar and Grill</title>

    <!-- Bootstrap -->
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css"
        rel="stylesheet" />
    <!-- AOS - Animate on Scroll -->
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css"
        rel="stylesheet" />
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,500&family=Cormorant+Garamond:ital,wght@0,500;0,600;1,500&family=Jost:wght@300;400;500;600&display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400&display=swap"
        rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="./public/css/bootstrap.min.css" />
    <link rel="stylesheet" href="./public/css/swiper-bundle.min.css" />

    <link rel="stylesheet" href="./public/css/custom.css" />
</head>

<body>
    <div id="main">
        <nav class="site-nav" aria-label="Main navigation">
            <div class="container">
                <div class="d-flex align-items-center">
                    <div class="nav-inner">
                        <div class="nav-logo">
                            <a href="index.html" class="logo-badge">
                                <img
                                    src="public/image/logo.png"
                                    alt="Sultan Arabic"
                                    title="Sultan Arabic" />
                            </a>
                        </div>
                    </div>
                    <!-- ░░░ HAMBURGER (mobile) ░░░ -->
                    <button
                        class="hamburger"
                        id="hamburgerBtn"
                        aria-label="Open menu"
                        aria-expanded="false">
                        <span></span><span></span><span></span>
                    </button>
                </div>
            </div>
        </nav>
        <!-- ░░░ SIDEBAR ░░░ -->
        <aside class="sidebar" id="sidebar" aria-hidden="true">
            <div class="sidebar-head">
                <a href="index.html" class="sidebar-logo">
                    <img
                        src="public/image/logo.png"
                        alt="Sultan Arabic"
                        title="Sultan Arabic" />
                </a>
                <button
                    class="sidebar-close"
                    id="sidebarClose"
                    aria-label="Close menu">
                    <iconify-icon icon="mingcute:close-fill"></iconify-icon>
                </button>
            </div>
            <ul class="sidebar-nav">
                <li><a href="index.html">Home</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="menu.html">Menu</a></li>
                <li><a href="gallery.html">Gallery</a></li>
                <li><a href="contact.html">Contacts</a></li>
            </ul>
            <p class="sidebar-footer">Luxury Arabic Dining · Kathmandu</p>
        </aside>
        <div class="breadcrump-wrapper">
            <div class="container">
                <div class="breadcrump-header">
                    <h1>Signature Cocktail</h1>
                </div>
            </div>
        </div>

        <section class="menuStage section-padding">
            <div class="container">
                <div class="row align-items-center g-5">
                    <!-- Image -->
                    <div class="col-md-5 col-lg-4 offset-lg-1">
                        <div class="photo-arch">
                            <img
                                src="public/image/8.png"
                                alt="Marigold Hour cocktail being poured over ice with lime and mint" />
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="col-md-7 col-lg-6 content-col">
                        <div class="eyebrow">Cocktails</div>
                        <h1 class="section-title">Marigold Hour</h1>
                        <p class="subtitle">
                            mezcal, blood orange, chili-salt rim, smoked cinnamon
                        </p>
                        <p class="description">
                            Smoky mezcal meets fresh blood orange and a whisper of chili on
                            the rim — built slow over a single large-format ice cube and
                            finished with a cinnamon smoke that clears just as it hits the
                            table. Bittersweet, warm, built for golden hour.
                        </p>

                        <div class="row attr-row">
                            <div class="col-4">
                                <span class="attr-label">Base</span>
                                <span class="attr-value">Mezcal</span>
                            </div>
                            <div class="col-4">
                                <span class="attr-label">Style</span>
                                <span class="attr-value">Stirred, smoked</span>
                            </div>
                            <div class="col-4">
                                <span class="attr-label">Served</span>
                                <span class="attr-value">On the rocks</span>
                            </div>
                        </div>

                        <div class="price-box">
                            <span class="price-label">Price</span>
                            <span class="price-value">Rs. 1,450</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <footer class="site-footer section-padding pb-0">
            <div class="container">
                <div class="row row-cols-1 row-cols-md-4 g-0 text-center footer-top">
                    <div class="col info-col">
                        <div class="info-item">
                            <iconify-icon icon="fluent:call-20-regular"></iconify-icon>
                            <h3>Contact Us</h3>
                            <p>014507444, 014509444</p>
                            <a href="tel:014507444" class="btn-outline-gold">Call Us Here</a>
                        </div>
                    </div>

                    <div class="col info-col">
                        <div class="info-item">
                            <iconify-icon icon="weui:location-outlined"></iconify-icon>
                            <h3>Address</h3>
                            <p>Lazimpat, Kathmandu , Nepal</p>
                            <a href="#" class="btn-outline-gold">Get Direction</a>
                        </div>
                    </div>

                    <div class="col info-col">
                        <div class="info-item">
                            <iconify-icon icon="streamline-cyber:email-2"></iconify-icon>
                            <h3>Email Address</h3>
                            <p>info@papabargrill.com</p>
                            <a href="mailto:info@papabargrill.com" class="btn-outline-gold">Message Us</a>
                        </div>
                    </div>

                    <div class="col info-col">
                        <div class="info-item">
                            <iconify-icon icon="fe:clock"></iconify-icon>
                            <h3>Opening Hours</h3>
                            <p>Mon &ndash; Sun:<br />9:30 am &ndash; 12:30 am</p>
                        </div>
                    </div>
                </div>

                <div class="row align-items-center middle-row">
                    <div class="col-12 col-md-4">
                        <p class="footer-copy">
                            &copy; 2025 <span class="accent">Papa's bar and grill</span>.
                            All rights reserved.
                        </p>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="brand-badge">
                            <img src="public/image/logo.png" alt="" />
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="social-row">
                            <a href="#" aria-label="Instagram"><iconify-icon icon="mdi:instagram"></iconify-icon></a>
                            <a href="#" aria-label="Facebook"><iconify-icon icon="ic:outline-facebook"></iconify-icon></a>
                            <a href="#" aria-label="WhatsApp"><iconify-icon icon="mingcute:whatsapp-line"></iconify-icon></a>
                        </div>
                    </div>
                </div>

                <p class="footer-credit">
                    Developed By: <a href="http://">Bent Ray Technologies</a>
                </p>
            </div>
        </footer>
    </div>
    <!-- Scripts -->
    <script src="public/js/bootstrap.bundle.min.js"></script>
    <script src="public/js/swiper-bundle.min.js"></script>
    <script src="public/js/iconify-icon.min.js"></script>
    <script src="public/js/gsap.min.js"></script>
    <script src="public/js/ScrollTrigger.min.js"></script>
    <script src="public/js/text-split.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollToPlugin.min.js"></script>
    <script src="public/js/custom.js"></script>
</body>

</html>