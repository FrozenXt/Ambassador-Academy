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
    public function getHomeData(): array
    {
        $categories = Category::where('status', 'active')
            ->with(['products' => function ($q) {
                $q->where('status', 'active')->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        $testimonials = Testimonial::latest()->get();

        $banner = Album::where('code', 'banner')->first();
        $bannerSlides = $banner ? $banner->gallery : collect();

        $heroAlbum = Album::where('code', 'hero')->first();
        $heroImages = $heroAlbum ? $heroAlbum->gallery : collect();

        $whyChooseUsServices = Service::where('status', 'active')
            ->where('type', 'Why People Choose Us?')
            ->orderBy('order')
            ->get();

        $heroVideoAlbum = Album::where('code', 'home-video')->first();
        $heroVideoItem = $heroVideoAlbum ? $heroVideoAlbum->gallery->first() : null;

        return compact(
            'categories',
            'testimonials',
            'bannerSlides',
            'heroAlbum',
            'heroImages',
            'whyChooseUsServices',
            'heroVideoItem'
        );
    }

    /**
     * Data for the About page.
     */
    public function getAboutData(): array
    {
        $aboutAlbum = Album::where('code', 'aboutuus')->first();
        $aboutImages = $aboutAlbum ? $aboutAlbum->gallery : collect();

        $chefAlbum = Album::where('code', 'chef')->first();
        $chefImages = $chefAlbum ? $chefAlbum->gallery : collect();

        $aboutBrandPost = Post::where('code', 'first')->first();
        $chairmanPost = Post::where('code', 'second')->first();

        return compact(
            'aboutAlbum',
            'aboutImages',
            'chefAlbum',
            'chefImages',
            'aboutBrandPost',
            'chairmanPost'
        );
    }

    /**
     * Data for the Menu page.
     */
    public function getMenuData(): array
    {
        $categories = Category::where('status', 'active')
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
        $album = Album::where('code', 'gallery')
            ->with(['galleries' => function ($query) {
                $query->where('file_type', 'image')
                    ->whereNotNull('path')
                    ->where('path', '!=', '')
                    ->where('status', 'active')
                    ->orderBy('sort_order');
            }])
            ->first();

        return compact('album');
    }

    /**
     * Data for the Services page.
     */
    public function getServicesData(): array
    {
        $services = Service::where('status', 'active')
            ->where('type', 'servicepage')
            ->orderBy('order')
            ->get();

        return compact('services');
    }
}
