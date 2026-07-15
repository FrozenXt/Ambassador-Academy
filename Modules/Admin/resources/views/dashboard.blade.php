@extends('admin::layouts.app')
@section('page_title', 'Dashboard')

@section('extra_css')
    <style>
        /* ── GRILL & BAR THEME VARIABLES ── */
        :root {
            --grill-charcoal: #1c1410;
            --grill-charcoal-2: #241a14;
            --grill-ember: #c2410c;
            --grill-ember-dark: #9a3412;
            --grill-amber: #d97706;
            --grill-amber-dark: #b45309;
            --grill-gold: #f59e0b;
            --grill-crimson: #be123c;
            --grill-bg: #f4f1ec;
            --grill-panel: #fffdfa;
            --grill-border: #ece5da;
            --grill-text: #1c1410;
            --grill-text-muted: #8a7d6d;
        }

        /* ── BASE ── */
        body,
        .wrapper,
        .content-wrapper {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
            background: var(--grill-bg) !important;
        }

        /* ── WELCOME BANNER ── */
        .welcome-banner {
            background: linear-gradient(135deg, var(--grill-charcoal) 0%, var(--grill-charcoal-2) 60%, #2b1c12 100%);
            border-radius: 18px;
            padding: 28px 32px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(28, 20, 16, .25);
        }

        .welcome-banner::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 240px;
            height: 240px;
            background: radial-gradient(circle, rgba(217, 119, 6, .35) 0%, rgba(217, 119, 6, 0) 70%);
            border-radius: 50%;
        }

        .welcome-banner::after {
            content: '';
            position: absolute;
            bottom: -40px;
            right: 160px;
            width: 140px;
            height: 140px;
            background: radial-gradient(circle, rgba(190, 18, 60, .25) 0%, rgba(190, 18, 60, 0) 70%);
            border-radius: 50%;
        }

        .welcome-banner .brand-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: .62rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--grill-gold);
            background: rgba(217, 119, 6, .15);
            border: 1px solid rgba(217, 119, 6, .3);
            padding: 3px 10px;
            border-radius: 99px;
            margin-bottom: 8px;
            position: relative;
            z-index: 2;
        }

        .welcome-banner h4 {
            font-size: 1.3rem;
            font-weight: 800;
            color: #fff;
            margin: 0 0 4px;
            position: relative;
            z-index: 2;
        }

        .welcome-banner .sub {
            font-size: .78rem;
            color: rgba(255, 255, 255, .5);
            position: relative;
            z-index: 2;
        }

        .banner-actions {
            display: flex;
            gap: 8px;
            position: relative;
            z-index: 2;
        }

        .banner-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 8px 18px;
            border-radius: 9px;
            font-size: .75rem;
            font-weight: 700;
            text-decoration: none;
            transition: all .15s;
            border: 1.5px solid transparent;
        }

        .banner-btn-primary {
            background: linear-gradient(135deg, var(--grill-amber), var(--grill-ember));
            color: #fff;
            border-color: transparent;
            box-shadow: 0 4px 14px rgba(217, 119, 6, .35);
        }

        .banner-btn-primary:hover {
            filter: brightness(1.08);
            color: #fff;
            text-decoration: none;
        }

        .banner-btn-ghost {
            background: rgba(255, 255, 255, .07);
            color: rgba(255, 255, 255, .85);
            border-color: rgba(255, 255, 255, .15);
        }

        .banner-btn-ghost:hover {
            background: rgba(255, 255, 255, .14);
            color: #fff;
            text-decoration: none;
        }

        /* ── STAT GRID ── */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 24px;
        }

        @media (max-width: 1200px) {
            .stat-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 700px) {
            .stat-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .stat-card {
            background: var(--grill-panel);
            border-radius: 16px;
            padding: 20px 18px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            border: 1px solid var(--grill-border);
            box-shadow: 0 1px 3px rgba(28, 20, 16, .05);
            text-decoration: none;
            color: inherit;
            transition: transform .16s, box-shadow .16s, border-color .16s;
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--grill-amber), var(--grill-ember));
            opacity: 0;
            transition: opacity .16s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 24px rgba(28, 20, 16, .12);
            border-color: rgba(217, 119, 6, .3);
            text-decoration: none;
            color: inherit;
        }

        .stat-card:hover::after {
            opacity: 1;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .si-amber {
            background: #fef3c7;
            color: var(--grill-amber-dark);
        }

        .si-emerald {
            background: #d1fae5;
            color: #047857;
        }

        .si-rose {
            background: #ffe4e6;
            color: var(--grill-crimson);
        }

        .si-sky {
            background: #fde68a;
            color: var(--grill-ember-dark);
        }

        .stat-label {
            font-size: .64rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: var(--grill-text-muted);
            margin-top: 2px;
        }

        .stat-value {
            font-size: 1.85rem;
            font-weight: 800;
            color: var(--grill-text);
            line-height: 1;
        }

        .stat-sub {
            font-size: .68rem;
            color: var(--grill-text-muted);
            margin-top: 1px;
        }

        /* ── MAIN LAYOUT ── */
        .dash-grid {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 18px;
            align-items: start;
        }

        @media (max-width: 960px) {
            .dash-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ── PANEL ── */
        .panel {
            background: var(--grill-panel);
            border-radius: 16px;
            border: 1px solid var(--grill-border);
            box-shadow: 0 1px 3px rgba(28, 20, 16, .05);
            overflow: hidden;
            margin-bottom: 18px;
        }

        .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 20px;
            border-bottom: 1px solid var(--grill-border);
            background: linear-gradient(180deg, #fffaf3 0%, var(--grill-panel) 100%);
        }

        .panel-title {
            font-size: .86rem;
            font-weight: 800;
            color: var(--grill-text);
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
        }

        .panel-title .ico-amber {
            color: var(--grill-amber-dark);
        }

        .panel-title .ico-rose {
            color: var(--grill-crimson);
        }

        .panel-title .ico-emerald {
            color: #047857;
        }

        .panel-title .ico-def {
            color: var(--grill-amber);
        }

        .pill {
            display: inline-flex;
            align-items: center;
            padding: 2px 10px;
            border-radius: 99px;
            font-size: .64rem;
            font-weight: 700;
        }

        .pill-red {
            background: #ffe4e6;
            color: var(--grill-crimson);
        }

        .pill-amber {
            background: #fef3c7;
            color: var(--grill-amber-dark);
        }

        .pill-green {
            background: #d1fae5;
            color: #047857;
        }

        .pill-blue {
            background: #e0f2fe;
            color: #0369a1;
        }

        .btn-sm-action {
            font-size: .71rem;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 8px;
            border: 1.5px solid var(--grill-border);
            background: #fffaf3;
            color: var(--grill-ember-dark);
            text-decoration: none;
            transition: all .14s;
            white-space: nowrap;
        }

        .btn-sm-action:hover {
            border-color: var(--grill-amber);
            color: #fff;
            background: linear-gradient(135deg, var(--grill-amber), var(--grill-ember));
            text-decoration: none;
        }

        /* ── FOOD ITEM GRID ── */
        .food-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            padding: 14px;
        }

        @media (max-width: 900px) {
            .food-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 600px) {
            .food-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .food-card {
            border-radius: 12px;
            overflow: hidden;
            background: #fdfaf5;
            border: 1px solid var(--grill-border);
            text-decoration: none;
            color: inherit;
            display: block;
            transition: transform .16s, box-shadow .16s;
        }

        .food-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 18px rgba(28, 20, 16, .12);
            text-decoration: none;
            color: inherit;
        }

        .food-thumb {
            aspect-ratio: 1;
            background: #f3f4f6;
            position: relative;
        }

        .food-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .food-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #d1c8b8;
            font-size: 1.3rem;
        }

        .food-status {
            position: absolute;
            top: 6px;
            right: 6px;
            font-size: .58rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 99px;
        }

        .food-info {
            padding: 9px 10px;
        }

        .food-name {
            font-size: .71rem;
            font-weight: 700;
            color: var(--grill-text);
            line-height: 1.25;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            margin-bottom: 3px;
        }

        .food-price {
            font-size: .69rem;
            font-weight: 800;
            color: var(--grill-ember);
        }

        /* ── CATEGORY ROWS ── */
        .cat-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 20px;
            border-bottom: 1px solid var(--grill-border);
            transition: background .12s;
            text-decoration: none;
            color: inherit;
        }

        .cat-row:last-child {
            border-bottom: none;
        }

        .cat-row:hover {
            background: #fff7ea;
            text-decoration: none;
            color: inherit;
        }

        .cat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            flex-shrink: 0;
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--grill-amber-dark);
            font-size: 1rem;
        }

        .cat-name {
            font-size: .81rem;
            font-weight: 700;
            color: var(--grill-text);
            margin-bottom: 2px;
        }

        .cat-meta {
            font-size: .68rem;
            color: var(--grill-text-muted);
        }

        .cat-count-badge {
            margin-left: auto;
            font-size: .68rem;
            font-weight: 800;
            color: #fff;
            background: linear-gradient(135deg, var(--grill-amber), var(--grill-ember));
            padding: 4px 11px;
            border-radius: 99px;
            flex-shrink: 0;
        }

        /* ── MESSAGES TABLE ── */
        .msg-table {
            width: 100%;
            border-collapse: collapse;
        }

        .msg-table thead th {
            font-size: .63rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .7px;
            color: var(--grill-text-muted);
            padding: 10px 14px;
            background: #fffaf3;
            border-bottom: 1px solid var(--grill-border);
            white-space: nowrap;
        }

        .msg-table tbody td {
            padding: 11px 14px;
            font-size: .8rem;
            color: #4b3f33;
            border-bottom: 1px solid #f7f2ea;
            vertical-align: middle;
        }

        .msg-table tbody tr:last-child td {
            border-bottom: none;
        }

        .msg-table tbody tr:hover td {
            background: #fff7ea;
        }

        .msg-table tbody tr.is-unread td {
            background: #fef9ec;
        }

        .msg-table tbody tr.is-unread:hover td {
            background: #fef1d1;
        }

        .avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: linear-gradient(135deg, #fde68a, #fbbf24);
            color: var(--grill-ember-dark);
            font-size: .62rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .btn-view {
            font-size: .65rem;
            font-weight: 700;
            padding: 4px 11px;
            border-radius: 7px;
            border: 1.5px solid var(--grill-border);
            color: var(--grill-ember-dark);
            text-decoration: none;
            transition: all .13s;
        }

        .btn-view:hover {
            background: linear-gradient(135deg, var(--grill-amber), var(--grill-ember));
            color: #fff;
            border-color: transparent;
            text-decoration: none;
        }

        /* ── RIGHT SIDEBAR ── */
        .sec-divider {
            font-size: .61rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--grill-text-muted);
            padding: 11px 20px 7px;
            border-bottom: 1px solid var(--grill-border);
            background: #fffaf3;
        }

        .mini-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 9px 20px;
            border-bottom: 1px solid var(--grill-border);
            font-size: .78rem;
        }

        .mini-row:last-child {
            border-bottom: none;
        }

        .mini-label {
            color: #4b3f33;
            font-weight: 500;
        }

        .mini-val {
            font-size: .71rem;
            font-weight: 800;
            padding: 3px 11px;
            border-radius: 99px;
        }

        .mv-green {
            background: #d1fae5;
            color: #047857;
        }

        .mv-amber {
            background: #fef3c7;
            color: var(--grill-amber-dark);
        }

        .mv-sky {
            background: #fde68a;
            color: var(--grill-ember-dark);
        }

        .mv-rose {
            background: #ffe4e6;
            color: var(--grill-crimson);
        }

        /* ── PROGRESS BARS ── */
        .prog-wrap {
            padding: 15px 20px 17px;
        }

        .prog-row {
            margin-bottom: 14px;
        }

        .prog-row:last-child {
            margin-bottom: 0;
        }

        .prog-meta {
            display: flex;
            justify-content: space-between;
            font-size: .72rem;
            color: #4b3f33;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .prog-bg {
            height: 6px;
            background: #f3ede1;
            border-radius: 99px;
            overflow: hidden;
        }

        .prog-fill {
            height: 100%;
            border-radius: 99px;
            transition: width .5s;
        }

        .pf-amber {
            background: linear-gradient(90deg, var(--grill-amber), var(--grill-gold));
        }

        .pf-emerald {
            background: #10b981;
        }

        .pf-red {
            background: linear-gradient(90deg, var(--grill-crimson), var(--grill-ember));
        }

        /* ── COUNTER CELLS ── */
        .counter-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1px;
            background: var(--grill-border);
        }

        .counter-cell {
            background: var(--grill-panel);
            padding: 15px 10px;
            text-align: center;
        }

        .counter-num {
            font-size: 1.55rem;
            font-weight: 800;
            color: var(--grill-text);
            line-height: 1;
            margin-bottom: 3px;
        }

        .counter-lbl {
            font-size: .61rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: var(--grill-text-muted);
        }

        /* ── STOCK ── */
        .stock-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            border-bottom: 1px solid var(--grill-border);
            font-size: .78rem;
        }

        .stock-item:last-child {
            border-bottom: none;
        }

        .stock-name {
            color: var(--grill-text);
            font-weight: 500;
        }

        .stock-out {
            background: #ffe4e6;
            color: var(--grill-crimson);
            font-size: .63rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 99px;
        }

        .stock-low {
            background: #fef3c7;
            color: var(--grill-amber-dark);
            font-size: .63rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 99px;
        }

        /* ── SETTINGS ── */
        .settings-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            padding: 15px;
        }

        .settings-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            padding: 13px 6px;
            border-radius: 11px;
            border: 1.5px solid var(--grill-border);
            background: #fffaf3;
            color: #4b3f33;
            font-size: .67rem;
            font-weight: 700;
            text-decoration: none;
            transition: all .14s;
        }

        .settings-btn i {
            font-size: 1rem;
            color: var(--grill-amber-dark);
        }

        .settings-btn:hover {
            border-color: transparent;
            background: linear-gradient(135deg, var(--grill-amber), var(--grill-ember));
            color: #fff;
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(194, 65, 12, .3);
        }

        .settings-btn:hover i {
            color: #fff;
        }

        /* ── EMPTY STATE ── */
        .empty-box {
            padding: 28px 16px;
            text-align: center;
            color: var(--grill-text-muted);
            font-size: .78rem;
        }

        .empty-box i {
            font-size: 1.4rem;
            display: block;
            margin-bottom: 7px;
            color: #dcd2c0;
        }

        /* ── PANEL FOOTER ── */
        .panel-foot {
            padding: 11px 14px;
            text-align: right;
            border-top: 1px solid var(--grill-border);
        }
    </style>
