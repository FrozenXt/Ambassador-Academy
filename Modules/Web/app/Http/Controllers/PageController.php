<?php

namespace Modules\Web\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Common\Entities\Product;
use Modules\Common\Entities\Album;
use Modules\Common\Entities\Post;

class PageController extends Controller
{
    public function home()
    {
        $categories = \Modules\Common\Entities\Category::where('status', 'active')
            ->with(['products' => function ($q) {
                $q->where('status', 'active')->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();
        $testimonials = \Modules\Common\Entities\Testimonial::latest()->get();
        $banner = \Modules\Common\Entities\Album::where('code', 'banner')->first();
        $bannerSlides = $banner ? $banner->gallery : collect();
        $heroAlbum = \Modules\Common\Entities\Album::where('code', 'hero')->first();
        $heroImages = $heroAlbum ? $heroAlbum->gallery : collect(); // same relationship name as banner — confirm once you share the Album model

        return view('web::web.home', compact('categories', 'testimonials', 'bannerSlides', 'heroAlbum', 'heroImages'));
    }

    public function about()
    {
        $aboutAlbum = \Modules\Common\Entities\Album::where('code', 'aboutuus')->first();
        $aboutImages = $aboutAlbum ? $aboutAlbum->gallery : collect();
        $chefAlbum = \Modules\Common\Entities\Album::where('code', 'chef')->first();
        $chefImages = $chefAlbum ? $chefAlbum->gallery : collect();

        $aboutBrandPost = Post::where('code', 'first')->first();
        $chairmanPost = Post::where('code', 'second')->first();

        return view('web::web.about', compact('aboutAlbum', 'aboutImages', 'chefAlbum', 'chefImages', 'aboutBrandPost', 'chairmanPost'));
    }

    public function menu()
    {
        $categories = \Modules\Common\Entities\Category::where('status', 'active')
            ->with(['products' => function ($q) {
                $q->where('status', 'active')->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        return view('web::web.menu', compact('categories'));
    }

    public function gallery()
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

        return view('web::web.gallery', compact('album'));
    }

    public function services()
    {
        return view('web::web.services');
    }

    public function contact()
    {
        return view('web::web.contact');
    }
}
