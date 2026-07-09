<?php
$pageTitle = "Menu | Sultan Arabic Restaurant";
$pageDescription = "Discover a curated selection of authentic Arabic dishes, signature grills, premium beverages, and exquisite desserts at Sultan & Papas, Kathmandu's luxury dining destination.";
$pageKeywords = "luxury dining Kathmandu, Arabic restaurant Kathmandu, Middle Eastern food Lalitpur, fine dining Nepal, Arabic grills, authentic Arabic cuisine, Sultan and Papas";
include 'header.php';
?>
<!-- ── Page Banner SECTION ── -->
<section class="page-banner">
    <!-- CONTENT -->
    <div class="page-banner-content text-center">
        <h1 class="page-banner-title reveal delay-1">our menu</h1>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%23ffffff'/%3E%3C/svg%3E&#34;);"
            aria-label="breadcrumb">
            <ol class="breadcrumb reveal delay-2">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Menu</li>
            </ol>
        </nav>
    </div>
</section>
<!-- ── Page Menu SECTION ── -->
<section class="page-menu section-padding">
    <div class="container position-relative z-1">
        <!-- ── Tabs ── -->
        <nav class="course-tabs" role="tablist">
            <button class="tab-btn active reveal delay-4" data-tab="main" role="tab"><svg
                    xmlns="http://www.w3.org/2000/svg" height="54" viewBox="0 0 200 54">
                    <path class="btn-shape"
                        d="M173.143 47.9851V47.8514H174.438C180.875 47.8514 186.093 45.1585 186.093 41.8364V40.3026C194.468 37.4559 200 32.5624 200 27C200 21.4377 194.469 16.5441 186.093 13.6974V12.1636C186.093 8.84155 180.875 6.14863 174.438 6.14863H173.143V6.01495C173.143 2.69291 167.925 0 161.488 0H38.5108C32.0739 0 26.8559 2.69291 26.8559 6.01495V6.14863H25.5609C19.1239 6.14863 13.9059 8.84155 13.9059 12.1636V13.6974C5.53098 16.5441 0 21.4377 0 27C0 32.5624 5.53098 37.4559 13.9059 40.3026V41.8364C13.9059 45.1585 19.1239 47.8514 25.5609 47.8514H26.8559V47.9851C26.8559 51.3071 32.0739 54 38.5108 54H161.488C167.925 54 173.143 51.3071 173.143 47.9851Z" />
                </svg>
                <span>Main Courses</span></button>
            <button class="tab-btn reveal delay-4" data-tab="appetizers" role="tab"><svg
                    xmlns="http://www.w3.org/2000/svg" height="54" viewBox="0 0 200 54" fill="none">
                    <path class="btn-shape"
                        d="M173.143 47.9851V47.8514H174.438C180.875 47.8514 186.093 45.1585 186.093 41.8364V40.3026C194.468 37.4559 200 32.5624 200 27C200 21.4377 194.469 16.5441 186.093 13.6974V12.1636C186.093 8.84155 180.875 6.14863 174.438 6.14863H173.143V6.01495C173.143 2.69291 167.925 0 161.488 0H38.5108C32.0739 0 26.8559 2.69291 26.8559 6.01495V6.14863H25.5609C19.1239 6.14863 13.9059 8.84155 13.9059 12.1636V13.6974C5.53098 16.5441 0 21.4377 0 27C0 32.5624 5.53098 37.4559 13.9059 40.3026V41.8364C13.9059 45.1585 19.1239 47.8514 25.5609 47.8514H26.8559V47.9851C26.8559 51.3071 32.0739 54 38.5108 54H161.488C167.925 54 173.143 51.3071 173.143 47.9851Z" />
                </svg>

                <span>Appetizers</span></button>
            <button class="tab-btn reveal delay-4" data-tab="desserts" role="tab"><svg
                    xmlns="http://www.w3.org/2000/svg" height="54" viewBox="0 0 200 54" fill="none">
                    <path class="btn-shape"
                        d="M173.143 47.9851V47.8514H174.438C180.875 47.8514 186.093 45.1585 186.093 41.8364V40.3026C194.468 37.4559 200 32.5624 200 27C200 21.4377 194.469 16.5441 186.093 13.6974V12.1636C186.093 8.84155 180.875 6.14863 174.438 6.14863H173.143V6.01495C173.143 2.69291 167.925 0 161.488 0H38.5108C32.0739 0 26.8559 2.69291 26.8559 6.01495V6.14863H25.5609C19.1239 6.14863 13.9059 8.84155 13.9059 12.1636V13.6974C5.53098 16.5441 0 21.4377 0 27C0 32.5624 5.53098 37.4559 13.9059 40.3026V41.8364C13.9059 45.1585 19.1239 47.8514 25.5609 47.8514H26.8559V47.9851C26.8559 51.3071 32.0739 54 38.5108 54H161.488C167.925 54 173.143 51.3071 173.143 47.9851Z" />
                </svg>

                <span>Desserts</span></button>
        </nav>
        <!-- ── Main Courses ── -->
        <div id="tab-main" class="course-tab-wrapper active container-fluid" style="margin:auto;">
            <div class="course-banner">
                <img src="public/images/13.webp" alt="Lamb specialty dish" />
                <div class="course-overlay">
                    <div class="text-center">
                        <h2 class="title reveal delay-2">
                            sultan’s special menu
                        </h2>
                        <!-- Divider -->
                        <div class="divider-gold reveal delay-3"><i></i></div>
                    </div>

                </div>
            </div>
            <div class="course-grid p-0">

                <div class="course-card reveal delay-5" data-name="Cold Mezze"
                    data-desc="Classic hummus served with pita bread, olive oil, and aromatic spices.">
                    <div class="card-img-wrap">
                        <img src="public/images/3.webp" alt="Cold Mezze">
                    </div>
                    <div class="card-body-text">
                        <div class="card-name">Cold Mezze</div>
                        <div class="card-desc">Classic hummus served with pita bread, olive oil, and aromatic
                            spices.</div>
                        <div class="card-price">NPR 800.00</div>
                    </div>
                </div>

                <div class="course-card reveal delay-5" data-name="Musakhan"
                    data-desc="Juicy char-grilled lamb chops seasoned with Arabic spices." data-price="NPR 480">
                    <div class="card-img-wrap">
                        <img src="public/images/4.jpg" alt="Musakhan">
                    </div>
                    <div class="card-body-text">
                        <div class="card-name">Musakhan</div>
                        <div class="card-desc">Juicy char-grilled lamb chops seasoned with Arabic spices.</div>
                        <div class="card-price">NPR 480.00</div>
                    </div>
                </div>

                <div class="course-card reveal delay-5" data-name="Sea Food Platter"
                    data-desc="Minced meat skewers grilled to perfection with herbs." data-price="NPR 450">
                    <div class="card-img-wrap">
                        <img src="public/images/5.jpg" alt="Sea Food Platter">
                    </div>
                    <div class="card-body-text">
                        <div class="card-name">Sea Food Platter</div>
                        <div class="card-desc">Minced meat skewers grilled to perfection with herbs.</div>
                        <div class="card-price">NPR 450.00</div>
                    </div>
                </div>

                <div class="course-card reveal delay-5" data-name="Lamb Chops"
                    data-desc="Slow-roasted lamb served with fragrant rice, nuts, and spices." data-price="NPR 670">
                    <div class="card-img-wrap">
                        <img src="public/images/6.jpg" alt="Lamb Chops">
                    </div>
                    <div class="card-body-text">
                        <div class="card-name">Lamb Chops</div>
                        <div class="card-desc">Slow-roasted lamb served with fragrant rice, nuts, and spices.</div>
                        <div class="card-price">NPR 670.00</div>
                    </div>
                </div>

                <div class="course-card reveal delay-5" data-name="Shish Tawook"
                    data-desc="Char-grilled marinated chicken skewers bursting with Middle Eastern flavors.">
                    <div class="card-img-wrap">
                        <img src="public/images/7.jpg" alt="Shish Tawook">
                    </div>
                    <div class="card-body-text">
                        <div class="card-name">Shish Tawook</div>
                        <div class="card-desc">Char-grilled marinated chicken skewers with Middle Eastern flavors.
                        </div>
                        <div class="card-price">NPR 67.00</div>
                    </div>
                </div>

                <div class="course-card reveal delay-5" data-name="Marboss"
                    data-desc="Traditional Arabian rice dish with tender, smoky chicken flavors."
                    data-price="NPR 67">
                    <div class="card-img-wrap">
                        <img src="public/images/8.jpg" alt="Marboss">
                    </div>
                    <div class="card-body-text">
                        <div class="card-name">Marboss</div>
                        <div class="card-desc">Traditional Arabian rice dish with tender, smoky chicken flavors.
                        </div>
                        <div class="card-price">NPR 67.00</div>
                    </div>
                </div>

                <div class="course-card reveal delay-5" data-name="Mixed Grill"
                    data-desc="Crispy golden chickpea fritters served with flavorful accompaniments."
                    data-price="NPR 300">
                    <div class="card-img-wrap">
                        <img src="public/images/9.jpg" alt="Mixed Grill">
                    </div>
                    <div class="card-body-text">
                        <div class="card-name">Mixed Grill</div>
                        <div class="card-desc">Crispy golden chickpea fritters served with flavorful accompaniments.
                        </div>
                        <div class="card-price">NPR 300.00</div>
                    </div>
                </div>

                <div class="course-card reveal delay-5" data-name="Kabsa"
                    data-desc="Thinly sliced marinated meat wrapped with fresh vegetables." data-price="NPR 67">
                    <div class="card-img-wrap">
                        <img src="public/images/10.jpg" alt="Kabsa">
                    </div>
                    <div class="card-body-text">
                        <div class="card-name">Kabsa</div>
                        <div class="card-desc">Thinly sliced marinated meat wrapped with fresh vegetables.</div>
                        <div class="card-price">NPR 67.00</div>
                    </div>
                </div>

                <div class="course-card reveal delay-5" data-name="Chicken Grill"
                    data-desc="Rich Egyptian bread pudding topped with nuts and cream." data-price="NPR 900">
                    <div class="card-img-wrap">
                        <img src="public/images/11.webp" alt="Chicken Grill">
                    </div>
                    <div class="card-body-text">
                        <div class="card-name">Chicken Grill</div>
                        <div class="card-desc">Rich Egyptian bread pudding topped with nuts and cream.</div>
                        <div class="card-price">NPR 900.00</div>
                    </div>
                </div>

                <div class="course-card reveal delay-5" data-name="Mashawi Platter"
                    data-desc="Creamy chickpea dip blended with tahini and olive oil." data-price="NPR 67">
                    <div class="card-img-wrap">
                        <img src="public/images/12.webp" alt="Mashawi Platter">
                    </div>
                    <div class="card-body-text">
                        <div class="card-name">Mashawi Platter</div>
                        <div class="card-desc">Creamy chickpea dip blended with tahini and olive oil.</div>
                        <div class="card-price">NPR 67.00</div>
                    </div>
                </div>

                <div class="course-card reveal delay-5" data-name="Kofta Kabab"
                    data-desc="Aromatic spiced rice topped with succulent slow-cooked lamb." data-price="NPR 900">
                    <div class="card-img-wrap">
                        <img src="public/images/13.webp" alt="Kofta Kabab">
                    </div>
                    <div class="card-body-text">
                        <div class="card-name">Kofta Kabab</div>
                        <div class="card-desc">Aromatic spiced rice topped with succulent slow-cooked lamb.</div>
                        <div class="card-price">NPR 900.00</div>
                    </div>
                </div>

                <div class="course-card reveal delay-5" data-name="Sambousek"
                    data-desc="Classic hummus served with pita bread, olive oil, and Arabic spices."
                    data-price="NPR 67">
                    <div class="card-img-wrap">
                        <img src="public/images/14.jpg" alt="Sambousek">
                    </div>
                    <div class="card-body-text">
                        <div class="card-name">Sambousek</div>
                        <div class="card-desc">Classic hummus served with pita bread, olive oil, and Arabic spices.
                        </div>
                        <div class="card-price">NPR 67.00</div>
                    </div>
                </div>

            </div>
        </div>
        <!-- ── Appetizers ── -->
        <div id="tab-appetizers" class="course-tab-wrapper container-fluid" style="margin:auto;">
            <div class="course-banner">
                <img src="public/images/12.webp" alt="Lamb specialty dish" />
                <div class="course-overlay">
                    <div class="text-center">
                        <h2 class="title reveal delay-2">
                            Appetizers
                        </h2>
                        <!-- Divider -->
                        <div class="divider-gold reveal delay-3"><i></i></div>
                    </div>

                </div>
            </div>
            <div class="course-grid p-0">
                <div class="course-card reveal delay-5" data-name="Hummus"
                    data-desc="Silky smooth chickpea dip drizzled with olive oil and paprika." data-price="NPR 250">
                    <div class="card-img-wrap">
                        <img src="public/images/1.webp" alt="Hummus">
                    </div>
                    <div class="card-body-text">
                        <div class="card-name">Hummus</div>
                        <div class="card-desc">Silky smooth chickpea dip drizzled with olive oil and paprika.</div>
                        <div class="card-price">NPR 250.00</div>
                    </div>
                </div>
                <div class="course-card reveal delay-5" data-name="Fattoush Salad"
                    data-desc="Crispy toasted pita salad with fresh vegetables and sumac dressing."
                    data-price="NPR 320">
                    <div class="card-img-wrap">
                        <img src="public/images/2.webp" alt="Fattoush Salad">
                    </div>
                    <div class="card-body-text">
                        <div class="card-name">Fattoush Salad</div>
                        <div class="card-desc">Crispy toasted pita salad with fresh vegetables and sumac dressing.
                        </div>
                        <div class="card-price">NPR 320.00</div>
                    </div>
                </div>
                <div class="course-card reveal delay-5" data-name="Baba Ghanoush"
                    data-desc="Smoky roasted eggplant dip with tahini, garlic and lemon." data-price="NPR 280">
                    <div class="card-img-wrap">
                        <img src="public/images/3.webp" alt="Baba Ghanoush">
                    </div>
                    <div class="card-body-text">
                        <div class="card-name">Baba Ghanoush</div>
                        <div class="card-desc">Smoky roasted eggplant dip with tahini, garlic and lemon.</div>
                        <div class="card-price">NPR 280.00</div>
                    </div>
                </div>
                <div class="course-card reveal delay-5" data-name="Falafel"
                    data-desc="Golden crispy chickpea bites served with tzatziki sauce." data-price="NPR 220">
                    <div class="card-img-wrap">
                        <img src="public/images/4.jpg" alt="Falafel">
                    </div>
                    <div class="card-body-text">
                        <div class="card-name">Falafel</div>
                        <div class="card-desc">Golden crispy chickpea bites served with tzatziki sauce.</div>
                        <div class="card-price">NPR 220.00</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ── Desserts ── -->
        <div id="tab-desserts" class="course-tab-wrapper container-fluid" style="margin:auto;">
            <div class="course-banner">
                <img src="public/images/10.jpg" alt="Lamb specialty dish" />
                <div class="course-overlay">
                    <div class="text-center">
                        <h2 class="title reveal delay-2">
                            Desserts
                        </h2>
                        <!-- Divider -->
                        <div class="divider-gold reveal delay-3"><i></i></div>
                    </div>

                </div>
            </div>
            <div class="course-grid p-0">
                <div class="course-card reveal delay-5" data-name="Baklava"
                    data-desc="Flaky pastry layers filled with nuts and drenched in golden honey syrup."
                    data-price="NPR 350">
                    <div class="card-img-wrap">
                        <img src="public/images/5.jpg" alt="Baklava">
                    </div>
                    <div class="card-body-text">
                        <div class="card-name">Baklava</div>
                        <div class="card-desc">Flaky pastry layers filled with nuts and drenched in golden honey
                            syrup.
                        </div>
                        <div class="card-price">NPR 350.00</div>
                    </div>
                </div>
                <div class="course-card reveal delay-5" data-name="Kunafa"
                    data-desc="Warm shredded pastry over molten cheese, soaked in rose water syrup."
                    data-price="NPR 420">
                    <div class="card-img-wrap">
                        <img src="public/images/6.jpg" alt="Kunafa">
                    </div>
                    <div class="card-body-text">
                        <div class="card-name">Kunafa</div>
                        <div class="card-desc">Warm shredded pastry over molten cheese, soaked in rose water syrup.
                        </div>
                        <div class="card-price">NPR 420.00</div>
                    </div>
                </div>
                <div class="course-card reveal delay-5" data-name="Umm Ali"
                    data-desc="Egyptian bread pudding with cream, raisins, nuts, and coconut flakes."
                    data-price="NPR 380">
                    <div class="card-img-wrap">
                        <img src="public/images/7.jpg" alt="Umm Ali">
                    </div>
                    <div class="card-body-text">
                        <div class="card-name">Umm Ali</div>
                        <div class="card-desc">Egyptian bread pudding with cream, raisins, nuts, and coconut flakes.
                        </div>
                        <div class="card-price">NPR 380.00</div>
                    </div>
                </div>
                <div class="course-card reveal delay-5" data-name="Halva"
                    data-desc="Soft sesame-based confection sweetened with honey and cardamom."
                    data-price="NPR 200">
                    <div class="card-img-wrap">
                        <img src="public/images/8.jpg" alt="Halva">
                    </div>
                    <div class="card-body-text">
                        <div class="card-name">Halva</div>
                        <div class="card-desc">Soft sesame-based confection sweetened with honey and cardamom.</div>
                        <div class="card-price">NPR 200.00</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ── Item Modal ── -->
    <div class="item-modal-overlay reveal delay-7" id="itemModal" role="dialog" aria-modal="true">
        <div class="item-modal">
            <button class="modal-close" id="modalClose" aria-label="Close">✕</button>
            <img class="modal-img" id="modalImg" src="" alt="Menu Image">
            <div class="modal-name" id="modalName"></div>
            <div class="modal-desc" id="modalDesc"></div>
            <div class="modal-price" id="modalPrice"></div>
        </div>
    </div>
</section>
<?php include 'footer.php'; ?>