@endsection

@section('admin_content')

    @php
        $siteSettings = app(\Modules\Common\Services\SiteSettingService::class);
        $siteName = $siteSettings->getByKey('site_name', 'Restaurant');
    @endphp

    {{-- ── WELCOME BANNER ── --}}
    <div class="welcome-banner">
        <div style="position:relative;z-index:2;">
            <span class="brand-tag"><i class="fas fa-fire"></i> {{ $siteName }} Admin</span>
            <h4><i class="fas fa-utensils mr-2"></i>Welcome back, {{ session('admin_name', 'Admin') }}</h4>
            <p class="sub"><i class="far fa-calendar-alt mr-1"></i>{{ now()->format('l, d F Y') }}</p>
        </div>
        <div class="banner-actions">
            <a href="{{ route('admin.settings.general') }}" class="banner-btn banner-btn-primary">
                <i class="fas fa-cog" style="font-size:.7rem;"></i> Settings
            </a>
        </div>
    </div>

    {{-- ── TOP STAT CARDS ── --}}
    <div class="stat-grid">

        <a href="{{ route('admin.products.index') }}" class="stat-card">
            <div class="stat-icon si-amber"><i class="fas fa-utensils"></i></div>
            <div>
                <div class="stat-value">{{ $totalProducts ?? 0 }}</div>
                <div class="stat-label">Food Items</div>
                <div class="stat-sub">{{ $activeProducts ?? 0 }} active</div>
            </div>
        </a>

        <a href="{{ route('admin.categories.index') }}" class="stat-card">
            <div class="stat-icon si-emerald"><i class="fas fa-tags"></i></div>
            <div>
                <div class="stat-value">{{ $totalCategories ?? 0 }}</div>
                <div class="stat-label">Categories</div>
                <div class="stat-sub">{{ $activeCategories ?? 0 }} active</div>
            </div>
        </a>

        <a href="{{ route('admin.contacts.index') }}" class="stat-card">
            <div class="stat-icon si-rose"><i class="fas fa-envelope"></i></div>
            <div>
                <div class="stat-value">{{ $unreadContacts ?? 0 }}</div>
                <div class="stat-label">Unread Messages</div>
                <div class="stat-sub">in your inbox</div>
            </div>
        </a>

        <a href="{{ route('admin.counters.index') }}" class="stat-card">
            <div class="stat-icon si-sky"><i class="fas fa-eye"></i></div>
            <div>
                <div class="stat-value">{{ $totalSiteViews ?? 0 }}</div>
                <div class="stat-label">Site Views</div>
                <div class="stat-sub">all time</div>
            </div>
        </a>

    </div>

    {{-- ── MAIN GRID ── --}}
    <div class="dash-grid">

        {{-- ===== LEFT COLUMN ===== --}}
        <div>

            {{-- Recent Food Items --}}
            <div class="panel">
                <div class="panel-header">
                    <h3 class="panel-title">
                        <i class="fas fa-utensils ico-amber"></i> Recent Menu Items
                        @if (isset($totalProducts) && $totalProducts > 0)
                            <span class="pill pill-amber">{{ $totalProducts }} total</span>
                        @endif
                    </h3>
                    <div style="display:flex;gap:7px;">
                        <a href="{{ route('admin.products.create') }}" class="btn-sm-action">
                            <i class="fas fa-plus mr-1"></i> Add Item
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="btn-sm-action">View All</a>
                    </div>
                </div>

                @if (isset($recentProducts) && $recentProducts->count())
                    <div class="food-grid">
                        @foreach ($recentProducts as $item)
                            <a href="{{ route('admin.products.edit', $item->id) }}" class="food-card">
                                <div class="food-thumb">
                                    @if ($item->image)
                                        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}"
                                            loading="lazy">
                                    @else
                                        <div class="food-placeholder"><i class="fas fa-utensils"></i></div>
                                    @endif
                                    <span class="food-status {{ $item->status == 'active' ? 'pill-green' : 'pill-red' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </div>
                                <div class="food-info">
                                    <div class="food-name">{{ $item->name }}</div>
                                    <div class="food-price">NPR {{ number_format($item->price, 2) }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="empty-box">
                        <i class="fas fa-utensils"></i>
                        No menu items yet.
                    </div>
                @endif

                <div class="panel-foot">
                    <a href="{{ route('admin.products.index') }}" class="btn-sm-action">
                        All Menu Items &rarr;
                    </a>
                </div>
            </div>

            {{-- Categories --}}
            <div class="panel">
                <div class="panel-header">
                    <h3 class="panel-title">
                        <i class="fas fa-tags ico-amber"></i> Categories
                        <span class="pill pill-amber">{{ $totalCategories ?? 0 }} total</span>
                    </h3>
                    <a href="{{ route('admin.categories.create') }}" class="btn-sm-action">
                        <i class="fas fa-plus mr-1"></i> New Category
                    </a>
                </div>

                @forelse($categories ?? [] as $category)
                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="cat-row">
                        <div class="cat-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div class="cat-name">{{ $category->name }}</div>
                            <div class="cat-meta">{{ $category->status == 'active' ? 'Active' : 'Inactive' }}</div>
                        </div>
                        <span class="cat-count-badge">
                            {{ $category->products_count ?? ($category->products ? $category->products->count() : 0) }}
                            items
                        </span>
                    </a>
                @empty
                    <div class="empty-box">
                        <i class="fas fa-tags"></i>
                        No categories yet. <a href="{{ route('admin.categories.create') }}">Create one</a>
                    </div>
                @endforelse

                <div class="panel-foot">
                    <a href="{{ route('admin.categories.index') }}" class="btn-sm-action">View All Categories &rarr;</a>
                </div>
            </div>

            {{-- Recent Messages --}}
            <div class="panel">
                <div class="panel-header">
                    <h3 class="panel-title">
                        <i class="fas fa-envelope ico-rose"></i> Recent Messages
                        @if (($unreadContacts ?? 0) > 0)
                            <span class="pill pill-red">{{ $unreadContacts }} new</span>
                        @endif
                    </h3>
                    <a href="{{ route('admin.contacts.index') }}" class="btn-sm-action">View All</a>
                </div>

                <table class="msg-table">
                    <thead>
                        <tr>
                            <th>From</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>When</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentContacts ?? [] as $contact)
                            <tr class="{{ $contact->isUnread() ? 'is-unread' : '' }}">
                                <td>
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <div class="avatar">{{ strtoupper(substr($contact->display_name, 0, 1)) }}</div>
                                        <span style="{{ $contact->isUnread() ? 'font-weight:700;color:#1c1410;' : '' }}">
                                            {{ Str::limit($contact->display_name, 14) }}
                                        </span>
                                    </div>
                                </td>
                                <td
                                    style="{{ $contact->isUnread() ? 'font-weight:600;color:#1c1410;' : 'color:#8a7d6d;' }}">
                                    {{ Str::limit($contact->subject ?? 'No Subject', 28) }}
                                </td>
                                <td>
                                    @php
                                        $pc = match ($contact->status) {
                                            'unread' => 'pill-red',
                                            'replied' => 'pill-green',
                                            default => 'pill-blue',
                                        };
                                    @endphp
                                    <span class="pill {{ $pc }}">{{ ucfirst($contact->status) }}</span>
                                </td>
                                <td style="color:#a89b89;font-size:.72rem;">
                                    {{ $contact->created_at->diffForHumans() }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.contacts.show', $contact->id) }}" class="btn-view">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-box"><i class="fas fa-inbox"></i> No messages yet.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="panel-foot">
                    <a href="{{ route('admin.contacts.index') }}" class="btn-sm-action">View All Messages &rarr;</a>
                </div>
            </div>

        </div>
        {{-- end left --}}

        {{-- ===== RIGHT COLUMN ===== --}}
        <div>

            {{-- Content Overview --}}
            <div class="panel">
                <div class="panel-header">
                    <h3 class="panel-title"><i class="fas fa-chart-bar ico-def"></i> Content Overview</h3>
                </div>

                <div class="sec-divider">Menu Items</div>
                <div class="mini-row">
                    <span class="mini-label">Total Items</span>
                    <span class="mini-val mv-amber">{{ $totalProducts ?? 0 }}</span>
                </div>
                <div class="mini-row">
                    <span class="mini-label">Active Items</span>
                    <span class="mini-val mv-green">{{ $activeProducts ?? 0 }}</span>
                </div>
                <div class="mini-row">
                    <span class="mini-label">Inactive Items</span>
                    <span class="mini-val mv-sky">{{ $inactiveProducts ?? 0 }}</span>
                </div>

                <div class="sec-divider">Categories</div>
                <div class="mini-row">
                    <span class="mini-label">Total Categories</span>
                    <span class="mini-val mv-amber">{{ $totalCategories ?? 0 }}</span>
                </div>
                <div class="mini-row">
                    <span class="mini-label">Active Categories</span>
                    <span class="mini-val mv-green">{{ $activeCategories ?? 0 }}</span>
                </div>
                <div class="mini-row">
                    <span class="mini-label">Inactive Categories</span>
                    <span class="mini-val mv-sky">{{ $inactiveCategories ?? 0 }}</span>
                </div>

                <div class="sec-divider">Messages</div>
                <div class="mini-row">
                    <span class="mini-label">Unread</span>
                    <span class="mini-val mv-rose">{{ $unreadContacts ?? 0 }}</span>
                </div>
                <div class="mini-row">
                    <span class="mini-label">Replied</span>
                    <span class="mini-val mv-green">{{ $repliedContacts ?? 0 }}</span>
                </div>
            </div>

            {{-- Content Health Bars --}}
            <div class="panel">
                <div class="panel-header">
                    <h3 class="panel-title"><i class="fas fa-layer-group" style="color:var(--grill-amber-dark);"></i>
                        Content Health</h3>
                </div>
                <div class="prog-wrap">
                    @php
                        $tP = $totalProducts ?? 0;
                        $aP = $activeProducts ?? 0;
                        $iP = $inactiveProducts ?? 0;
                        $tC = $totalCategories ?? 0;
                        $aC = $activeCategories ?? 0;
                    @endphp

                    <div class="prog-row">
                        <div class="prog-meta">
                            <span>Active Menu Items</span>
                            <span
                                style="font-weight:700;color:#10b981;">{{ $tP > 0 ? round(($aP / $tP) * 100) : 0 }}%</span>
                        </div>
                        <div class="prog-bg">
                            <div class="prog-fill pf-emerald"
                                style="width:{{ $tP > 0 ? round(($aP / $tP) * 100) : 0 }}%;"></div>
                        </div>
                    </div>

                    <div class="prog-row">
                        <div class="prog-meta">
                            <span>Inactive Menu Items</span>
                            <span
                                style="font-weight:700;color:var(--grill-crimson);">{{ $tP > 0 ? round(($iP / $tP) * 100) : 0 }}%</span>
                        </div>
                        <div class="prog-bg">
                            <div class="prog-fill pf-red" style="width:{{ $tP > 0 ? round(($iP / $tP) * 100) : 0 }}%;">
                            </div>
                        </div>
                    </div>

                    <div class="prog-row">
                        <div class="prog-meta">
                            <span>Active Categories</span>
                            <span
                                style="font-weight:700;color:var(--grill-amber-dark);">{{ $tC > 0 ? round(($aC / $tC) * 100) : 0 }}%</span>
                        </div>
                        <div class="prog-bg">
                            <div class="prog-fill pf-amber" style="width:{{ $tC > 0 ? round(($aC / $tC) * 100) : 0 }}%;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Site Settings --}}
            <div class="panel">
                <div class="panel-header">
                    <h3 class="panel-title"><i class="fas fa-cogs ico-def"></i> Site Settings</h3>
                </div>
                <div class="settings-grid">
                    @foreach ([['route' => 'admin.settings.general', 'icon' => 'fas fa-sliders-h', 'label' => 'General'], ['route' => 'admin.settings.social', 'icon' => 'fas fa-share-alt', 'label' => 'Social'], ['route' => 'admin.settings.seo', 'icon' => 'fas fa-search', 'label' => 'SEO'], ['route' => 'admin.settings.scripts', 'icon' => 'fas fa-code', 'label' => 'Scripts'], ['route' => 'admin.settings.footer', 'icon' => 'fas fa-shoe-prints', 'label' => 'Footer']] as $s)
                        <a href="{{ route($s['route']) }}" class="settings-btn">
                            <i class="{{ $s['icon'] }}"></i>{{ $s['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

        </div>
        {{-- end right --}}

    </div>
    {{-- end main grid --}}

@endsection
