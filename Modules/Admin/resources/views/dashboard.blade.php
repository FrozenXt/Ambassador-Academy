@extends('admin::layouts.app')
@section('page_title', 'Dashboard')

@section('extra_css')
    <style>
        /* ── BASE ── */
        body,
        .wrapper,
        .content-wrapper {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
            background: #f0f2f7 !important;
        }

        /* ── WELCOME BANNER ── */
        .welcome-banner {
            background: #1c1410;
            border-radius: 16px;
            padding: 26px 30px;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            position: relative;
            overflow: hidden;
        }

        .welcome-banner::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: rgba(217, 119, 6, .18);
            border-radius: 50%;
        }

        .welcome-banner::after {
            content: '';
            position: absolute;
            bottom: -30px;
            right: 140px;
            width: 100px;
            height: 100px;
            background: rgba(190, 18, 60, .12);
            border-radius: 50%;
        }

        .welcome-banner h4 {
            font-size: 1.2rem;
            font-weight: 700;
            color: #fff;
            margin: 0 0 3px;
        }

        .welcome-banner .sub {
            font-size: .76rem;
            color: rgba(255, 255, 255, .45);
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
            padding: 7px 16px;
            border-radius: 8px;
            font-size: .75rem;
            font-weight: 600;
            text-decoration: none;
            transition: all .15s;
            border: 1.5px solid transparent;
        }

        .banner-btn-primary {
            background: #d97706;
            color: #fff;
            border-color: #d97706;
        }

        .banner-btn-primary:hover {
            background: #b45309;
            color: #fff;
            text-decoration: none;
        }

        .banner-btn-ghost {
            background: rgba(255, 255, 255, .07);
            color: rgba(255, 255, 255, .8);
            border-color: rgba(255, 255, 255, .12);
        }

        .banner-btn-ghost:hover {
            background: rgba(255, 255, 255, .13);
            color: #fff;
            text-decoration: none;
        }

        /* ── STAT GRID ── */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 22px;
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
            background: #fff;
            border-radius: 14px;
            padding: 18px 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            border: 1px solid #e9ebf0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .04);
            text-decoration: none;
            color: inherit;
            transition: transform .16s, box-shadow .16s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 18px rgba(0, 0, 0, .08);
            text-decoration: none;
            color: inherit;
        }

        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
        }

        .si-amber {
            background: #fef3c7;
            color: #b45309;
        }

        .si-emerald {
            background: #d1fae5;
            color: #047857;
        }

        .si-rose {
            background: #ffe4e6;
            color: #be123c;
        }

        .si-sky {
            background: #e0f2fe;
            color: #0369a1;
        }

        .stat-label {
            font-size: .63rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: #9ca3af;
            margin-top: 2px;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: #111827;
            line-height: 1;
        }

        .stat-sub {
            font-size: .67rem;
            color: #6b7280;
            margin-top: 1px;
        }

        /* ── MAIN LAYOUT ── */
        .dash-grid {
            display: grid;
            grid-template-columns: 1fr 316px;
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
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e9ebf0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .04);
            overflow: hidden;
            margin-bottom: 18px;
        }

        .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            border-bottom: 1px solid #f3f4f6;
        }

        .panel-title {
            font-size: .84rem;
            font-weight: 700;
            color: #111827;
            display: flex;
            align-items: center;
            gap: 7px;
            margin: 0;
        }

        .panel-title .ico-amber {
            color: #b45309;
        }

        .panel-title .ico-rose {
            color: #be123c;
        }

        .panel-title .ico-emerald {
            color: #047857;
        }

        .panel-title .ico-def {
            color: #d97706;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            padding: 2px 9px;
            border-radius: 99px;
            font-size: .64rem;
            font-weight: 700;
        }

        .pill-red {
            background: #ffe4e6;
            color: #be123c;
        }

        .pill-amber {
            background: #fef3c7;
            color: #b45309;
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
            font-size: .7rem;
            font-weight: 600;
            padding: 5px 13px;
            border-radius: 7px;
            border: 1.5px solid #e5e7eb;
            background: #f9fafb;
            color: #374151;
            text-decoration: none;
            transition: all .14s;
            white-space: nowrap;
        }

        .btn-sm-action:hover {
            border-color: #d97706;
            color: #d97706;
            background: #fffbeb;
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
            border-radius: 10px;
            overflow: hidden;
            background: #f9fafb;
            border: 1px solid #f0f0f5;
            text-decoration: none;
            color: inherit;
            display: block;
            transition: transform .16s, box-shadow .16s;
        }

        .food-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 14px rgba(0, 0, 0, .08);
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
            color: #d1d5db;
            font-size: 1.3rem;
        }

        .food-status {
            position: absolute;
            top: 6px;
            right: 6px;
            font-size: .58rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 99px;
        }

        .food-info {
            padding: 8px 9px;
        }

        .food-name {
            font-size: .7rem;
            font-weight: 700;
            color: #111827;
            line-height: 1.25;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            margin-bottom: 3px;
        }

        .food-price {
            font-size: .68rem;
            font-weight: 700;
            color: #b45309;
        }

        /* ── CATEGORY ROWS ── */
        .cat-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 20px;
            border-bottom: 1px solid #f3f4f6;
            transition: background .12s;
            text-decoration: none;
            color: inherit;
        }

        .cat-row:last-child {
            border-bottom: none;
        }

        .cat-row:hover {
            background: #fffbeb;
            text-decoration: none;
            color: inherit;
        }

        .cat-icon {
            width: 42px;
            height: 42px;
            border-radius: 9px;
            flex-shrink: 0;
            background: #fef3c7;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #b45309;
            font-size: .95rem;
        }

        .cat-name {
            font-size: .8rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 2px;
        }

        .cat-meta {
            font-size: .67rem;
            color: #9ca3af;
        }

        .cat-count-badge {
            margin-left: auto;
            font-size: .67rem;
            font-weight: 700;
            color: #b45309;
            background: #fef3c7;
            padding: 3px 10px;
            border-radius: 99px;
            flex-shrink: 0;
        }

        /* ── MESSAGES TABLE ── */
        .msg-table {
            width: 100%;
            border-collapse: collapse;
        }

        .msg-table thead th {
            font-size: .62rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .7px;
            color: #6b7280;
            padding: 9px 14px;
            background: #f9fafb;
            border-bottom: 1px solid #f0f0f5;
            white-space: nowrap;
        }

        .msg-table tbody td {
            padding: 10px 14px;
            font-size: .79rem;
            color: #374151;
            border-bottom: 1px solid #f9fafb;
            vertical-align: middle;
        }

        .msg-table tbody tr:last-child td {
            border-bottom: none;
        }

        .msg-table tbody tr:hover td {
            background: #fffbeb;
        }

        .msg-table tbody tr.is-unread td {
            background: #fefce8;
        }

        .msg-table tbody tr.is-unread:hover td {
            background: #fef9c3;
        }

        .avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #fef3c7;
            color: #b45309;
            font-size: .6rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .btn-view {
            font-size: .64rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 6px;
            border: 1.5px solid #e5e7eb;
            color: #d97706;
            text-decoration: none;
            transition: all .13s;
        }

        .btn-view:hover {
            background: #d97706;
            color: #fff;
            border-color: #d97706;
            text-decoration: none;
        }

        /* ── RIGHT SIDEBAR ── */
        .sec-divider {
            font-size: .6rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #9ca3af;
            padding: 10px 20px 6px;
            border-bottom: 1px solid #f3f4f6;
            background: #fafafa;
        }

        .mini-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 20px;
            border-bottom: 1px solid #f3f4f6;
            font-size: .77rem;
        }

        .mini-row:last-child {
            border-bottom: none;
        }

        .mini-label {
            color: #374151;
            font-weight: 500;
        }

        .mini-val {
            font-size: .7rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 99px;
        }

        .mv-green {
            background: #d1fae5;
            color: #047857;
        }

        .mv-amber {
            background: #fef3c7;
            color: #b45309;
        }

        .mv-sky {
            background: #e0f2fe;
            color: #0369a1;
        }

        .mv-rose {
            background: #ffe4e6;
            color: #be123c;
        }

        /* ── PROGRESS BARS ── */
        .prog-wrap {
            padding: 14px 20px 16px;
        }

        .prog-row {
            margin-bottom: 13px;
        }

        .prog-row:last-child {
            margin-bottom: 0;
        }

        .prog-meta {
            display: flex;
            justify-content: space-between;
            font-size: .71rem;
            color: #374151;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .prog-bg {
            height: 5px;
            background: #f3f4f6;
            border-radius: 99px;
            overflow: hidden;
        }

        .prog-fill {
            height: 100%;
            border-radius: 99px;
            transition: width .5s;
        }

        .pf-amber {
            background: #d97706;
        }

        .pf-emerald {
            background: #10b981;
        }

        .pf-red {
            background: #ef4444;
        }

        /* ── COUNTER CELLS ── */
        .counter-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1px;
            background: #f0f2f7;
        }

        .counter-cell {
            background: #fff;
            padding: 14px 10px;
            text-align: center;
        }

        .counter-num {
            font-size: 1.5rem;
            font-weight: 800;
            color: #111827;
            line-height: 1;
            margin-bottom: 3px;
        }

        .counter-lbl {
            font-size: .61rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: #9ca3af;
        }

        /* ── STOCK ── */
        .stock-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 9px 20px;
            border-bottom: 1px solid #f3f4f6;
            font-size: .77rem;
        }

        .stock-item:last-child {
            border-bottom: none;
        }

        .stock-name {
            color: #111827;
            font-weight: 500;
        }

        .stock-out {
            background: #ffe4e6;
            color: #be123c;
            font-size: .62rem;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 99px;
        }

        .stock-low {
            background: #fef3c7;
            color: #b45309;
            font-size: .62rem;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 99px;
        }

        /* ── SETTINGS ── */
        .settings-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 7px;
            padding: 14px;
        }

        .settings-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            padding: 12px 6px;
            border-radius: 9px;
            border: 1.5px solid #e5e7eb;
            background: #f9fafb;
            color: #374151;
            font-size: .66rem;
            font-weight: 600;
            text-decoration: none;
            transition: all .14s;
        }

        .settings-btn i {
            font-size: .95rem;
        }

        .settings-btn:hover {
            border-color: #d97706;
            color: #d97706;
            background: #fffbeb;
            text-decoration: none;
            transform: translateY(-1px);
        }

        /* ── EMPTY STATE ── */
        .empty-box {
            padding: 26px 16px;
            text-align: center;
            color: #9ca3af;
            font-size: .77rem;
        }

        .empty-box i {
            font-size: 1.3rem;
            display: block;
            margin-bottom: 6px;
            color: #d1d5db;
        }

        /* ── PANEL FOOTER ── */
        .panel-foot {
            padding: 10px 14px;
            text-align: right;
            border-top: 1px solid #f3f4f6;
        }
    </style>
