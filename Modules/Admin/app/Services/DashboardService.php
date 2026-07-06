<?php

namespace Modules\Admin\Services;

use Modules\Admin\Repositories\AdminRepositoryInterface;
use Modules\Common\Entities\Product;
use Modules\Common\Entities\Category;
use Modules\Common\Entities\Contact;
use Modules\Common\Entities\Event;
use Modules\Common\Entities\Testimonial;
use Modules\Common\Entities\Page;
use Modules\Common\Entities\Client;
use Modules\Common\Entities\Album;
use Modules\Common\Entities\Gallery;
use Modules\Common\Entities\Counter;
use Modules\Common\Entities\Blog;

class DashboardService
{
    protected $adminRepository;

    public function __construct(AdminRepositoryInterface $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function getDashboardStats(): array
    {
        return array_merge(
            $this->getUserStats(),
            $this->getProductStats(),
            $this->getContactStats(),
            $this->getAlbumStats(),
            $this->getGalleryStats(),
            $this->getCounterStats(),
            $this->getMiscStats(),
            $this->getBlogStats(),

        );
    }

    // Users
    private function getUserStats(): array
    {
        return [
            'totalUsers'     => $this->adminRepository->getTotalUsers(),
            'todayUsers'     => $this->adminRepository->getTodayUsers(),
            'thisMonthUsers' => $this->adminRepository->getThisMonthUsers(),
            'recentUsers'    => $this->adminRepository->getRecentUsers(10),
        ];
    }
    private function getBlogStats(): array
    {
        return [
            'totalBlogs'    => Blog::count(),
            'activeBlogs'   => Blog::where('status', 'active')->count(),
            'inactiveBlogs' => Blog::where('status', 'inactive')->count(),
            'todayBlogs'    => Blog::whereDate('created_at', today())->count(),
            'recentBlogs'   => Blog::latest()->take(5)->get(),
        ];
    }

    // Products
    private function getProductStats(): array
    {
        return [
            'totalProducts'    => Product::count(),
            'activeProducts'   => Product::where('status', 'active')->count(),
            'inactiveProducts' => Product::where('status', 'inactive')->count(),
            'totalCategories'  => Category::count(),
            'lowStockProducts' => Product::where('stock', '<=', 5)
                ->where('status', 'active')
                ->with('category')
                ->latest()
                ->take(5)
                ->get(),
            'recentProducts'   => Product::with('category')
                ->latest()
                ->take(5)
                ->get(),
        ];
    }

    // Contacts
    private function getContactStats(): array
    {
        return [
            'unreadContacts' => Contact::where('status', 'unread')->count(),
            'recentContacts' => Contact::latest()->take(5)->get(),
        ];
    }

    // Albums
    private function getAlbumStats(): array
    {
        return [
            'totalAlbums'    => Album::count(),
            'activeAlbums'   => Album::where('status', 'active')->count(),
            'inactiveAlbums' => Album::where('status', 'inactive')->count(),
            'recentAlbums'   => Album::withCount('galleries')
                ->latest()
                ->take(6)
                ->get(),
        ];
    }

    // Gallery
    // - column is `path`  (not image_path)
    // - `image_url` accessor on model handles asset() URL automatically
    // - filter image_type = 'gallery' to exclude banners, logos, etc.
    // - filter file_type  = 'image'   to exclude videos and youtube links
    // - scopeOrdered() sorts by sort_order ASC then created_at DESC
    private function getGalleryStats(): array
    {
        return [
            'totalGalleries'    => Gallery::count(),
            'activeGalleries'   => Gallery::where('status', 'active')->count(),
            'inactiveGalleries' => Gallery::where('status', 'inactive')->count(),
            'recentGalleries'   => Gallery::where('file_type', 'image')
                ->whereNotNull('path')
                ->where('path', '!=', '')
                ->ordered()
                ->take(10)
                ->get(),
        ];
    }

    // Counters
    private function getCounterStats(): array
    {
        return [
            'totalCounters' => Counter::count(),
            'counters'      => Counter::where('status', 'active')
                ->orderBy('order')
                ->take(4)
                ->get(),
        ];
    }

    // Misc
    private function getMiscStats(): array
    {
        return [
            'totalEvents'       => Event::count(),
            'totalTestimonials' => Testimonial::count(),
            'totalPages'        => Page::count(),
            'totalClients'      => Client::where('is_active', true)->count(),
        ];
    }
}
