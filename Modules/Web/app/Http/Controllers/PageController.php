<?php

namespace Modules\Web\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Common\Entities\Product;

class PageController extends Controller
{
    public function home()
    {
        $mainCourses = Product::where('status', 'active')
            ->whereHas('category', fn($q) => $q->where('name', 'Main Course'))
            ->orderBy('sort_order')
            ->get();

        $appetizers = Product::where('status', 'active')
            ->whereHas('category', fn($q) => $q->where('name', 'Appetizers'))
            ->orderBy('sort_order')
            ->get();

        $desserts = Product::where('status', 'active')
            ->whereHas('category', fn($q) => $q->where('name', 'Desserts'))
            ->orderBy('sort_order')
            ->get();

        return view('web::web.home', compact('mainCourses', 'appetizers', 'desserts'));
    }

    public function about()
    {
        return view('web::web.about');
    }

    public function menu()
    {
        $mainCourses = Product::where('status', 'active')
            ->whereHas('category', fn($q) => $q->where('name', 'Main Course'))
            ->orderBy('sort_order')
            ->get();

        $appetizers = Product::where('status', 'active')
            ->whereHas('category', fn($q) => $q->where('name', 'Appetizers'))
            ->orderBy('sort_order')
            ->get();

        $desserts = Product::where('status', 'active')
            ->whereHas('category', fn($q) => $q->where('name', 'Desserts'))
            ->orderBy('sort_order')
            ->get();

        return view('web::web.menu', compact('mainCourses', 'appetizers', 'desserts'));
    }

    public function gallery()
    {
        return view('web::web.gallery');
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
