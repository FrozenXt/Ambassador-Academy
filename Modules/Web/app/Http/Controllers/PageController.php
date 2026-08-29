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
        $data = app(\Modules\Web\Services\PageService::class)->getGalleryData();

        return view('web::web.gallery', $data);
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
        return view('web::web.events', $this->pageService->getEventsData());
    }

    public function apply()
    {
        return view('web::web.apply');
    }

    public function eca()
    {
        $data = app(\Modules\Web\Services\PageService::class)->getEcaData();

        return view('web::web.eca', $data);
    }

    public function blog()
    {
        $blogs = \Modules\Common\Entities\Blog::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->get();

        return view('web::web.blog', compact('blogs'));
    }
    public function blogShow(string $slug)
    {
        $data = app(\Modules\Web\Services\PageService::class)->getBlogDetailData($slug);

        return view('web::web.blog-detail', $data);
    }
    public function menuDetail($slug)
    {
        return view('web::web.Menu-detail', $this->pageService->getMenuDetailData($slug));
    }

    public function eventsCalendarPartial(\Illuminate\Http\Request $request)
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

    public function eventShow(string $slug)
    {
        $data = app(\Modules\Web\Services\PageService::class)->getEventDetailData($slug);

        return view('web::web.event-detail', $data);
    }

    public function eventIcs(string $slug)
    {
        $event = \Modules\Common\Entities\Event::where('status', 'published')
            ->where('slug', $slug)
            ->firstOrFail();

        $start = $event->start_date->format('Ymd\THis');
        $end = $event->end_date ? $event->end_date->format('Ymd\THis') : $event->start_date->addHour()->format('Ymd\THis');

        $ics = "BEGIN:VCALENDAR\r\n";
        $ics .= "VERSION:2.0\r\n";
        $ics .= "PRODID:-//Ambassador School//Events//EN\r\n";
        $ics .= "BEGIN:VEVENT\r\n";
        $ics .= "UID:" . $event->slug . "@ambassadorschool.edu.np\r\n";
        $ics .= "DTSTAMP:" . now()->format('Ymd\THis\Z') . "\r\n";
        $ics .= "DTSTART:" . $start . "\r\n";
        $ics .= "DTEND:" . $end . "\r\n";
        $ics .= "SUMMARY:" . addcslashes($event->title, ",;") . "\r\n";
        $ics .= "DESCRIPTION:" . addcslashes(strip_tags($event->short_description ?? ''), ",;\n") . "\r\n";
        $ics .= "LOCATION:" . addcslashes($event->venue ?? $event->location ?? '', ",;") . "\r\n";
        $ics .= "END:VEVENT\r\n";
        $ics .= "END:VCALENDAR\r\n";

        return response($ics, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $event->slug . '.ics"',
        ]);
    }
    public function ecaShow(string $slug)
    {
        $data = app(\Modules\Web\Services\PageService::class)->getEcaDetailData($slug);

        return view('web::web.eca-detail', $data);
    }
}
