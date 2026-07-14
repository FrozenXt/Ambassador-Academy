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
        $bannerAlbum = \Modules\Common\Entities\Album::where('code', 'banner')->first();
        $bannerItem = $bannerAlbum ? $bannerAlbum->gallery->first() : null;

        $heroAlbum = \Modules\Common\Entities\Album::where('code', 'hero')->first();
        $heroBottleBack = $heroAlbum ? $heroAlbum->gallery->first() : null;
        $heroBottleFront = $heroAlbum ? $heroAlbum->gallery->skip(1)->first() : null;
        $storyPost = \Modules\Common\Entities\Post::where('code', 'story')->first();

        $revealFeatures = \Modules\Common\Entities\Service::where('status', 'active')
            ->where('type', 'hero')
            ->orderBy('order')
            ->get();


        $papasFeatures = \Modules\Common\Entities\Service::where('status', 'active')
            ->where('type', 'features')
            ->orderBy('order')
            ->get();
        $menuCategory = \Modules\Common\Entities\Category::where('name', 'menu')
            ->where('status', 'active')
            ->with(['products' => function ($q) {
                $q->where('status', 'active')->orderBy('sort_order')->take(4);
            }])
            ->first();

        $galleryAlbum = \Modules\Common\Entities\Album::where('code', 'gallery')->first();
        $galleryImages = $galleryAlbum ? $galleryAlbum->gallery : collect();
        $menuAlbum = \Modules\Common\Entities\Album::where('code', 'menu')->first();
        $menuGalleryImages = $menuAlbum ? $menuAlbum->gallery->take(2) : collect();

        return compact('bannerItem', 'heroBottleBack', 'heroBottleFront', 'papasFeatures', 'revealFeatures', 'storyPost', 'menuCategory', 'menuGalleryImages', 'galleryImages');
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