@endsection

@section('admin_content')

    {{-- ── WELCOME BANNER ── --}}
    <div class="welcome-banner">
        <div style="position:relative;z-index:2;">
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
                                        <span style="{{ $contact->isUnread() ? 'font-weight:700;color:#111827;' : '' }}">
                                            {{ Str::limit($contact->display_name, 14) }}
                                        </span>
                                    </div>
                                </td>
                                <td
                                    style="{{ $contact->isUnread() ? 'font-weight:600;color:#111827;' : 'color:#6b7280;' }}">
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
                                <td style="color:#9ca3af;font-size:.71rem;">
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
                    <h3 class="panel-title"><i class="fas fa-layer-group" style="color:#d97706;"></i> Content Health</h3>
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
                                style="width:{{ $tP > 0 ? round(($aP / $tP) * 100) : 0 }}%;">
                            </div>
                        </div>
                    </div>

                    <div class="prog-row">
                        <div class="prog-meta">
                            <span>Inactive Menu Items</span>
                            <span
                                style="font-weight:700;color:#ef4444;">{{ $tP > 0 ? round(($iP / $tP) * 100) : 0 }}%</span>
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
                                style="font-weight:700;color:#d97706;">{{ $tC > 0 ? round(($aC / $tC) * 100) : 0 }}%</span>
                        </div>
                        <div class="prog-bg">
                            <div class="prog-fill pf-amber" style="width:{{ $tC > 0 ? round(($aC / $tC) * 100) : 0 }}%;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Site Views
            <div class="panel">
                <div class="panel-header">
                    <h3 class="panel-title">
                        <i class="fas fa-eye ico-amber"></i> Site Views
                    </h3>
                    <a href="{{ route('admin.counters.index') }}" class="btn-sm-action">Manage</a>
                </div>
                @if (isset($counters) && $counters->count())
                    <div class="counter-grid">
                        @foreach ($counters->take(4) as $counter)
                            <div class="counter-cell">
                                <div class="counter-num">{{ number_format($counter->number ?? ($counter->value ?? 0)) }}
                                </div>
                                <div class="counter-lbl">
                                    {{ Str::limit($counter->title ?? ($counter->label ?? 'Counter'), 16) }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-box" style="padding:18px;">
                        <i class="fas fa-eye"></i>
                        No counters yet. <a href="{{ route('admin.counters.create') }}">Add one</a>
                    </div>
                @endif
            </div> --}}

            {{-- Low Stock Alert --}}
            {{-- <div class="panel">
                <div class="panel-header">
                    <h3 class="panel-title">
                        <i class="fas fa-exclamation-triangle ico-amber"></i> Low Stock
                    </h3>
                    @php $lowCount = $lowStockProducts->count(); @endphp
                    @if ($lowCount > 0)
                        <span class="pill pill-amber">{{ $lowCount }} items</span>
                    @endif
                </div>
                @forelse($lowStockProducts as $p)
                    <div class="stock-item">
                        <span class="stock-name">{{ Str::limit($p->name, 22) }}</span>
                        @if ($p->stock == 0)
                            <span class="stock-out">Out of stock</span>
                        @else
                            <span class="stock-low">{{ $p->stock }} left</span>
                        @endif
                    </div>
                @empty
                    <div class="empty-box" style="padding:14px;">
                        <i class="fas fa-check-circle" style="color:#10b981;"></i>
                        All items stocked!
                    </div>
                @endforelse
            </div> --}}

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
