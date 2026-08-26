<!DOCTYPE html>
<html lang="en">

<head>
    @php
        $siteSettings = app(\Modules\Common\Services\SiteSettingService::class);
        $faviconUrl = $siteSettings->getByKey('site_favicon')
            ? Storage::url($siteSettings->getByKey('site_favicon'))
            : asset('image/favicon.ico');
    @endphp

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel | @yield('page_title', 'Dashboard')</title>

    <!-- ── Favicon (dynamic) ── -->
    <link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
    <link rel="shortcut icon" href="{{ $faviconUrl }}">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

    <style>
        /* ── RESPONSIVE FIXES ── */

        /* Prevent any element from causing horizontal scroll on small screens */
        html,
        body {
            overflow-x: hidden;
        }

        /* Content header: stack title and actions on small screens instead of
           title-left / actions-float-right colliding */
        @media (max-width: 767px) {
            .content-header .row>.col-sm-6:first-child {
                margin-bottom: 8px;
            }

            .content-header .float-right {
                float: none !important;
            }

            .content-header h1 {
                font-size: 1.3rem;
            }

            .content-header .float-right .btn,
            .content-header .float-right a.btn {
                margin-bottom: 6px;
            }
        }

        /* Sidebar brand + user panel: prevent long site names / emails from
           overflowing and breaking the sidebar width */
        .brand-text,
        .user-panel .info span,
        .user-panel .info small {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 160px;
            display: inline-block;
            vertical-align: middle;
        }

        /* Sidebar nav badges: keep them from wrapping oddly on narrow sidebar */
        .nav-sidebar .badge.right {
            flex-shrink: 0;
        }

        /* Cards / tables: force horizontal scroll INSIDE the card instead of
           the whole page overflowing on mobile (belt-and-braces; most tables
           should already be wrapped in .table-responsive individually) */
        @media (max-width: 767px) {
            .card-body table:not(.table-responsive table) {
                display: block;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
        }

        /* Small-box / info-box stat cards: ensure comfortable tap targets and
           spacing when stacked 2-per-row on phones */
        @media (max-width: 575px) {
            .small-box .inner h3 {
                font-size: 1.6rem;
            }

            .small-box .inner p {
                font-size: .75rem;
            }

            .small-box .icon {
                font-size: 2.4rem;
            }
        }

        /* Buttons in card-tools / page_actions: wrap instead of overflowing
           off-screen on narrow viewports */
        .card-tools,
        .content-header .float-right {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            justify-content: flex-end;
        }

        /* Navbar top-right admin name: hide the long email/name text on very
           small screens, keep just the icon + logout visible */
        @media (max-width: 400px) {
            .main-header .navbar-nav .nav-link.text-muted {
                display: none;
            }
        }

        /* Footer: stack version info under copyright on small screens instead
           of float-right overlapping the text */
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

        {{-- Preloader --}}
        {{-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('vendor/adminlte/dist/img/AdminLTELogo.png') }}" alt="AdminLTE"
                height="60" width="60">
        </div> --}}

        {{-- Navbar --}}
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user-circle mr-1"></i>
                        {{ session('admin_name') }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        {{-- <a href="{{ route('admin.account.edit') }}" class="dropdown-item">
                            <i class="fas fa-user-edit mr-2 text-muted"></i> Account Settings
                        </a> --}}
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
                <li class="nav-item">
                    <form action="{{ route('admin.logout') }}" method="POST" id="admin-logout-form">
                        @csrf
                    </form>
                    <a href="#" class="nav-link text-danger"
                        onclick="document.getElementById('admin-logout-form').submit()">
                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                    </a>
                </li>
            </ul>
        </nav>

        {{-- Sidebar --}}
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="/admin/dashboard" class="brand-link">
                @php
                    $siteLogo = \Modules\Common\Entities\SiteSetting::get('site_logo');
                @endphp
                <img src="{{ $siteLogo ? asset('storage/' . $siteLogo) : asset('vendor/adminlte/dist/img/AdminLTELogo.png') }}"
                    alt="Logo" class="brand-image img-circle elevation-3"
                    style="opacity:.8; width:33px; height:33px; object-fit:cover; border-radius:50%; border:2px solid rgba(255,255,255,0.2); box-shadow:0 2px 8px rgba(0,0,0,0.3);">
                <span class="brand-text font-weight-light">
                    <b>Admin</b>Dashboard
                </span>
            </a>
            <div class="sidebar">

                {{-- User Panel --}}
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <div class="img-circle bg-primary text-white d-flex
                                align-items-center justify-content-center font-weight-bold"
                            style="width:35px;height:35px;">
                            {{ strtoupper(substr(session('admin_name', 'A'), 0, 1)) }}
                        </div>
                    </div>
                    <div class="info">
                        <span class="d-block text-white">
                            {{ session('admin_name') }}
                        </span>
                        <small class="text-white-50">
                            {{ session('admin_email') }}
                        </small>
                    </div>
                </div>

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">

                        {{-- Dashboard --}}
                        <li class="nav-item">
                            <a href="/admin/dashboard"
                                class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        @php
                            $adminUser = \Modules\Common\Entities\User::find(session('admin_id'));
                            $isSuperAdmin = $adminUser && $adminUser->hasRole('superadmin');
                        @endphp

                        @if ($isSuperAdmin)
                            <li class="nav-item">
                                <a href="{{ route('admin.users.index') }}"
                                    class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-user-shield"></i>
                                    <p>User Management
                                        @if (\Modules\Common\Entities\User::count() > 0)
                                            <span class="badge badge-info right">
                                                {{ \Modules\Common\Entities\User::count() }}
                                            </span>
                                        @endif
                                    </p>
                                </a>
                            </li>
                        @endif

                        {{-- Categories --}}
                        <li class="nav-item">
                            <a href="/admin/categories"
                                class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tags"></i>
                                <p>
                                    Categories
                                    <span class="badge badge-info right">
                                        {{ \Modules\Common\Entities\Category::count() }}
                                    </span>
                                </p>
                            </a>
                        </li>

                        {{-- Products --}}
                        <li class="nav-item">
                            <a href="/admin/products"
                                class="nav-link {{ request()->is('admin/products*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-box"></i>
                                <p>
                                    Food-Items
                                    <span class="badge badge-info right">
                                        {{ \Modules\Common\Entities\Product::count() }}
                                    </span>
                                </p>
                            </a>
                        </li>
                        <li class="nav-header">CONTENT</li>

                        <li class="nav-item">
                            <a href="{{ route('admin.posts.index') }}"
                                class="nav-link {{ request()->is('admin/posts*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>Posts
                                    @if (\Modules\Common\Entities\Post::count() > 0)
                                        <span class="badge badge-info right">
                                            {{ \Modules\Common\Entities\Post::count() }}
                                        </span>
                                    @endif
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.albums.index') }}"
                                class="nav-link {{ request()->is('admin/albums*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-images"></i>
                                <p>Albums</p>
                                <span class="badge badge-info right">
                                    {{ \Modules\Common\Entities\Album::count() }}
                                </span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.gallery.index') }}"
                                class="nav-link {{ request()->is('admin/gallery*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-images"></i>
                                <p>Gallery</p>
                                <span class="badge badge-info right">
                                    {{ \Modules\Common\Entities\Gallery::count() }}
                                </span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.counters.index') }}"
                                class="nav-link {{ request()->is('admin/counters*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-images"></i>
                                <p>Counters</p>
                                <span class="badge badge-info right">
                                    {{ \Modules\Common\Entities\Counter::count() }}
                                </span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.services.index') }}"
                                class="nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">

                                <i class="nav-icon fas fa-cogs"></i>
                                <p>Services
                                    @php $serviceCount = \Modules\Common\Entities\Service::count(); @endphp
                                    @if ($serviceCount > 0)
                                        <span class="badge badge-info right">{{ $serviceCount }}</span>
                                    @endif
                                </p>
                            </a>
                        </li>
                        {{-- Contact Messages --}}
                        <li class="nav-item">
                            <a href="{{ route('admin.contacts.index', ['source' => 'contact']) }}"
                                class="nav-link {{ request()->get('source') == 'contact' ? 'active' : '' }}">

                                <i class="nav-icon fas fa-envelope"></i>

                                <p>
                                    Contact Messages

                                    @php
                                        $contactUnread = \Modules\Common\Entities\Contact::whereNull('model')
                                            ->where('status', 'unread')
                                            ->count();
                                    @endphp

                                    @if ($contactUnread > 0)
                                        <span class="badge badge-danger right">
                                            {{ $contactUnread }}
                                        </span>
                                    @endif
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.testimonials.index') }}"
                                class="nav-link {{ request()->is('admin/testimonials*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-quote-left"></i>
                                <p>Testimonials
                                    @php $testimonialCount = \Modules\Common\Entities\Testimonial::where('is_featured', true)->count(); @endphp
                                    @if ($testimonialCount > 0)
                                        <span class="badge badge-info right">{{ $testimonialCount }}</span>
                                    @endif
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.events.index') }}"
                                class="nav-link {{ request()->is('admin/events*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-quote-left"></i>
                                <p>Events
                                    @php $eventCount = \Modules\Common\Entities\Event::where('is_featured', true)->count(); @endphp
                                    @if ($eventCount > 0)
                                        <span class="badge badge-info right">{{ $eventCount }}</span>
                                    @endif
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.blog-categories.index') }}"
                                class="nav-link {{ request()->is('admin/blog-categories*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-quote-left"></i>
                                <p>Blog Categories
                                    @php $blogCategoryCount = \Modules\Common\Entities\BlogCategory::where('status', true)->count(); @endphp
                                    @if ($blogCategoryCount > 0)
                                        <span class="badge badge-info right">{{ $blogCategoryCount }}</span>
                                    @endif
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.blogs.index') }}"
                                class="nav-link {{ request()->is('admin/blogs*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-quote-left"></i>
                                <p>Blogs
                                    @php $blogCount = \Modules\Common\Entities\Blog::where('is_featured', true)->count(); @endphp
                                    @if ($blogCount > 0)
                                        <span class="badge badge-info right">{{ $blogCount }}</span>
                                    @endif
                                </p>
                            </a>
                        </li>
                        @php
                            $adminUser = \Modules\Common\Entities\User::find(session('admin_id'));
                            $isSuperAdmin = $adminUser && $adminUser->hasRole('superadmin');
                        @endphp
                        @if ($isSuperAdmin)
                            <li class="nav-header">SETTINGS</li>

                            <li class="nav-item {{ request()->is('admin/settings*') ? 'menu-open' : '' }}">
                                <a href="#"
                                    class="nav-link {{ request()->is('admin/settings*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-cogs"></i>
                                    <p>
                                        Site Settings
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
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
                        @endif

                        @php
                            $adminUser = \Modules\Common\Entities\User::find(session('admin_id'));
                            $isSuperAdmin = $adminUser && $adminUser->hasRole('superadmin');
                        @endphp
                        @if ($isSuperAdmin)
                            <li class="nav-item">
                                <a href="{{ route('admin.email-settings.index') }}"
                                    class="nav-link {{ request()->is('admin/email-settings*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-envelope-open-text"></i>
                                    <p>Email Settings</p>
                                </a>
                            </li>
                        @endif
                        <li class="nav-header">ACCOUNT</li>
                        {{-- Logout --}}
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
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ session('error') }}
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

        {{-- Footer --}}
        <footer class="main-footer">
            <strong>© {{ date('Y') }}
                {{ \Modules\Common\Entities\SiteSetting::where('key', 'site_name')->value('value') ?? 'Admin' }}.</strong>
            All rights reserved.
            <div class="float-right">
                <b>Version</b> 1.0.0
            </div>
        </footer>

        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>
    @yield('extra_js')
</body>

</html>
