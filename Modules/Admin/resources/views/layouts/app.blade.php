<!DOCTYPE html>
<html lang="en">

<head>
    @php
        $siteSettings = app(\Modules\Common\Services\SiteSettingService::class);
        $faviconUrl = $siteSettings->getByKey('site_favicon')
            ? Storage::url($siteSettings->getByKey('site_favicon'))
            : asset('image/favicon.ico');
        $siteLogo = \Modules\Common\Entities\SiteSetting::get('site_logo');
        $siteName = $siteSettings->getByKey('site_name', 'Ambassador Academy');
    @endphp

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel | @yield('page_title', 'Dashboard')</title>

    <link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
    <link rel="shortcut icon" href="{{ $faviconUrl }}">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:400,500,600,700,800&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

    <style>
        /* =====================================================
           AMBASSADOR ACADEMY ADMIN THEME
           Re-skins the shared AdminLTE shell — every admin page
           (Contacts, Applications, Products, etc.) inherits this
           automatically since they use the same base classes.
           ===================================================== */
        :root {
            --aa-primary: #6d5df6;
            --aa-primary-dark: #5a4ae0;
            --aa-primary-light: #ede9fe;
            --aa-sidebar-from: #1e1b3a;
            --aa-sidebar-to: #2a2454;
            --aa-bg: #f5f6fb;
            --aa-panel: #ffffff;
            --aa-border: #eef0f7;
            --aa-text: #1f2333;
            --aa-text-muted: #8890a4;
            --aa-green: #10b981;
            --aa-green-bg: #d1fae5;
            --aa-blue: #3b82f6;
            --aa-blue-bg: #dbeafe;
            --aa-amber: #f59e0b;
            --aa-amber-bg: #fef3c7;
            --aa-rose: #ef4444;
            --aa-rose-bg: #fee2e2;
            --aa-radius: 16px;
            --aa-shadow: 0 1px 3px rgba(31, 35, 51, .06), 0 1px 2px rgba(31, 35, 51, .04);
            --aa-shadow-hover: 0 12px 28px rgba(31, 35, 51, .1);
        }

        html,
        body {
            overflow-x: hidden;
        }

        body,
        .wrapper,
        .content-wrapper {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
            background: var(--aa-bg) !important;
            color: var(--aa-text);
        }

        /* ── SIDEBAR ── */
        .main-sidebar {
            background: linear-gradient(180deg, var(--aa-sidebar-from) 0%, var(--aa-sidebar-to) 100%) !important;
            box-shadow: 4px 0 24px rgba(20, 16, 50, .15);
        }

        .brand-link {
            border-bottom: 1px solid rgba(255, 255, 255, .08) !important;
            padding: 18px 16px !important;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-link .brand-image {
            margin: 0 !important;
            width: 40px !important;
            height: 40px !important;
        }

        .brand-text {
            display: flex !important;
            flex-direction: column;
            line-height: 1.25;
            max-width: 160px;
        }

        .brand-text b {
            font-size: .95rem;
            font-weight: 800;
            color: #fff !important;
        }

        .brand-text small {
            font-size: .68rem;
            color: rgba(255, 255, 255, .5);
            font-weight: 500;
        }

        .user-panel {
            display: none !important;
            /* replaced by navbar avatar in this theme */
        }

        .nav-header {
            color: rgba(255, 255, 255, .35) !important;
            font-size: .64rem !important;
            font-weight: 700 !important;
            letter-spacing: .8px;
            padding: 18px 20px 8px !important;
        }

        .nav-sidebar .nav-link {
            color: rgba(255, 255, 255, .68) !important;
            border-radius: 10px !important;
            margin: 2px 10px !important;
            padding: 9px 12px !important;
            font-size: .82rem !important;
            font-weight: 500;
            transition: all .15s;
        }

        .nav-sidebar .nav-link .nav-icon {
            color: rgba(255, 255, 255, .55) !important;
            font-size: .9rem;
            margin-right: 10px;
            width: 18px;
            text-align: center;
        }

        .nav-sidebar .nav-link:hover {
            background: rgba(255, 255, 255, .06) !important;
            color: #fff !important;
        }

        .nav-sidebar .nav-link.active {
            background: var(--aa-primary) !important;
            color: #fff !important;
            box-shadow: 0 4px 14px rgba(109, 93, 246, .4);
        }

        .nav-sidebar .nav-link.active .nav-icon {
            color: #fff !important;
        }

        .nav-sidebar .badge {
            font-size: .62rem;
            font-weight: 700;
            padding: 3px 7px;
            border-radius: 99px;
        }

        .nav-sidebar .badge.badge-info {
            background: rgba(255, 255, 255, .12) !important;
            color: rgba(255, 255, 255, .85) !important;
        }

        .nav-sidebar .badge.badge-danger {
            background: var(--aa-rose) !important;
        }

        .nav-treeview .nav-link {
            padding-left: 42px !important;
            font-size: .78rem !important;
        }

        /* ── NAVBAR ── */
        .main-header.navbar {
            background: #fff !important;
            border-bottom: 1px solid var(--aa-border) !important;
            box-shadow: 0 1px 2px rgba(31, 35, 51, .03);
            padding: 6px 20px;
        }

        .main-header .nav-link {
            color: var(--aa-text) !important;
        }

        .navbar-nav .dropdown-menu {
            border-radius: 12px;
            border: 1px solid var(--aa-border);
            box-shadow: var(--aa-shadow-hover);
            margin-top: 8px;
        }

        /* ── CONTENT HEADER ── */
        .content-header {
            background: transparent;
            padding: 22px 0 4px;
        }

        .content-header h1 {
            font-size: 1.55rem;
            font-weight: 800;
            color: var(--aa-text);
        }

        /* ── CARDS (shared by every admin page) ── */
        .card {
            border-radius: var(--aa-radius) !important;
            border: 1px solid var(--aa-border) !important;
            box-shadow: var(--aa-shadow) !important;
        }

        .card-header {
            background: #fff !important;
            border-bottom: 1px solid var(--aa-border) !important;
            border-radius: var(--aa-radius) var(--aa-radius) 0 0 !important;
        }

        .card-outline.card-primary {
            border-top: 3px solid var(--aa-primary) !important;
        }

        .card-outline.card-secondary {
            border-top: 3px solid #64748b !important;
        }

        .card-outline.card-success {
            border-top: 3px solid var(--aa-green) !important;
        }

        .card-outline.card-danger {
            border-top: 3px solid var(--aa-rose) !important;
        }

        .card-outline.card-warning {
            border-top: 3px solid var(--aa-amber) !important;
        }

        .btn-primary {
            background: var(--aa-primary) !important;
            border-color: var(--aa-primary) !important;
        }

        .btn-primary:hover {
            background: var(--aa-primary-dark) !important;
            border-color: var(--aa-primary-dark) !important;
        }

        .badge-primary {
            background: var(--aa-primary) !important;
        }

        .small-box {
            border-radius: var(--aa-radius) !important;
            box-shadow: var(--aa-shadow) !important;
        }

        /* ── FOOTER ── */
        .main-footer {
            background: #fff;
            border-top: 1px solid var(--aa-border);
            color: var(--aa-text-muted);
            font-size: .78rem;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 767px) {
            .content-header .row>.col-sm-6:first-child {
                margin-bottom: 8px;
            }

            .content-header .float-right {
                float: none !important;
            }

            .content-header h1 {
                font-size: 1.25rem;
            }
        }

        .brand-text,
        .navbar-nav .nav-link .d-name {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 160px;
        }

        .card-tools,
        .content-header .float-right {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            justify-content: flex-end;
        }

        @media (max-width: 400px) {
            .main-header .navbar-nav .nav-link.text-muted {
                display: none;
            }
        }

        @media (max-width: 575px) {
            .main-footer .float-right {
                float: none !important;
                display: block;
                margin-top: 4px;
            }
        }
    </style>

    @yield('extra_css')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        {{-- Navbar --}}
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto align-items-center">
                <li class="nav-item mr-2">
                    <a class="nav-link position-relative" href="#" title="Notifications">
                        <i class="fas fa-bell"></i>
                        @php
                            $navUnread =
                                (class_exists(\Modules\Common\Entities\Contact::class)
                                    ? \Modules\Common\Entities\Contact::whereNull('model')
                                        ->where('status', 'unread')
                                        ->count()
                                    : 0) +
                                (class_exists(\Modules\Common\Entities\Application::class)
                                    ? \Modules\Common\Entities\Application::where('status', 'unread')->count()
                                    : 0);
                        @endphp
                        @if ($navUnread > 0)
                            <span class="position-absolute"
                                style="top:2px; right:0; width:8px; height:8px; border-radius:50%; background:var(--aa-rose);"></span>
                        @endif
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-toggle="dropdown"
                        role="button" aria-haspopup="true" aria-expanded="false" style="gap:9px;">
                        <div class="d-flex align-items-center justify-content-center"
                            style="width:36px;height:36px;border-radius:50%;background:var(--aa-primary-light);color:var(--aa-primary);font-weight:800;font-size:.8rem;">
                            {{ strtoupper(substr(session('admin_name', 'A'), 0, 1)) }}
                        </div>
                        <span class="d-none d-sm-flex flex-column text-left">
                            <span class="d-name"
                                style="font-size:.8rem; font-weight:700; color:var(--aa-text); line-height:1.2;">
                                {{ session('admin_name') }}
                            </span>
                            <small class="text-muted" style="font-size:.66rem;">Administrator</small>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="{{ route('admin.account.change-password') }}" class="dropdown-item">
                            <i class="fas fa-key mr-2 text-muted"></i> Change Password
                        </a>
                        <a href="{{ route('admin.account.change-email') }}" class="dropdown-item">
                            <i class="fas fa-envelope mr-2 text-muted"></i> Change Email
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item text-danger"
                            onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>

        {{-- Sidebar --}}
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="/admin/dashboard" class="brand-link">
                <img src="{{ $siteLogo ? asset('storage/' . $siteLogo) : asset('vendor/adminlte/dist/img/AdminLTELogo.png') }}"
                    alt="Logo" class="brand-image img-circle elevation-3"
                    style="width:40px; height:40px; object-fit:cover; border:2px solid rgba(255,255,255,0.15);">
                <span class="brand-text">
                    <b>{{ $siteName }}</b>
                    <small>Admin Panel</small>
                </span>
            </a>

            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">

                        <li class="nav-item">
                            <a href="/admin/dashboard"
                                class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-th-large"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        @php
                            $adminUser = \Modules\Common\Entities\User::find(session('admin_id'));
                            $isSuperAdmin = $adminUser && $adminUser->hasRole('superadmin');
                        @endphp

                        <li class="nav-header">CONTENT MANAGEMENT</li>

                        <li class="nav-item">
                            <a href="{{ route('admin.posts.index') }}"
                                class="nav-link {{ request()->is('admin/posts*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>Posts
                                    @php $postCount = \Modules\Common\Entities\Post::count(); @endphp
                                    @if ($postCount > 0)
                                        <span class="badge badge-info right">{{ $postCount }}</span>
                                    @endif
                                </p>
                            </a>
                        </li>

                        @if (Route::has('admin.pages.index'))
                            <li class="nav-item">
                                <a href="{{ route('admin.pages.index') }}"
                                    class="nav-link {{ request()->is('admin/pages*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-copy"></i>
                                    <p>Pages
                                        @php $pageCount = \Modules\Common\Entities\Page::count(); @endphp
                                        @if ($pageCount > 0)
                                            <span class="badge badge-info right">{{ $pageCount }}</span>
                                        @endif
                                    </p>
                                </a>
                            </li>
                        @endif

                        <li class="nav-item">
                            <a href="{{ route('admin.categories.index') }}"
                                class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tags"></i>
                                <p>Categories
                                    <span
                                        class="badge badge-info right">{{ \Modules\Common\Entities\Category::count() }}</span>
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.services.index') }}"
                                class="nav-link {{ request()->is('admin/services*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-concierge-bell"></i>
                                <p>Services
                                    <span
                                        class="badge badge-info right">{{ \Modules\Common\Entities\Service::count() }}</span>
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.counters.index') }}"
                                class="nav-link {{ request()->is('admin/counters*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-graduate"></i>
                                <p>Counters
                                    <span
                                        class="badge badge-info right">{{ \Modules\Common\Entities\Counter::count() }}</span>
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.media.index') }}"
                                class="nav-link {{ request()->is('admin/media*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-photo-video"></i>
                                <p>Media Library</p>
                            </a>
                        </li>

                        @if (Route::has('admin.menus.index'))
                            <li class="nav-item">
                                <a href="{{ route('admin.menus.index') }}"
                                    class="nav-link {{ request()->is('admin/menus*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-bars"></i>
                                    <p>Menus</p>
                                </a>
                            </li>
                        @endif

                        <li class="nav-item">
                            <a href="{{ route('admin.events.index') }}"
                                class="nav-link {{ request()->is('admin/events*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-calendar-alt"></i>
                                <p>Events
                                    @php $eventCount = \Modules\Common\Entities\Event::count(); @endphp
                                    @if ($eventCount > 0)
                                        <span class="badge badge-info right">{{ $eventCount }}</span>
                                    @endif
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.gallery.index') }}"
                                class="nav-link {{ request()->is('admin/gallery*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-images"></i>
                                <p>Galleries
                                    <span
                                        class="badge badge-info right">{{ \Modules\Common\Entities\Gallery::count() }}</span>
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.testimonials.index') }}"
                                class="nav-link {{ request()->is('admin/testimonials*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-quote-left"></i>
                                <p>Testimonials
                                    @php $testimonialCount = \Modules\Common\Entities\Testimonial::count(); @endphp
                                    @if ($testimonialCount > 0)
                                        <span class="badge badge-info right">{{ $testimonialCount }}</span>
                                    @endif
                                </p>
                            </a>
                        </li>

                        <li class="nav-header">OTHER MANAGEMENT</li>

                        <li class="nav-item">
                            <a href="{{ route('admin.applications.index') }}"
                                class="nav-link {{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-graduate"></i>
                                <p>Applied Students
                                    @php $applicationCount = \Modules\Common\Entities\Application::count(); @endphp
                                    @if ($applicationCount > 0)
                                        <span class="badge badge-info right">{{ $applicationCount }}</span>
                                    @endif
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.contacts.index') }}"
                                class="nav-link {{ request()->is('admin/contacts*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-envelope"></i>
                                <p>Contacts
                                    @php $contactUnread = \Modules\Common\Entities\Contact::whereNull('model')->where('status', 'unread')->count(); @endphp
                                    @if ($contactUnread > 0)
                                        <span class="badge badge-danger right">{{ $contactUnread }}</span>
                                    @endif
                                </p>
                            </a>
                        </li>

                        @if (Route::has('admin.newsletters.index'))
                            <li class="nav-item">
                                <a href="{{ route('admin.newsletters.index') }}"
                                    class="nav-link {{ request()->is('admin/newsletters*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-paper-plane"></i>
                                    <p>Newsletters</p>
                                </a>
                            </li>
                        @endif

                        @if ($isSuperAdmin)
                            <li class="nav-header">SETTINGS</li>

                            <li class="nav-item {{ request()->is('admin/settings*') ? 'menu-open' : '' }}">
                                <a href="#"
                                    class="nav-link {{ request()->is('admin/settings*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-cogs"></i>
                                    <p>Site Settings <i class="fas fa-angle-left right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.settings.general') }}"
                                            class="nav-link {{ request()->is('admin/settings/general') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>General</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.settings.social') }}"
                                            class="nav-link {{ request()->is('admin/settings/social') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Social Media</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.settings.seo') }}"
                                            class="nav-link {{ request()->is('admin/settings/seo') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>SEO</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.settings.scripts') }}"
                                            class="nav-link {{ request()->is('admin/settings/scripts') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Scripts</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.settings.footer') }}"
                                            class="nav-link {{ request()->is('admin/settings/footer') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Footer</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.users.index') }}"
                                    class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-user-shield"></i>
                                    <p>Users
                                        @php $userCount = \Modules\Common\Entities\User::count(); @endphp
                                        @if ($userCount > 0)
                                            <span class="badge badge-info right">{{ $userCount }}</span>
                                        @endif
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.email-settings.index') }}"
                                    class="nav-link {{ request()->is('admin/email-settings*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-envelope-open-text"></i>
                                    <p>Email Settings</p>
                                </a>
                            </li>
                        @endif

                        <li class="nav-item">
                            <a href="#" class="nav-link"
                                onclick="document.getElementById('admin-logout-form').submit()">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>Logout</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        {{-- Content Wrapper --}}
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2 align-items-center">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('page_title', 'Dashboard')</h1>
                            @hasSection('page_subtitle')
                                <p class="text-muted mb-0" style="font-size:.85rem;">@yield('page_subtitle')</p>
                            @endif
                        </div>
                        <div class="col-sm-6">
                            <div class="float-right">
                                @yield('page_actions')
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="container-fluid">

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-warning alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Please fix the errors below:</strong>
                            <ul class="mb-0 mt-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('admin_content')

                </div>
            </div>
        </div>

        <footer class="main-footer">
            <strong>© {{ date('Y') }} {{ $siteName }}.</strong> All rights reserved.
            <div class="float-right"><b>Version</b> 1.0.0</div>
        </footer>

        <form action="{{ route('admin.logout') }}" method="POST" id="admin-logout-form">@csrf</form>

        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    @yield('extra_js')
</body>

</html>
