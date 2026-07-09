<?php

namespace Modules\Admin\Services;

use Modules\Common\Entities\Product;
use Modules\Common\Entities\Category;
use Modules\Common\Entities\Contact;
use Modules\Common\Entities\Counter;

class DashboardService
{
    public function getDashboardStats(): array
    {
        return array_merge(
            $this->getProductStats(),
            $this->getCategoryStats(),
            $this->getContactStats(),
            $this->getCounterStats(),
        );
    }

    // Products
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

    // Categories
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

    // Contacts
    private function getContactStats(): array
    {
        return [
            'unreadContacts'  => Contact::where('status', 'unread')->count(),
            'repliedContacts' => Contact::where('status', 'replied')->count(),
            'recentContacts'  => Contact::latest()->take(6)->get(),
        ];
    }

    // Counters (Site Views)
    private function getCounterStats(): array
    {
        return [
            'totalCounters' => Counter::count(),
            'totalSiteViews' => Counter::sum('number'),
            'counters'      => Counter::where('status', 'active')
                ->orderBy('order')
                ->take(4)
                ->get(),
        ];
    }
}
