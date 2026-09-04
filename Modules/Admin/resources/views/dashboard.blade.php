@extends('admin::layouts.app')
@section('page_title', 'Dashboard')
@section('page_subtitle', "Welcome back! Here's what's happening with your site.")

@section('extra_css')
    <style>
        .stat-card {
            background: #fff;
            border-radius: 18px;
            border: 1px solid var(--aa-border);
            box-shadow: var(--aa-shadow);
            padding: 18px 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .stat-top {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .stat-icon-box {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .si-violet {
            background: var(--aa-primary-light);
            color: var(--aa-primary);
        }

        .si-green {
            background: var(--aa-green-bg);
            color: var(--aa-green);
        }

        .si-blue {
            background: var(--aa-blue-bg);
            color: var(--aa-blue);
        }

        .si-amber {
            background: var(--aa-amber-bg);
            color: #b45309;
        }

        .stat-label {
            font-size: .8rem;
            color: var(--aa-text-muted);
            font-weight: 600;
        }

        .stat-value {
            font-size: 1.7rem;
            font-weight: 800;
            color: var(--aa-text);
            line-height: 1;
            margin-top: 2px;
        }

        .stat-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-delta {
            font-size: .74rem;
            color: var(--aa-text-muted);
        }

        .stat-delta b {
            color: var(--aa-text);
        }

        .stat-sparkline {
            width: 90px;
            height: 32px;
        }

        .dash-row {
            display: grid;
            grid-template-columns: 1.6fr 1fr 1fr;
            gap: 18px;
            margin-top: 18px;
        }

        .dash-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 18px;
            margin-top: 18px;
        }

        @media (max-width: 1200px) {

            .dash-row,
            .dash-row-2 {
                grid-template-columns: 1fr;
            }
        }

        .panel {
            background: #fff;
            border-radius: 18px;
            border: 1px solid var(--aa-border);
            box-shadow: var(--aa-shadow);
            overflow: hidden;
        }

        .panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 20px;
            border-bottom: 1px solid var(--aa-border);
        }

        .panel-head h3 {
            font-size: .92rem;
            font-weight: 800;
            color: var(--aa-text);
            margin: 0;
        }

        .panel-body {
            padding: 18px 20px;
        }

        .select-mini {
            font-size: .74rem;
            font-weight: 600;
            border: 1px solid var(--aa-border);
            border-radius: 8px;
            padding: 5px 10px;
            background: #fff;
            color: var(--aa-text-muted);
        }

        .mini-stat-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 11px 20px;
            border-bottom: 1px solid var(--aa-border);
            font-size: .82rem;
        }

        .mini-stat-row:last-child {
            border-bottom: none;
        }

        .mini-stat-row .label {
            display: flex;
            align-items: center;
            gap: 9px;
            color: var(--aa-text);
            font-weight: 600;
        }

        .mini-stat-row .val {
            font-weight: 800;
            color: var(--aa-text);
        }

        .mini-icon {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .75rem;
        }

        .view-all-btn {
            display: block;
            text-align: center;
            margin: 14px 20px 18px;
            padding: 9px;
            border-radius: 10px;
            border: 1px solid var(--aa-border);
            color: var(--aa-primary);
            font-weight: 700;
            font-size: .78rem;
            text-decoration: none;
        }

        .view-all-btn:hover {
            background: var(--aa-primary-light);
            text-decoration: none;
            color: var(--aa-primary);
        }

        .post-row {
            display: flex;
            gap: 12px;
            align-items: center;
            padding: 12px 20px;
            border-bottom: 1px solid var(--aa-border);
        }

        .post-row:last-child {
            border-bottom: none;
        }

        .post-thumb {
            width: 46px;
            height: 46px;
            border-radius: 10px;
            object-fit: cover;
            flex-shrink: 0;
            background: #f1f1f5;
        }

        .post-title {
            font-size: .82rem;
            font-weight: 700;
            color: var(--aa-text);
            margin-bottom: 2px;
        }

        .post-meta {
            font-size: .7rem;
            color: var(--aa-text-muted);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .top-content-row {
            padding: 12px 20px;
            border-bottom: 1px solid var(--aa-border);
        }

        .top-content-row:last-child {
            border-bottom: none;
        }

        .top-content-head {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }

        .top-content-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .74rem;
            flex-shrink: 0;
        }

        .top-content-title {
            font-size: .8rem;
            font-weight: 700;
            color: var(--aa-text);
            flex: 1;
        }

        .top-content-views {
            font-size: .74rem;
            font-weight: 700;
            color: var(--aa-text-muted);
        }

        .top-prog-bg {
            height: 5px;
            background: #f1f1f5;
            border-radius: 99px;
            overflow: hidden;
            margin-left: 40px;
        }

        .top-prog-fill {
            height: 100%;
            border-radius: 99px;
            background: var(--aa-primary);
        }

        .quick-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            padding: 18px 20px;
        }

        .quick-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            padding: 20px 10px;
            border-radius: 14px;
            text-decoration: none;
            font-size: .78rem;
            font-weight: 700;
            transition: transform .15s;
        }

        .quick-btn:hover {
            transform: translateY(-2px);
            text-decoration: none;
        }

        .quick-btn i {
            font-size: 1.3rem;
        }

        .qb-violet {
            background: var(--aa-primary-light);
            color: var(--aa-primary);
        }

        .qb-green {
            background: var(--aa-green-bg);
            color: var(--aa-green);
        }

        .qb-blue {
            background: var(--aa-blue-bg);
            color: var(--aa-blue);
        }

        .qb-amber {
            background: var(--aa-amber-bg);
            color: #b45309;
        }
    </style>
