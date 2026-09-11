<?php

use Illuminate\Support\Facades\Route;
use Modules\Web\Http\Controllers\WebController;
use Modules\Common\Entities\Page;
use Modules\Common\Entities\SiteSetting;
use Modules\Common\Entities\Contact;
use Modules\Web\Http\Controllers\ContactController;
use Modules\Web\Http\Controllers\PageController;
use Modules\Web\Http\Controllers\ApplyController;

/*
|--------------------------------------------------------------------------
| Web Frontend Routes
|--------------------------------------------------------------------------
*/

// Route::get('/phpinfo-test', function () {
//     phpinfo();
// });

// // ── Home
// Route::get('/', [WebController::class, 'home'])->name('web.home');

// ── Products
// Route::get('/products',           [WebController::class, 'products'])->name('web.products');
//Route::get('/products/{product}', [WebController::class, 'productDetail'])->name('web.product.detail');

// ── Services
//Route::get('/services',        [WebController::class, 'services'])->name('web.services');
//Route::get('/services/{service}', [WebController::class, 'serviceDetail'])->name('web.service.detail');

// ── Events
//Route::get('/events',        [WebController::class, 'events'])->name('web.events');
//Route::get('/events/{slug}', [WebController::class, 'eventDetail'])->name('web.event.detail');

// ── Notices
//Route::get('/notices',        [WebController::class, 'notices'])->name('web.notices');
//Route::get('/notices/{slug}', [WebController::class, 'noticeDetail'])->name('web.notice.detail');

// ── Contact
//Route::get('/contact',  [WebController::class, 'contact'])->name('web.contact');
//Route::post('/contact', [WebController::class, 'contactSubmit'])->name('web.contact.submit');


// ── Contact
//Route::get('/faq', [WebController::class, 'faq'])->name('web.faq');

//-─ Pricing
// Route::get('/pricing', [WebController::class, 'pricing'])->name('web.pricing');
// Route::get('/blog',                 [WebController::class, 'blog'])->name('web.blog');
// Route::get('/blog/category/{slug}', [WebController::class, 'blogCategory'])->name('web.blog.category');
// Route::get('/blog/{slug}',          [WebController::class, 'blogDetail'])->name('web.blog.detail');
// Route::get('/blog/{slug}', [WebController::class, 'blogDetail'])->name('web.blog.detail');
// ── Pages
// Route::get('/page/{slug}', function (string $slug) {
//     $page = Page::where('slug', $slug)
//         ->where('status', 'published')
//         ->firstOrFail();
//     $settings = SiteSetting::all()->keyBy('key');
//     return view('web::web.page', compact('page', 'settings'));
// })->name('web.page');

// Route::view('/menu', 'web.pages.menu')->name('menu');

//Route::get('/contact', [ContactController::class, 'index'])->name('web.contact');
// Route::post('/contact', [ContactController::class, 'submit'])->name('web.contact.submit');

// ── Clients
//Route::get('/clients', [WebController::class, 'clients'])->name('web.clients');


Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/menu', [PageController::class, 'menu'])->name('menu');
Route::get('menu/{slug}', [PageController::class, 'menuDetail'])->name('menu.detail');
Route::get('/gallery', [PageController::class, 'gallery'])->name('gallery');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/events', [PageController::class, 'events'])->name('events');

Route::get('/events/calendar-partial', [PageController::class, 'eventsCalendarPartial'])->name('events.calendar.partial');
// Route::get('/events/{event}', [PageController::class, 'events'])->name('events.show');
Route::get('/events/{slug}', [PageController::class, 'eventShow'])->name('events.show');
Route::get('/eca', [PageController::class, 'eca'])->name('eca');
Route::get('/blog', [PageController::class, 'blog'])->name('blog');
Route::get('/blog/{blog}', [PageController::class, 'blogShow'])->name('blog.show');
Route::get('/testimonials', [PageController::class, 'testimonials'])->name('testimonials');
Route::get('/newsletter', [PageController::class, 'newsletter'])->name('newsletter.subscribe');
// 
Route::get('/apply', [ApplyController::class, 'index'])->name('apply');
Route::post('/apply', [ApplyController::class, 'store'])->name('apply.store');
Route::get('/services{service}', [PageController::class, 'service-detail'])->name('service.detail');
// Route::get('/contact', [PageController::class, 'contact'])->name('contact');
// Route::post('/contact', [PageController::class, 'processContact'])->name('contact.process');
Route::get('/gallery', [PageController::class, 'gallery'])->name('gallery');
Route::get('/eca/{slug}', [PageController::class, 'ecaShow'])->name('eca.show');
// Route::get('/contact', [ContactController::class, 'index'])->name('contact');
// Route::post('/contact', [ContactController::class, 'process'])->name('contact.process');

// Route::get('/blog/{slug}', [PageController::class, 'blogShow'])->name('blog.show');
Route::get('/events/{slug}/calendar.ics', [PageController::class, 'eventIcs'])->name('events.calendar.ics');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'process'])->name('contact.process');
