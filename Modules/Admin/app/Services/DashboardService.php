<?php

namespace Modules\Admin\Services;

use Modules\Common\Entities\Product;
use Modules\Common\Entities\Category;
use Modules\Common\Entities\Contact;
use Modules\Common\Entities\Counter;
use Modules\Common\Entities\Post;
use Modules\Common\Entities\Application;
use Modules\Common\Entities\Event;
use Modules\Common\Entities\Gallery;
use Illuminate\Support\Carbon;

class DashboardService
{
    public function getDashboardStats(): array
    {
        return array_merge(
            $this->getProductStats(),
            $this->getCategoryStats(),
            $this->getContactStats(),
            $this->getCounterStats(),
            $this->getPostStats(),
            $this->getApplicationStats(),
            $this->getContentOverviewChart(),
            $this->getTopContent(),
        );
    }

    // ── Products (kept for legacy views) ──
    private function getProductStats(): array
    {
        return [
            'totalProducts'    => Product::count(),
            'activeProducts'   => Product::where('status', 'active')->count(),
            'inactiveProducts' => Product::where('status', 'inactive')->count(),

            'lowStockProducts' => Product::where('stock', '<=', 5)
                ->where('status', 'active')
                ->with('categories')
                ->latest()
                ->take(5)
                ->get(),

            'recentProducts' => Product::with('categories')
                ->latest()
                ->take(10)
                ->get(),
        ];
    }

    // ── Categories (kept for legacy views) ──
    private function getCategoryStats(): array
    {
        return [
            'totalCategories'    => Category::count(),
            'activeCategories'   => Category::where('status', 'active')->count(),
            'inactiveCategories' => Category::where('status', 'inactive')->count(),
            'categories'         => Category::withCount('products')
                ->latest()
                ->take(6)
                ->get(),
        ];
    }

    // ── Contacts ──
    private function getContactStats(): array
    {
        $baseQuery = Contact::whereNull('model');

        return [
            'totalContacts'   => (clone $baseQuery)->count(),
            'unreadContacts'  => (clone $baseQuery)->where('status', 'unread')->count(),
            'repliedContacts' => (clone $baseQuery)->where('status', 'replied')->count(),
            'recentContacts'  => (clone $baseQuery)->latest()->take(6)->get(),
            'sparkContacts'   => $this->weeklySparkline(Contact::whereNull('model')),
        ];
    }

    // ── Counters (Site Views) ──
    private function getCounterStats(): array
    {
        $totalViews = Counter::sum('number');

        return [
            'totalCounters'      => Counter::count(),
            'totalSiteViews'     => $totalViews,
            // No real page-view tracking table exists yet — this is the same
            // total shown for "this month" until a proper view-log is added.
            'siteViewsThisMonth' => Counter::where('updated_at', '>=', now()->startOfMonth())->sum('number'),
            'counters'           => Counter::where('status', 'active')
                ->orderBy('order')
                ->take(4)
                ->get(),
            'sparkViews'         => $this->weeklySparkline(Counter::query(), 'updated_at', 'number'),
        ];
    }

    // ── Posts ──
    private function getPostStats(): array
    {
        return [
            'totalPosts'     => Post::count(),
            'publishedPosts' => Post::where('status', 'published')->count(),
            'recentPosts'    => Post::latest()->take(3)->get(),
            'sparkPosts'     => $this->weeklySparkline(Post::query()),
        ];
    }

    // ── Applications ──
    private function getApplicationStats(): array
    {
        $breakdown = [
            'New'         => Application::where('status', 'unread')->count(),
            'In Review'   => Application::where('status', 'read')->count(),
            'Shortlisted' => Application::where('status', 'replied')->count(),
        ];

        // Filter out zero-count statuses so the donut/legend don't show empty slices
        $breakdown = array_filter($breakdown, fn($count) => $count > 0);

        // Always show at least one entry so the donut chart doesn't error on empty data
        if (empty($breakdown)) {
            $breakdown = ['No Applications' => 1];
        }

        return [
            'totalApplications'         => Application::count(),
            'newApplicationsThisMonth'  => Application::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'applicationBreakdown'      => $breakdown,
            'sparkApplications'         => $this->weeklySparkline(Application::query()),
        ];
    }

