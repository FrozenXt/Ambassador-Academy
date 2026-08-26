<?php

namespace Modules\Web\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Web\Services\PageService;

class PageController extends Controller
{
    public function __construct(protected PageService $pageService) {}

    public function home()
    {
        return view('web::web.home', $this->pageService->getHomeData());
    }

    public function about()
    {
        return view('web::web.about', $this->pageService->getAboutData());
    }

    public function menu()
    {
        return view('web::web.menu', $this->pageService->getMenuData());
    }

    public function gallery()
    {
        return view('web::web.gallery', $this->pageService->getGalleryData());
    }

    public function services()
    {
        return view('web::web.services', $this->pageService->getServiceData());
    }

    public function servicesDetail()
    {
        return view('web::web.services', $this->pageService->getServiceData());
    }

    public function contact()
    {
        return view('web::web.contact');
    }

    public function events()
    {
        return view('web::web.events');
    }
    public function apply()
    {
        return view('web::web.apply');
    }

    public function blog()
    {
        return view('web::web.blog');
    }

    public function menuDetail($slug)
    {
        return view('web::web.Menu-detail', $this->pageService->getMenuDetailData($slug));
    }
}
