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
        $galleryAlbums = \Modules\Common\Entities\Album::where('is_gallery_category', 1)
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
    /**
     * Data for the Events page.
     */
    public function getEventsData(): array
    {
        $featuredEvents = \Modules\Common\Entities\Event::where('status', 'published')
            ->where('type', 'event')
            ->where('is_featured', true)
            ->orderBy('start_date')
            ->orderBy('order')
            ->take(6)
            ->get();

        $upcomingEvents = \Modules\Common\Entities\Event::where('status', 'published')
            ->where('type', 'event')
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->orderBy('order')
            ->take(5)
            ->get();

        $calMonth = \Carbon\Carbon::create(
            request('year', now()->year),
            request('month', now()->month),
            1
        );

        $calendarEvents = \Modules\Common\Entities\Event::where('status', 'published')
            ->where('type', 'event')
            ->whereYear('start_date', $calMonth->year)
            ->whereMonth('start_date', $calMonth->month)
            ->get();

        return compact('featuredEvents', 'upcomingEvents', 'calendarEvents');

        // return compact('featuredEvents', 'upcomingEvents');
    }

    public function calendarPartial(Request $request)
    {
        $calMonth = \Carbon\Carbon::create(
            $request->query('year', now()->year),
            $request->query('month', now()->month),
            1
        );

        $calendarEvents = \Modules\Common\Entities\Event::where('status', 'published')
            ->where('type', 'event')
            ->whereYear('start_date', $calMonth->year)
            ->whereMonth('start_date', $calMonth->month)
            ->get();

        return view('web::partials.calendar', compact('calendarEvents'))->render();
    }

    public function getEcaData(): array
    {
        $ecaPost = \Modules\Common\Entities\Post::where('code', 'eca-page')->first();

        $ecaCards = \Modules\Common\Entities\Service::where('status', 'active')
            ->where('type', 'eca-page')
            ->orderBy('order')
            ->get();

        return compact('ecaPost', 'ecaCards');
    }

    public function getBlogDetailData(string $slug): array
    {
        $blog = \Modules\Common\Entities\Blog::published()
            ->where('slug', $slug)
            ->with(['category', 'author'])
            ->firstOrFail();

        $blog->incrementViews();

        $categories = \Modules\Common\Entities\BlogCategory::withCount(['blogs' => function ($q) {
            $q->where('status', 'published');
        }])
            ->having('blogs_count', '>', 0)
            ->orderBy('name')
            ->get();

        $recentPosts = \Modules\Common\Entities\Blog::published()
            ->where('id', '!=', $blog->id)
            ->orderBy('published_at', 'desc')
            ->take(4)
            ->get();

        $relatedPosts = \Modules\Common\Entities\Blog::published()
            ->where('id', '!=', $blog->id)
            ->where('category_id', $blog->category_id)
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        // fallback: if not enough related posts in same category, pad with recent posts
        if ($relatedPosts->count() < 3) {
            $exclude = $relatedPosts->pluck('id')->push($blog->id);
            $extra = \Modules\Common\Entities\Blog::published()
                ->whereNotIn('id', $exclude)
                ->orderBy('published_at', 'desc')
                ->take(3 - $relatedPosts->count())
                ->get();
            $relatedPosts = $relatedPosts->concat($extra);
        }

        return compact('blog', 'categories', 'recentPosts', 'relatedPosts');
    }

    public function getEventDetailData(string $slug): array
    {
        $event = \Modules\Common\Entities\Event::where('status', 'published')
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedEvents = \Modules\Common\Entities\Event::where('status', 'published')
            ->where('id', '!=', $event->id)
            ->where('type', $event->type)
            ->orderBy('start_date', 'desc')
            ->take(3)
            ->get();

        if ($relatedEvents->count() < 3) {
            $exclude = $relatedEvents->pluck('id')->push($event->id);
            $extra = \Modules\Common\Entities\Event::where('status', 'published')
                ->whereNotIn('id', $exclude)
                ->orderBy('start_date', 'desc')
                ->take(3 - $relatedEvents->count())
                ->get();
            $relatedEvents = $relatedEvents->concat($extra);
        }

        return compact('event', 'relatedEvents');
    }

    public function getEcaDetailData(string $slug): array
    {
        $ecaItem = \Modules\Common\Entities\Service::where('status', 'active')
            ->where('type', 'eca-page')
            ->where('slug', $slug)
            ->firstOrFail();

        $categories = \Modules\Common\Entities\Service::where('status', 'active')
            ->where('type', 'service-features')
            ->orderBy('order')
            ->get();

        $galleryAlbum = \Modules\Common\Entities\Album::where('is_gallery_category', true)
            ->where('title', $ecaItem->title)
            ->with('gallery')
            ->first();

        $galleryImages = $galleryAlbum ? $galleryAlbum->gallery->take(4) : collect();

        return compact('ecaItem', 'categories', 'galleryImages');
    }
}