@endsection

@section('admin_content')

    @php
        // Fallback demo data — replace by passing real values from DashboardController
        $totalPosts = $totalPosts ?? \Modules\Common\Entities\Post::count();
        $publishedPosts = $publishedPosts ?? \Modules\Common\Entities\Post::where('status', 'published')->count();
        $totalApplications = $totalApplications ?? \Modules\Common\Entities\Application::count();
        $newApplicationsThisMonth =
            $newApplicationsThisMonth ??
            \Modules\Common\Entities\Application::whereMonth('created_at', now()->month)->count();
        $totalContacts = $totalContacts ?? \Modules\Common\Entities\Contact::whereNull('model')->count();
        $unreadContacts =
            $unreadContacts ?? \Modules\Common\Entities\Contact::whereNull('model')->where('status', 'unread')->count();
        $totalSiteViews = $totalSiteViews ?? 0;
        $siteViewsThisMonth = $siteViewsThisMonth ?? 0;

        $sparkPosts = $sparkPosts ?? [3, 5, 4, 6, 5, 7, 6];
        $sparkApplications = $sparkApplications ?? [2, 4, 3, 6, 5, 8, 7];
        $sparkContacts = $sparkContacts ?? [4, 3, 5, 3, 6, 4, 5];
        $sparkViews = $sparkViews ?? [200, 260, 230, 290, 270, 310, 312];

        $chartLabels = $chartLabels ?? ['1 Aug', '8 Aug', '15 Aug', '22 Aug', '29 Aug'];
        $chartPosts = $chartPosts ?? [2, 5, 8, 6, 9];
        $chartPages = $chartPages ?? [1, 4, 6, 5, 6];
        $chartEvents = $chartEvents ?? [1, 2, 1, 3, 2];
        $chartGalleries = $chartGalleries ?? [1, 3, 5, 4, 3];

        $applicationBreakdown = $applicationBreakdown ?? [
            'New' => 8,
            'In Review' => 10,
            'Shortlisted' => 6,
            'Contacted' => 4,
        ];

        $recentPosts = $recentPosts ?? \Modules\Common\Entities\Post::latest()->take(3)->get();
        $recentContacts =
            $recentContacts ?? \Modules\Common\Entities\Contact::whereNull('model')->latest()->take(3)->get();

        $topContent = $topContent ?? collect();
    @endphp

    {{-- ── STAT CARDS ── --}}
    <div class="stat-grid" style="display:grid; grid-template-columns:repeat(4,1fr); gap:14px;">

        <a href="{{ route('admin.posts.index') }}" class="stat-card" style="text-decoration:none; color:inherit;">
            <div class="stat-top">
                <div class="stat-icon-box si-violet"><i class="fas fa-file-alt"></i></div>
                <div>
                    <div class="stat-label">Total Posts</div>
                    <div class="stat-value">{{ $totalPosts }}</div>
                </div>
            </div>
            <div class="stat-bottom">
                <span class="stat-delta">Published: <b>{{ $publishedPosts }}</b></span>
                <canvas class="spark-canvas stat-sparkline" data-points="{{ implode(',', $sparkPosts) }}"
                    data-color="#6d5df6"></canvas>
            </div>
        </a>

        <a href="{{ route('admin.applications.index') }}" class="stat-card" style="text-decoration:none; color:inherit;">
            <div class="stat-top">
                <div class="stat-icon-box si-green"><i class="fas fa-user-graduate"></i></div>
                <div>
                    <div class="stat-label">Applied Students</div>
                    <div class="stat-value">{{ $totalApplications }}</div>
                </div>
            </div>
            <div class="stat-bottom">
                <span class="stat-delta">New this month: <b>{{ $newApplicationsThisMonth }}</b></span>
                <canvas class="spark-canvas stat-sparkline" data-points="{{ implode(',', $sparkApplications) }}"
                    data-color="#10b981"></canvas>
            </div>
        </a>

        <a href="{{ route('admin.contacts.index') }}" class="stat-card" style="text-decoration:none; color:inherit;">
            <div class="stat-top">
                <div class="stat-icon-box si-blue"><i class="fas fa-envelope"></i></div>
                <div>
                    <div class="stat-label">Contact Messages</div>
                    <div class="stat-value">{{ $totalContacts }}</div>
                </div>
            </div>
            <div class="stat-bottom">
                <span class="stat-delta">Unread: <b>{{ $unreadContacts }}</b></span>
                <canvas class="spark-canvas stat-sparkline" data-points="{{ implode(',', $sparkContacts) }}"
                    data-color="#3b82f6"></canvas>
            </div>
        </a>

        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon-box si-amber"><i class="fas fa-eye"></i></div>
                <div>
                    <div class="stat-label">Site Views</div>
                    <div class="stat-value">{{ $totalSiteViews }}</div>
                </div>
            </div>
            <div class="stat-bottom">
                <span class="stat-delta">This month: <b>{{ $siteViewsThisMonth }}</b></span>
                <canvas class="spark-canvas stat-sparkline" data-points="{{ implode(',', $sparkViews) }}"
                    data-color="#f59e0b"></canvas>
            </div>
        </div>
    </div>

    {{-- ── CHARTS ROW ── --}}
    <div class="dash-row">

        <div class="panel">
            <div class="panel-head">
                <h3><i class="fas fa-chart-line mr-2" style="color:var(--aa-primary);"></i>Content Overview</h3>
                <select class="select-mini">
                    <option>This Month</option>
                </select>
            </div>
            <div class="panel-body">
                <canvas id="contentOverviewChart" height="90"></canvas>
            </div>
        </div>

        <div class="panel">
            <div class="panel-head">
                <h3>Applied Students <span class="text-muted" style="font-weight:500;">(This Month)</span></h3>
            </div>
            <div class="panel-body" style="text-align:center;">
                <canvas id="applicationsDonutChart" height="180"></canvas>
                <div style="text-align:left; margin-top:14px;">
                    @php
                        $donutColors = ['#8b5cf6', '#10b981', '#f59e0b', '#3b82f6'];
                        $donutTotal = array_sum($applicationBreakdown);
                    @endphp
                    @foreach ($applicationBreakdown as $label => $count)
                        <div
                            style="display:flex; align-items:center; justify-content:space-between; padding:4px 0; font-size:.78rem;">
                            <span style="display:flex; align-items:center; gap:8px;">
                                <span
                                    style="width:8px;height:8px;border-radius:50%;background:{{ $donutColors[$loop->index % 4] }};display:inline-block;"></span>
                                {{ $label }}
                            </span>
                            <span style="font-weight:700;">{{ $count }}
                                ({{ $donutTotal > 0 ? round(($count / $donutTotal) * 100, 1) : 0 }}%)</span>
                        </div>
                    @endforeach
                </div>
            </div>
            <a href="{{ route('admin.applications.index') }}" class="view-all-btn">View All Applications</a>
        </div>

        <div class="panel">
            <div class="panel-head">
                <h3>Contact Messages</h3>
            </div>
            <div class="mini-stat-row">
                <span class="label"><span class="mini-icon" style="background:var(--aa-blue-bg);color:var(--aa-blue);"><i
                            class="fas fa-envelope"></i></span>New messages</span>
                <span class="val">{{ $unreadContacts }}</span>
            </div>
            <div class="mini-stat-row">
                <span class="label"><span class="mini-icon" style="background:var(--aa-amber-bg);color:#b45309;"><i
                            class="fas fa-clock"></i></span>In Progress</span>
                <span
                    class="val">{{ \Modules\Common\Entities\Contact::whereNull('model')->where('status', 'read')->count() }}</span>
            </div>
            <div class="mini-stat-row">
                <span class="label"><span class="mini-icon" style="background:var(--aa-green-bg);color:var(--aa-green);"><i
                            class="fas fa-check-circle"></i></span>Replied</span>
                <span
                    class="val">{{ \Modules\Common\Entities\Contact::whereNull('model')->where('status', 'replied')->count() }}</span>
            </div>
            <a href="{{ route('admin.contacts.index') }}" class="view-all-btn">View All Messages</a>
        </div>
    </div>

    {{-- ── BOTTOM ROW ── --}}
    <div class="dash-row-2">

        <div class="panel">
            <div class="panel-head">
                <h3>Recent Posts</h3>
                <a href="{{ route('admin.posts.index') }}"
                    style="font-size:.78rem; font-weight:700; color:var(--aa-primary); text-decoration:none;">View All</a>
            </div>
            @forelse ($recentPosts as $post)
                <div class="post-row">
                    <img src="{{ $post->image ?? null ? Storage::url($post->image) : asset('vendor/adminlte/dist/img/AdminLTELogo.png') }}"
                        class="post-thumb" alt="">
                    <div style="flex:1; min-width:0;">
                        <div class="post-title">{{ $post->title }}</div>
                        <div class="post-meta">
                            <span>{{ ucfirst($post->status ?? 'draft') }} &bull;
                                {{ optional($post->created_at)->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="post-row" style="justify-content:center; color:var(--aa-text-muted); font-size:.8rem;">No
                    posts yet.</div>
            @endforelse
        </div>

        <div class="panel">
            <div class="panel-head">
                <h3>Top Performing Content</h3>
                <select class="select-mini">
                    <option>By Views</option>
                </select>
            </div>
            @forelse ($topContent as $content)
                <div class="top-content-row">
                    <div class="top-content-head">
                        <div class="top-content-icon"
                            style="background:{{ $content['bg'] ?? 'var(--aa-primary-light)' }}; color:{{ $content['color'] ?? 'var(--aa-primary)' }};">
                            <i class="{{ $content['icon'] ?? 'fas fa-file' }}"></i>
                        </div>
                        <div class="top-content-title">{{ $content['title'] }}</div>
                        <div class="top-content-views">{{ $content['views'] }} views</div>
                    </div>
                    <div class="top-prog-bg">
                        <div class="top-prog-fill"
                            style="width:{{ $content['pct'] ?? 50 }}%; background:{{ $content['color'] ?? 'var(--aa-primary)' }};">
                        </div>
                    </div>
                </div>
            @empty
                <div class="top-content-row"
                    style="text-align:center; color:var(--aa-text-muted); font-size:.8rem; border:none;">
                    No view data yet.
                </div>
            @endforelse
        </div>

        <div class="panel">
            <div class="panel-head">
                <h3>Quick Actions</h3>
            </div>
            <div class="quick-grid">
                <a href="{{ route('admin.posts.create') }}" class="quick-btn qb-violet">
                    <i class="fas fa-pen"></i> Add New Post
                </a>
                <a href="{{ route('admin.events.create') }}" class="quick-btn qb-green">
                    <i class="fas fa-calendar-plus"></i> Add Event
                </a>
                <a href="{{ route('admin.gallery.create') }}" class="quick-btn qb-blue">
                    <i class="fas fa-images"></i> Add Gallery
                </a>
                <a href="{{ route('admin.applications.index') }}" class="quick-btn qb-amber">
                    <i class="fas fa-user-graduate"></i> View Applications
                </a>
            </div>
        </div>
    </div>

@endsection

@section('extra_js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // ── Sparklines on stat cards ──
        document.querySelectorAll('.spark-canvas').forEach(function(canvas) {
            const points = canvas.dataset.points.split(',').map(Number);
            const color = canvas.dataset.color;
            new Chart(canvas, {
                type: 'line',
                data: {
                    labels: points.map((_, i) => i),
                    datasets: [{
                        data: points,
                        borderColor: color,
                        borderWidth: 2,
                        pointRadius: 0,
                        tension: .4,
                        fill: false,
                    }]
                },
                options: {
                    responsive: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: false
                        }
                    },
                    scales: {
                        x: {
                            display: false
                        },
                        y: {
                            display: false
                        }
                    },
                    elements: {
                        line: {
                            borderJoinStyle: 'round'
                        }
                    },
                }
            });
        });

        // ── Content Overview line chart ──
        new Chart(document.getElementById('contentOverviewChart'), {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                        label: 'Posts',
                        data: @json($chartPosts),
                        borderColor: '#8b5cf6',
                        backgroundColor: 'rgba(139,92,246,.08)',
                        tension: .4,
                        fill: true
                    },
                    {
                        label: 'Pages',
                        data: @json($chartPages),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16,185,129,.08)',
                        tension: .4,
                        fill: true
                    },
                    {
                        label: 'Events',
                        data: @json($chartEvents),
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245,158,11,.08)',
                        tension: .4,
                        fill: true
                    },
                    {
                        label: 'Galleries',
                        data: @json($chartGalleries),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59,130,246,.08)',
                        tension: .4,
                        fill: true
                    },
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'start',
                        labels: {
                            boxWidth: 8,
                            usePointStyle: true,
                            font: {
                                size: 11
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f1f5'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    },
                },
            }
        });

        // ── Applications donut ──
        new Chart(document.getElementById('applicationsDonutChart'), {
            type: 'doughnut',
            data: {
                labels: @json(array_keys($applicationBreakdown)),
                datasets: [{
                    data: @json(array_values($applicationBreakdown)),
                    backgroundColor: ['#8b5cf6', '#10b981', '#f59e0b', '#3b82f6'],
                    borderWidth: 0,
                }]
            },
            options: {
                cutout: '72%',
                plugins: {
                    legend: {
                        display: false
                    }
                },
            },
            plugins: [{
                id: 'centerText',
                afterDraw(chart) {
                    const {
                        ctx,
                        chartArea: {
                            width,
                            height,
                            top,
                            left
                        }
                    } = chart;
                    ctx.save();
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.font = '800 22px Inter';
                    ctx.fillStyle = '#1f2333';
                    ctx.fillText('{{ $donutTotal }}', left + width / 2, top + height / 2 - 8);
                    ctx.font = '600 11px Inter';
                    ctx.fillStyle = '#8890a4';
                    ctx.fillText('Total', left + width / 2, top + height / 2 + 12);
                    ctx.restore();
                }
            }]
        });
    </script>
@endsection
