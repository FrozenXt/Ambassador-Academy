<?php

namespace Modules\Web\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Web\Http\Requests\ContactRequest;

use Modules\Common\Services\ContactService;
use Modules\Web\Services\HomeService;

use Modules\Common\Entities\SiteSetting;
use Modules\Common\Entities\Blog;

use Modules\Common\Services\BlogService;

class WebController extends Controller
{
    protected $homeService;
    protected $contactService;
    protected $blogService;

    public function __construct(
        HomeService $homeService,
        ContactService $contactService,
        BlogService $blogService
    ) {
        $this->homeService = $homeService;
        $this->contactService = $contactService;
        $this->blogService = $blogService;

        $settings = SiteSetting::all()->keyBy('key');
        view()->share('settings', $settings);
    }

    public function home()
    {
        $data = $this->homeService->getHomeData();
        return view('web::web.home', $data);
    }

    public function contact()
    {
        return view('web::web.contact');
    }

    public function contactSubmit(ContactRequest $request)
    {
        $this->contactService->createContact($request->validated());

        return back()->with('success', 'Your message has been sent! We will get back to you soon.');
    }
    public function blogDetail($slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();

        return view('web::web.blog-detail', compact('blog'));
    }
    public function blog()
    {
        $filters = request()->only(['search', 'category']);

        $allBlogs = $this->blogService->getBlogListing(6, $filters);

        return view('web::web.blog', compact('allBlogs'));
    }


    // public function pricing()
    // {
    //     $pricings = Pricing::where('is_active', true)
    //         ->orderBy('sort_order', 'asc')
    //         ->orderBy('id', 'asc')
    //         ->get();

    //     // Add features_list attribute to each pricing
    //     foreach ($pricings as $pricing) {
    //         $pricing->features_list = is_array($pricing->features) ? $pricing->features : [];
    //     }

    //     return view('web::web.pricing', compact('pricings'));
    // }

    // public function faq()
    // {
    //     // Get all active FAQs grouped by category
    //     $faqs = Faq::where('status', 'active')
    //         ->orderBy('order')
    //         ->get()
    //         ->groupBy(function ($faq) {
    //             return $faq->category ?: 'General';
    //         });

    //     return view('web::web.faq', compact('faqs'));
    // }

    // // ── Products
    // public function products()
    // {
    //     $categories = Category::where('status', 'active')->get();

    //     $query = Product::where('status', 'active')->with('category');

    //     if (request('category')) {
    //         $query->where('category_id', request('category'));
    //     }

    //     if (request('search')) {
    //         $query->where('name', 'like', '%' . request('search') . '%');
    //     }

    //     if (request('sort') == 'price_low') {
    //         $query->orderBy('price', 'asc');
    //     } elseif (request('sort') == 'price_high') {
    //         $query->orderBy('price', 'desc');
    //     } else {
    //         $query->latest();
    //     }

    //     $products = $query->paginate(12)->withQueryString();

    //     return view('web::web.products', compact('products', 'categories'));
    // }

    // public function productDetail(Product $product)
    // {
    //     abort_if($product->status !== 'active', 404);

    //     $related = Product::where('category_id', $product->category_id)
    //         ->where('id', '!=', $product->id)
    //         ->where('status', 'active')
    //         ->take(4)
    //         ->get();

    //     return view('web::web.product-detail', compact('product', 'related'));
    // }

    // // ── Clients
    // public function clients()
    // {
    //     $clients = Client::where('is_active', true)
    //         ->latest()
    //         ->paginate(12);

    //     // Stats for the page
    //     $totalClients  = Client::where('is_active', true)->count();
    //     $industries    = Client::where('is_active', true)
    //         ->whereNotNull('industry_type')
    //         ->distinct()
    //         ->pluck('industry_type');
    //     $countries     = Client::where('is_active', true)
    //         ->whereNotNull('country')
    //         ->distinct()
    //         ->pluck('country');

    //     return view('web::web.clients', compact(
    //         'clients',
    //         'totalClients',
    //         'industries',
    //         'countries'
    //     ));
    // }

    // // ── Services
    // public function services()
    // {
    //     // Update this when you have a Service entity
    //     $services = collect();

    //     return view('web::web.services', compact('services'));
    // }

    // public function serviceDetail(string $slug)
    // {
    //     $service = Service::where('slug', $slug)
    //         ->where('status', 'active') // optional
    //         ->firstOrFail();

    //     $otherServices = Service::where(
    //         'status',
    //         'active'
    //     )
    //         ->where('id', '!=', $service->id)
    //         ->latest()
    //         ->take(5)
    //         ->get();

    //     return view('web::web.service-detail', compact('service', 'otherServices'));
    // }

    // // ── Events
    // public function events()
    // {
    //     $upcoming = Event::where('status', 'published')
    //         ->where('type', 'event')
    //         ->where('start_date', '>', now())
    //         ->orderBy('start_date')
    //         ->paginate(9);

    //     $past = Event::where('status', 'published')
    //         ->where('type', 'event')
    //         ->where('start_date', '<=', now())
    //         ->orderByDesc('start_date')
    //         ->take(3)
    //         ->get();

    //     return view('web::web.events', compact('upcoming', 'past'));
    // }

    // public function eventDetail(string $slug)
    // {
    //     $event = Event::where('slug', $slug)
    //         ->where('status', 'published')
    //         ->where('type', 'event')
    //         ->firstOrFail();

    //     $related = Event::where('status', 'published')
    //         ->where('type', 'event')
    //         ->where('id', '!=', $event->id)
    //         ->where('start_date', '>', now())
    //         ->orderBy('start_date')
    //         ->take(3)
    //         ->get();

    //     return view('web::web.event-detail', compact('event', 'related'));
    // }


    // // ── Notices / Announcements
    // public function notices()
    // {
    //     $notices = Notice::where('status', 'published')
    //         ->whereIn('type', ['news', 'notice'])  // include both types
    //         ->orderByDesc('created_at')
    //         ->paginate(10);

    //     return view('web::web.notices', compact('notices'));
    // }

    // public function noticeDetail(string $slug)
    // {
    //     $notice = Notice::where('slug', $slug)
    //         ->where('status', 'published')
    //         ->whereIn('type', ['news', 'notice']) // include both types
    //         ->firstOrFail();

    //     return view('web::web.notice-detail', compact('notice'));
    // }
}