    // ── Content Overview line chart (Posts / Pages / Events / Galleries per week) ──
    private function getContentOverviewChart(): array
    {
        $weeks = collect(range(4, 0))->map(fn($i) => now()->subWeeks($i)->startOfWeek());

        $labels = $weeks->map(fn($start) => $start->format('j M'))->toArray();

        $countPerWeek = function ($model, $column = 'created_at') use ($weeks) {
            return $weeks->map(function ($start) use ($model, $column) {
                $end = $start->copy()->endOfWeek();
                return $model->whereBetween($column, [$start, $end])->count();
            })->toArray();
        };

        return [
            'chartLabels'    => $labels,
            'chartPosts'     => $countPerWeek(Post::query()),
            'chartPages'     => class_exists(\Modules\Common\Entities\Page::class)
                ? $countPerWeek(\Modules\Common\Entities\Page::query())
                : array_fill(0, count($labels), 0),
            'chartEvents'    => class_exists(Event::class)
                ? $countPerWeek(Event::query())
                : array_fill(0, count($labels), 0),
            'chartGalleries' => class_exists(Gallery::class)
                ? $countPerWeek(Gallery::query())
                : array_fill(0, count($labels), 0),
        ];
    }

    // ── Top Performing Content ──
    // No page-view tracking table exists yet, so this returns real records
    // (most recently updated, as a proxy) with an even placeholder view count.
    // Replace with true `views` column data once page-view logging is added.
    private function getTopContent(): array
    {
        $topContent = collect();

        $sources = [
            ['model' => Post::class, 'icon' => 'fas fa-file-alt', 'bg' => 'var(--aa-primary-light)', 'color' => 'var(--aa-primary)'],
            ['model' => Event::class, 'icon' => 'fas fa-calendar-alt', 'bg' => 'var(--aa-green-bg)', 'color' => 'var(--aa-green)'],
            ['model' => Gallery::class, 'icon' => 'fas fa-images', 'bg' => 'var(--aa-blue-bg)', 'color' => 'var(--aa-blue)'],
        ];

        foreach ($sources as $source) {
            if (!class_exists($source['model'])) {
                continue;
            }

            $record = $source['model']::latest()->first();

            if ($record) {
                $topContent->push([
                    'title' => $record->title ?? $record->name ?? 'Untitled',
                    'views' => $record->views ?? 0,
                    'icon'  => $source['icon'],
                    'bg'    => $source['bg'],
                    'color' => $source['color'],
                    'pct'   => 60,
                ]);
            }
        }

        // Normalize progress-bar percentages relative to the highest view count
        $maxViews = $topContent->max('views') ?: 1;
        $topContent = $topContent->map(function ($item) use ($maxViews) {
            $item['pct'] = $item['views'] > 0 ? round(($item['views'] / $maxViews) * 100) : 8;
            return $item;
        });

        return [
            'topContent' => $topContent,
        ];
    }

    // ── Shared helper: 7-point weekly count sparkline for any model ──
    private function weeklySparkline($query, string $dateColumn = 'created_at', ?string $sumColumn = null): array
    {
        $points = [];

        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i);
            $dayQuery = (clone $query)->whereDate($dateColumn, $day->toDateString());

            $points[] = $sumColumn ? (int) $dayQuery->sum($sumColumn) : $dayQuery->count();
        }

        // Avoid a flat all-zero sparkline looking broken — give it a minimal baseline
        if (array_sum($points) === 0) {
            $points = [1, 1, 1, 1, 1, 1, 1];
        }

        return $points;
    }
}
