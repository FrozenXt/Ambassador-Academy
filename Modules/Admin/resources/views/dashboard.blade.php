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
            background: #111827;
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
            background: rgba(99, 102, 241, .15);
            border-radius: 50%;
        }

        .welcome-banner::after {
            content: '';
            position: absolute;
            bottom: -30px;
            right: 140px;
            width: 100px;
            height: 100px;
            background: rgba(139, 92, 246, .1);
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
            background: #6366f1;
            color: #fff;
            border-color: #6366f1;
        }

        .banner-btn-primary:hover {
            background: #4f46e5;
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
            grid-template-columns: repeat(6, 1fr);
            gap: 14px;
            margin-bottom: 22px;
        }

        @media (max-width: 1200px) {
            .stat-grid {
                grid-template-columns: repeat(3, 1fr);
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

        .si-indigo {
            background: #e0e7ff;
            color: #4338ca;
        }

        .si-violet {
            background: #ede9fe;
            color: #7c3aed;
        }

        .si-sky {
            background: #e0f2fe;
            color: #0369a1;
        }

        .si-emerald {
            background: #d1fae5;
            color: #047857;
        }

        .si-rose {
            background: #ffe4e6;
            color: #be123c;
        }

        .si-amber {
            background: #fef3c7;
            color: #b45309;
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

        .panel-title .ico-violet {
            color: #7c3aed;
        }

        .panel-title .ico-rose {
            color: #be123c;
        }

        .panel-title .ico-amber {
            color: #b45309;
        }

        .panel-title .ico-indigo {
            color: #4338ca;
        }

        .panel-title .ico-def {
            color: #6366f1;
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

        .pill-indigo {
            background: #e0e7ff;
            color: #4338ca;
        }

        .pill-violet {
            background: #ede9fe;
            color: #7c3aed;
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
            border-color: #6366f1;
            color: #6366f1;
            background: #f5f3ff;
            text-decoration: none;
        }

        /* ── GALLERY GRID ── */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
            padding: 12px;
        }

        @media (max-width: 900px) {
            .gallery-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 600px) {
            .gallery-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .g-thumb {
            border-radius: 8px;
            overflow: hidden;
            aspect-ratio: 1;
            position: relative;
            background: #f3f4f6;
            cursor: pointer;
        }

        .g-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform .22s;
        }

        .g-thumb:hover img {
            transform: scale(1.07);
        }

        .g-thumb .g-over {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, .6) 0%, transparent 50%);
            opacity: 0;
            transition: opacity .2s;
            display: flex;
            align-items: flex-end;
            padding: 6px 7px;
        }

        .g-thumb:hover .g-over {
            opacity: 1;
        }

        .g-thumb .g-cap {
            font-size: .6rem;
            color: #fff;
            font-weight: 600;
            line-height: 1.2;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .g-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #d1d5db;
            font-size: .65rem;
            gap: 3px;
        }

        .g-placeholder i {
            font-size: 1.2rem;
        }

        /* ── ALBUM ROWS ── */
        .album-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 20px;
            border-bottom: 1px solid #f3f4f6;
            transition: background .12s;
            text-decoration: none;
            color: inherit;
        }

        .album-row:last-child {
            border-bottom: none;
        }

        .album-row:hover {
            background: #fafbff;
            text-decoration: none;
            color: inherit;
        }

        .album-cover {
            width: 46px;
            height: 46px;
            border-radius: 9px;
            overflow: hidden;
            flex-shrink: 0;
            background: #ede9fe;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7c3aed;
            font-size: 1rem;
        }

        .album-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .album-name {
            font-size: .8rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 2px;
        }

        .album-meta {
            font-size: .67rem;
            color: #9ca3af;
        }

        .album-count-badge {
            margin-left: auto;
            font-size: .67rem;
            font-weight: 700;
            color: #6366f1;
            background: #e0e7ff;
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
            background: #fafbff;
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
            background: #dbeafe;
            color: #1d4ed8;
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
            color: #6366f1;
            text-decoration: none;
            transition: all .13s;
        }

        .btn-view:hover {
            background: #6366f1;
            color: #fff;
            border-color: #6366f1;
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

        .mv-blue {
            background: #dbeafe;
            color: #1e40af;
        }

        .mv-indigo {
            background: #e0e7ff;
            color: #3730a3;
        }

        .mv-violet {
            background: #ede9fe;
            color: #6d28d9;
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

        .pf-indigo {
            background: #6366f1;
        }

        .pf-violet {
            background: #8b5cf6;
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
            border-color: #6366f1;
            color: #6366f1;
            background: #f5f3ff;
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
            <h4>Welcome back, {{ session('admin_name', 'Admin') }} </h4>
            <p class="sub"><i class="far fa-calendar-alt mr-1"></i>{{ now()->format('l, d F Y') }}</p>
        </div>
        <div class="banner-actions">
            {{-- <a href="{{ route('web.home') }}" target="_blank" class="banner-btn banner-btn-ghost">
                <i class="fas fa-external-link-alt" style="font-size:.65rem;"></i> View Site
            </a> --}}
            <a href="{{ route('admin.settings.general') }}" class="banner-btn banner-btn-primary">
                <i class="fas fa-cog" style="font-size:.7rem;"></i> Settings
            </a>
        </div>
    </div>

    {{-- ── TOP STAT CARDS ── --}}
    <div class="stat-grid">

        <div class="stat-card">
            <div class="stat-icon si-violet"><i class="fas fa-book-open"></i></div>
            <div>
                <div class="stat-value">{{ $totalAlbums ?? 0 }}</div>
                <div class="stat-label">Total Albums</div>
                <div class="stat-sub">{{ $activeAlbums ?? 0 }} active</div>
            </div>
        </div>

        <a href="{{ route('admin.gallery.index') }}" class="stat-card">
            <div class="stat-icon si-indigo"><i class="fas fa-images"></i></div>
            <div>
                <div class="stat-value">{{ $totalGalleries ?? 0 }}</div>
                <div class="stat-label">Gallery Photos</div>
                <div class="stat-sub">{{ $activeGalleries ?? 0 }} active</div>
            </div>
        </a>

        <a href="{{ route('admin.blogs.index') }}" class="stat-card">
            <div class="stat-icon si-amber"><i class="fas fa-blog"></i></div>
            <div>
                <div class="stat-value">{{ $totalBlogs ?? 0 }}</div>
                <div class="stat-label">Total Blogs</div>
                <div class="stat-sub">+{{ $todayBlogs ?? 0 }} today</div>
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
            <div class="stat-icon si-sky"><i class="fas fa-sort-numeric-up"></i></div>
            <div>
                <div class="stat-value">{{ $totalCounters ?? 0 }}</div>
                <div class="stat-label">Counters</div>
                <div class="stat-sub">site statistics</div>
            </div>
        </a>

        {{-- <div class="stat-card">
            <div class="stat-icon si-emerald"><i class="fas fa-user-plus"></i></div>
            <div>
                <div class="stat-value">{{ $thisMonthUsers }}</div>
                <div class="stat-label">Monthly Signups</div>
                <div class="stat-sub">this month</div>
            </div>
        </div> --}}

    </div>

    {{-- ── MAIN GRID ── --}}
    <div class="dash-grid">

        {{-- ===== LEFT COLUMN ===== --}}
        <div>

            {{-- Recent Gallery Uploads --}}
            <div class="panel">
                <div class="panel-header">
                    <h3 class="panel-title">
                        <i class="fas fa-images ico-violet"></i> Recent Gallery Uploads
                        @if (isset($totalGalleries) && $totalGalleries > 0)
                            <span class="pill pill-violet">{{ $totalGalleries }} total</span>
                        @endif
                    </h3>
                    <div style="display:flex;gap:7px;">
                        <a href="{{ route('admin.gallery.create') }}" class="btn-sm-action">
                            <i class="fas fa-plus mr-1"></i> Upload
                        </a>
                        <a href="{{ route('admin.gallery.index') }}" class="btn-sm-action">View All</a>
                    </div>
                </div>

                @if (isset($recentGalleries) && $recentGalleries->count())
                    <div class="gallery-grid">
                        @foreach ($recentGalleries as $img)
                            <div class="g-thumb">
                                @if ($img->path)
                                    <img src="{{ $img->image_url }}" alt="{{ $img->title ?? '' }}" loading="lazy">
                                @else
                                    <div class="g-placeholder">
                                        <i class="fas fa-image"></i>
                                        <span>No Image</span>
                                    </div>
                                @endif
                                <div class="g-over">
                                    <span class="g-cap">{{ $img->title ?? 'Untitled' }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-box">
                        <i class="fas fa-images"></i>
                        No gallery photos yet.
                    </div>
                @endif

                <div class="panel-foot">
                    <a href="{{ route('admin.gallery.index') }}" class="btn-sm-action">
                        All Gallery Items &rarr;
                    </a>
                </div>
            </div>

            {{-- Recent Albums --}}
            <div class="panel">
                <div class="panel-header">
                    <h3 class="panel-title">
                        <i class="fas fa-book-open ico-violet"></i> Albums
                        <span class="pill pill-indigo">{{ $totalAlbums ?? 0 }} total</span>
                    </h3>
                    <a href="{{ route('admin.albums.create') }}" class="btn-sm-action">
                        <i class="fas fa-plus mr-1"></i> New Album
                    </a>
                </div>

                @forelse($recentAlbums ?? [] as $album)
                    <a href="{{ route('admin.albums.edit', $album->id) }}" class="album-row">
                        <div class="album-cover">
                            @if ($album->cover_image ?? ($album->image ?? null))
                                <img src="{{ asset('storage/' . ($album->cover_image ?? $album->image)) }}"
                                    alt="{{ $album->title }}">
                            @else
                                <i class="fas fa-book-open"></i>
                            @endif
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div class="album-name">{{ Str::limit($album->title, 32) }}</div>
                            <div class="album-meta">
                                {{ $album->galleries_count ?? ($album->galleries ? $album->galleries->count() : 0) }}
                                photos
                                &middot; {{ $album->created_at->format('d M Y') }}
                            </div>
                        </div>
                        <span class="album-count-badge">
                            {{ $album->galleries_count ?? ($album->galleries ? $album->galleries->count() : 0) }}
                        </span>
                    </a>
                @empty
                    <div class="empty-box">
                        <i class="fas fa-book-open"></i>
                        No albums yet. <a href="{{ route('admin.albums.create') }}">Create one</a>
                    </div>
                @endforelse

                <div class="panel-foot">
                    <a href="{{ route('admin.albums.index') }}" class="btn-sm-action">View All Albums &rarr;</a>
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
                            <tr class="{{ $contact->status == 'unread' ? 'is-unread' : '' }}">
                                <td>
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <div class="avatar">{{ strtoupper(substr($contact->name, 0, 1)) }}</div>
                                        <span
                                            style="{{ $contact->status == 'unread' ? 'font-weight:700;color:#111827;' : '' }}">
                                            {{ Str::limit($contact->name, 14) }}
                                        </span>
                                    </div>
                                </td>
                                <td
                                    style="{{ $contact->status == 'unread' ? 'font-weight:600;color:#111827;' : 'color:#6b7280;' }}">
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

                <div class="sec-divider">Gallery</div>
                <div class="mini-row">
                    <span class="mini-label">Total Photos</span>
                    <span class="mini-val mv-indigo">{{ $totalGalleries ?? 0 }}</span>
                </div>
                <div class="mini-row">
                    <span class="mini-label">Active Photos</span>
                    <span class="mini-val mv-green">{{ $activeGalleries ?? 0 }}</span>
                </div>
                <div class="mini-row">
                    <span class="mini-label">Inactive Photos</span>
                    <span class="mini-val mv-sky">{{ $inactiveGalleries ?? 0 }}</span>
                </div>

                <div class="sec-divider">Albums</div>
                <div class="mini-row">
                    <span class="mini-label">Total Albums</span>
                    <span class="mini-val mv-violet">{{ $totalAlbums ?? 0 }}</span>
                </div>
                <div class="mini-row">
                    <span class="mini-label">Active Albums</span>
                    <span class="mini-val mv-green">{{ $activeAlbums ?? 0 }}</span>
                </div>
                <div class="mini-row">
                    <span class="mini-label">Inactive Albums</span>
                    <span class="mini-val mv-sky">{{ $inactiveAlbums ?? 0 }}</span>
                </div>

                <div class="sec-divider">Blogs</div>
                <div class="mini-row">
                    <span class="mini-label">Today</span>
                    <span class="mini-val mv-green">{{ $todayBlogs ?? 0 }}</span>
                </div>
                <div class="mini-row">
                    <span class="mini-label">Active</span>
                    <span class="mini-val mv-blue">{{ $activeBlogs ?? 0 }}</span>
                </div>
                <div class="mini-row">
                    <span class="mini-label">Inactive</span>
                    <span class="mini-val mv-sky">{{ $inactiveBlogs ?? 0 }}</span>
                </div>
                <div class="mini-row">
                    <span class="mini-label">Total Blogs</span>
                    <span class="mini-val mv-indigo">{{ $totalBlogs ?? 0 }}</span>
                </div>

                <div class="sec-divider">Messages</div>
                <div class="mini-row">
                    <span class="mini-label">Unread</span>
                    <span class="mini-val mv-rose">{{ $unreadContacts ?? 0 }}</span>
                </div>
            </div>

            {{-- Content Health Bars --}}
            <div class="panel">
                <div class="panel-header">
                    <h3 class="panel-title"><i class="fas fa-layer-group" style="color:#8b5cf6;"></i> Content Health</h3>
                </div>
                <div class="prog-wrap">
                    @php
                        $tG = $totalGalleries ?? 0;
                        $aG = $activeGalleries ?? 0;
                        $tA = $totalAlbums ?? 0;
                        $aA = $activeAlbums ?? 0;
                        $tP = $totalProducts ?? 0;
                        $aP = $activeProducts ?? 0;
                        $iP = $inactiveProducts ?? 0;
                    @endphp

                    <div class="prog-row">
                        <div class="prog-meta">
                            <span>Active Photos</span>
                            <span
                                style="font-weight:700;color:#6366f1;">{{ $tG > 0 ? round(($aG / $tG) * 100) : 0 }}%</span>
                        </div>
                        <div class="prog-bg">
                            <div class="prog-fill pf-indigo"
                                style="width:{{ $tG > 0 ? round(($aG / $tG) * 100) : 0 }}%;">
                            </div>
                        </div>
                    </div>

                    <div class="prog-row">
                        <div class="prog-meta">
                            <span>Active Albums</span>
                            <span
                                style="font-weight:700;color:#8b5cf6;">{{ $tA > 0 ? round(($aA / $tA) * 100) : 0 }}%</span>
                        </div>
                        <div class="prog-bg">
                            <div class="prog-fill pf-violet"
                                style="width:{{ $tA > 0 ? round(($aA / $tA) * 100) : 0 }}%;">
                            </div>
                        </div>
                    </div>

                    <div class="prog-row">
                        <div class="prog-meta">
                            <span>Active Products</span>
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
                            <span>Inactive Products</span>
                            <span
                                style="font-weight:700;color:#ef4444;">{{ $tP > 0 ? round(($iP / $tP) * 100) : 0 }}%</span>
                        </div>
                        <div class="prog-bg">
                            <div class="prog-fill pf-red" style="width:{{ $tP > 0 ? round(($iP / $tP) * 100) : 0 }}%;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Site Counters --}}
            <div class="panel">
                <div class="panel-header">
                    <h3 class="panel-title">
                        <i class="fas fa-sort-numeric-up ico-amber"></i> Site Counters
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
                        <i class="fas fa-sort-numeric-up"></i>
                        No counters yet. <a href="{{ route('admin.counters.create') }}">Add one</a>
                    </div>
                @endif
            </div>

            {{-- Low Stock Alert --}}
            <div class="panel">
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
                        All products stocked!
                    </div>
                @endforelse
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
