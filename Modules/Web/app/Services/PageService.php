<?php

namespace Modules\Web\Services;

use Modules\Common\Entities\Album;
use Modules\Common\Entities\Category;
use Modules\Common\Entities\Post;
use Modules\Common\Entities\Service;
use Modules\Common\Entities\Testimonial;

class PageService
{
    /**
     * Data for the Home page.
     */

    /**
     * Data for the Home page.
     */
    /**
     * Data for the Home page.
     */
    public function getHomeData(): array
    {
        $bannerAlbum   = Album::where('code', 'banner')->first();
        $bannerImages  = $bannerAlbum ? $bannerAlbum->gallery : collect();
        $bannerContent = $bannerImages->first();

        $storyPost = Post::where('code', 'story')->first();

        $checkListItems = collect();

        if ($storyPost && $storyPost->content) {
            preg_match_all('/<p>(.*?)<\/p>/s', $storyPost->content, $matches);

            $checkListItems = !empty($matches[1])
                ? collect($matches[1])->filter(fn($line) => trim(strip_tags($line)) !== '')
                : collect(explode("\n", strip_tags($storyPost->content)))->filter(fn($line) => trim($line) !== '');
        }

        $academicFeatures = Service::where('status', 'active')
            ->where('type', 'home-features')
            ->orderBy('order')
            ->get();

        $academics = Service::where('status', 'active')
            ->where('type', 'home-amenities')
            ->orderBy('order')
            ->get();

        $ecaPost = Post::where('code', 'eca')->first();

        $ecaGalleryAlbum = Album::where('code', 'gallery')->first();
        $ecaGalleryImages = $ecaGalleryAlbum
            ? $ecaGalleryAlbum->gallery->where('file_type', 'image')->take(5)->values()
            : collect();

        $menuCategory = Category::where('name', 'menu')
            ->where('status', 'active')
            ->with(['products' => function ($q) {
                $q->where('status', 'active')->orderBy('sort_order')->take(4);
            }])
            ->first();

        $galleryAlbum  = Album::where('code', 'gallery')->first();
        $galleryImages = $galleryAlbum ? $galleryAlbum->gallery : collect();

        $menuAlbum         = Album::where('code', 'menu')->first();
        $menuGalleryImages = $menuAlbum ? $menuAlbum->gallery->take(2) : collect();

        $upcomingEvents = \Modules\Common\Entities\Event::where('status', 'published')
            ->where('type', 'event')
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->orderBy('order')
            ->take(3)
            ->get();

        $latestBlogs = \Modules\Common\Entities\Blog::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();
        $testimonials = \Modules\Common\Entities\Testimonial::where('status', 'active')
            ->orderBy('order')
            ->get();

        $testimonialAlbum = \Modules\Common\Entities\Album::where('code', 'testimonial')->first();
        $testimonialImage = $testimonialAlbum ? $testimonialAlbum->gallery->first() : null;

        return compact(
            'bannerImages',
            'bannerContent',
            // 'academicFeatures',
            'academics',
            'storyPost',
            'checkListItems',
            'ecaPost',
            'ecaGalleryImages',
            'menuCategory',
            'menuGalleryImages',
            'galleryImages',
            'upcomingEvents',
            'latestBlogs',
            'testimonials',
            'testimonialImage'
        );
    }
    /**
     * Data for the About page.
     */
    public function getAboutData(): array
    {
        $aboutPost = \Modules\Common\Entities\Post::where('code', 'about-page')->first();

        $chairpersonPost = \Modules\Common\Entities\Post::where('code', 'chairman')->first();

        $amenityMediaAlbum = \Modules\Common\Entities\Album::where('code', 'about-amenity-media')->first();
        $amenityImages = $amenityMediaAlbum ? $amenityMediaAlbum->gallery : collect();

        $amenities = \Modules\Common\Entities\Service::where('status', 'active')
            ->where('type', 'about-amenities')
            ->orderBy('order')
            ->get();

        $upcomingEvents = \Modules\Common\Entities\Event::where('status', 'published')
            ->where('type', 'event')
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->orderBy('order')
            ->get();

        $counters = \Modules\Common\Entities\Counter::where('status', 'active')
            ->orderBy('order')
            ->get();

        return compact('aboutPost', 'chairpersonPost', 'amenityImages', 'amenities', 'upcomingEvents', 'counters');
    }
    /**
     * Data for the Menu page.
     */
    public function getMenuData(): array
    {
        $categories = \Modules\Common\Entities\Category::where('status', 'active')
            ->with(['products' => function ($q) {
                $q->where('status', 'active')->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        return compact('categories');
    }

    /**
     * Data for the Gallery page.
     */
    public function getGalleryData(): array
    {
        $galleryAlbums = \Modules\Common\Entities\Album::where('is_gallery_category', true)
            ->where('status', 'active')
            ->with('gallery')
            ->orderBy('sort_order')
            ->get();

        $galleryItems = collect();

        foreach ($galleryAlbums as $album) {
            foreach ($album->gallery as $img) {
                $galleryItems->push([
                    'image'    => $img,
                    'category' => $album->slug,
                    'label'    => $album->title,
                ]);
            }
        }

        $filters = $galleryAlbums
            ->filter(fn($album) => $album->gallery->isNotEmpty())
            ->map(fn($album) => [
                'slug'  => $album->slug,
                'label' => $album->title,
            ])
            ->values();

        return compact('galleryItems', 'filters');
    }


    public function getMenuDetailData($slug): array
    {
        $product = \Modules\Common\Entities\Product::where('slug', $slug)
            ->where('status', 'active')
            // ->with('category')
            ->firstOrFail();

        return compact('product');
    }

    public function getServiceData(): array
    {
        // $servicePost = \Modules\Common\Entities\Post::where('code', 'service-page')->first();

        $quickServices = \Modules\Common\Entities\Service::where('status', 'active')
            ->where('type', 'service-page-icon')
            ->orderBy('order')
            ->get();

        $serviceFeatures = \Modules\Common\Entities\Service::where('status', 'active')
            ->where('type', 'service-features')
            ->orderBy('order')
            ->get();

        return compact('quickServices', 'serviceFeatures');
    }
    public function showDetail(string $slug): \Illuminate\View\View
    {
        $service = \Modules\Common\Entities\Service::where('status', 'active')
            ->where('type', 'service-features')
            ->where('slug', $slug)
            ->firstOrFail();

        $otherServices = \Modules\Common\Entities\Service::where('status', 'active')
            ->where('type', 'service-features')
            ->where('id', '!=', $service->id)
            ->orderBy('order')
            ->get();

        return view('pages.service-detail', compact('service', 'otherServices'));
    }
}
