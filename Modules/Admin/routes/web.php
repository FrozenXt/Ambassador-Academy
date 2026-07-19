<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AuthController;
use Modules\Admin\Http\Controllers\DashboardController;
use Modules\Admin\Http\Controllers\BannerController;
use Modules\Admin\Http\Controllers\MenuController;
use Modules\Admin\Http\Controllers\ContactController;
use Modules\Admin\Http\Controllers\ServiceController;
use Modules\Admin\Http\Controllers\PageController;
use Modules\Admin\Http\Controllers\TestimonialController;
use Modules\Admin\Http\Controllers\EventController;
use Modules\Admin\Http\Controllers\NoticeController;
use Modules\Admin\Http\Controllers\SiteSettingController;
use Modules\Admin\Http\Controllers\MediaController;
use Modules\Admin\Http\Controllers\CategoryController;
use Modules\Admin\Http\Controllers\ProductController;
use Modules\Admin\Http\Controllers\ClientController;
use Modules\Admin\Http\Controllers\FaqController;
use Modules\Admin\Http\Controllers\CounterController;
use Modules\Admin\Http\Controllers\PricingController;
use Modules\Admin\Http\Controllers\EmailSettingController;
use Modules\Admin\Http\Controllers\UserManagementController;
use Modules\Admin\Http\Controllers\AlbumController;
use Modules\Admin\Http\Controllers\GalleryController;
use Modules\Admin\Http\Controllers\BlogCategoryController;
use Modules\Admin\Http\Controllers\BlogController;
use Modules\Admin\Http\Controllers\BrochureController;
use Modules\Admin\Http\Controllers\PostController;
use Modules\Admin\Http\Controllers\AccountController;

Route::prefix('admin')->name('admin.')->group(function () {

    // ── Public auth routes ──
    Route::get('/login',   [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',  [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ── Protected routes ──
    Route::middleware(\Modules\Admin\Http\Middleware\AdminMiddleware::class)
        ->group(function () {

            // Role shorthands
            // superadmin only        → checkRole:superadmin
            // superadmin + admin     → checkRole:superadmin,admin
            // all except staff       → checkRole:superadmin,admin,manager
            // everyone               → checkRole:superadmin,admin,manager,staff

            // ── Dashboard (all roles) ──
            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->name('dashboard')
                ->middleware('checkRole:superadmin,admin,manager,staff');


            // ── User Management (superadmin only) ──
            Route::middleware('checkRole:superadmin')->group(function () {
                Route::get('users',                       [UserManagementController::class, 'index'])->name('users.index');
                Route::get('users/create',                [UserManagementController::class, 'create'])->name('users.create');
                Route::post('users',                      [UserManagementController::class, 'store'])->name('users.store');
                Route::get('users/{id}/edit',             [UserManagementController::class, 'edit'])->name('users.edit');
                Route::put('users/{id}',                  [UserManagementController::class, 'update'])->name('users.update');
                Route::delete('users/{id}',               [UserManagementController::class, 'destroy'])->name('users.destroy');
                Route::post('users/{id}/toggle-status',   [UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');
                Route::delete('users/{id}/remove-avatar', [UserManagementController::class, 'removeAvatar'])->name('users.remove-avatar');
            });


            // ── Categories ──
            Route::get('categories',           [CategoryController::class, 'index'])->name('categories.index')->middleware('checkRole:superadmin,admin,manager,staff');
            Route::get('categories/create',    [CategoryController::class, 'create'])->name('categories.create')->middleware('checkRole:superadmin,admin,manager');
            Route::post('categories',          [CategoryController::class, 'store'])->name('categories.store')->middleware('checkRole:superadmin,admin,manager');
            Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit')->middleware('checkRole:superadmin,admin,manager');
            Route::put('categories/{category}',      [CategoryController::class, 'update'])->name('categories.update')->middleware('checkRole:superadmin,admin,manager');
            Route::delete('categories/{category}',   [CategoryController::class, 'destroy'])->name('categories.destroy')->middleware('checkRole:superadmin,admin');
            Route::post('categories/update-order', [CategoryController::class, 'updateOrder'])->name('categories.update-order')->middleware('checkRole:superadmin,admin,manager');


            // ── Products ──
            Route::get('products',           [ProductController::class, 'index'])->name('products.index')->middleware('checkRole:superadmin,admin,manager,staff');
            Route::get('products/create',    [ProductController::class, 'create'])->name('products.create')->middleware('checkRole:superadmin,admin,manager');
            Route::post('products',          [ProductController::class, 'store'])->name('products.store')->middleware('checkRole:superadmin,admin,manager');
            Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show')->middleware('checkRole:superadmin,admin,manager,staff');
            Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit')->middleware('checkRole:superadmin,admin,manager');
            Route::put('products/{product}',      [ProductController::class, 'update'])->name('products.update')->middleware('checkRole:superadmin,admin,manager');
            Route::delete('products/{product}',   [ProductController::class, 'destroy'])->name('products.destroy')->middleware('checkRole:superadmin,admin');
            Route::post('products/update-order', [ProductController::class, 'updateOrder'])->name('products.update-order')->middleware('checkRole:superadmin,admin,manager');



            // ── Banners ──
            // Route::get('banners',             [BannerController::class, 'index'])->name('banners.index')->middleware('checkRole:superadmin,admin,manager,staff');
            // Route::get('banners/create',      [BannerController::class, 'create'])->name('banners.create')->middleware('checkRole:superadmin,admin,manager');
            // Route::post('banners',            [BannerController::class, 'store'])->name('banners.store')->middleware('checkRole:superadmin,admin,manager');
            // Route::get('banners/{banner}/edit',   [BannerController::class, 'edit'])->name('banners.edit')->middleware('checkRole:superadmin,admin,manager');
            // Route::put('banners/{banner}',        [BannerController::class, 'update'])->name('banners.update')->middleware('checkRole:superadmin,admin,manager');
            // Route::delete('banners/{banner}',     [BannerController::class, 'destroy'])->name('banners.destroy')->middleware('checkRole:superadmin,admin');
            // Route::post('banners/{banner}/toggle', [BannerController::class, 'toggle'])
            //     ->name('banners.toggle')
            //     ->middleware('checkRole:superadmin,admin,manager');
            // Route::post('banners/reorder',    [BannerController::class, 'reorder'])->name('banners.reorder')->middleware('checkRole:superadmin,admin,manager');


            // ── Services ──
            Route::get('services', [ServiceController::class, 'index'])->name('services.index')->middleware('checkRole:superadmin,admin,manager,staff');
            Route::get('services/create', [ServiceController::class, 'create'])->name('services.create')->middleware('checkRole:superadmin,admin,manager');
            Route::post('services', [ServiceController::class, 'store'])->name('services.store')->middleware('checkRole:superadmin,admin,manager');
            Route::get('services/trash', [ServiceController::class, 'trash'])->name('services.trash')->middleware('checkRole:superadmin,admin');
            Route::get('services/{id}/edit', [ServiceController::class, 'edit'])->name('services.edit')->middleware('checkRole:superadmin,admin,manager');
            Route::get('services/{id}', [ServiceController::class, 'show'])->name('services.show')->middleware('checkRole:superadmin,admin,manager,staff');
            Route::put('services/{id}', [ServiceController::class, 'update'])->name('services.update')->middleware('checkRole:superadmin,admin,manager');
            Route::delete('services/{id}', [ServiceController::class, 'destroy'])->name('services.destroy')->middleware('checkRole:superadmin,admin');
            Route::post('services/{id}/restore', [ServiceController::class, 'restore'])->name('services.restore')->middleware('checkRole:superadmin,admin');
            Route::delete('services/{id}/force-delete', [ServiceController::class, 'forceDelete'])->name('services.force-delete')->middleware('checkRole:superadmin,admin');
            Route::post('services/{id}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('services.toggle-status')->middleware('checkRole:superadmin,admin,manager');
            Route::post('services/reorder', [ServiceController::class, 'reorder'])->name('services.reorder')->middleware('checkRole:superadmin,admin,manager');
            Route::delete('services/{id}/remove-image', [ServiceController::class, 'removeImage'])->name('services.remove-image')->middleware('checkRole:superadmin,admin,manager');
            // // ── Albums ──
            Route::resource('albums', AlbumController::class)->except(['show']);
            Route::post('albums/sort-order', [AlbumController::class, 'updateSortOrder'])
                ->name('albums.sort-order');

            Route::prefix('gallery')->name('gallery.')->middleware('checkRole:superadmin,admin,manager,staff')->group(function () {
                Route::get('/',                         [GalleryController::class, 'index'])->name('index');
                Route::get('/create',                   [GalleryController::class, 'create'])->name('create');
                Route::post('/',                        [GalleryController::class, 'store'])->name('store');
                Route::get('/{id}',                     [GalleryController::class, 'show'])->name('show');
                Route::get('/{id}/edit',                [GalleryController::class, 'edit'])->name('edit');
                Route::put('/{id}',                     [GalleryController::class, 'update'])->name('update');
                Route::delete('/{id}',                  [GalleryController::class, 'destroy'])->name('destroy');

                // Custom routes
                Route::post('/bulk-upload',             [GalleryController::class, 'bulkUpload'])->name('bulk-upload');
                Route::post('/bulk-delete',             [GalleryController::class, 'bulkDelete'])->name('bulk-delete');
                Route::post('/sort-order',              [GalleryController::class, 'updateSortOrder'])->name('sort-order');
            });

            Route::prefix('account')->name('account.')->middleware(['web', 'auth'])->group(function () {
                Route::get('/', [AccountController::class, 'edit'])->name('edit');
                Route::put('/', [AccountController::class, 'update'])->name('update');
                Route::get('change-password', [AccountController::class, 'showChangePassword'])->name('change-password');
                Route::put('change-password', [AccountController::class, 'updatePassword'])->name('update-password');
                Route::get('change-email', [AccountController::class, 'showChangeEmail'])->name('change-email');
                Route::put('change-email', [AccountController::class, 'updateEmail'])->name('update-email');
            });


            // ── Pages ──
            Route::get('pages',                  [PageController::class, 'index'])->name('pages.index')->middleware('checkRole:superadmin,admin,manager,staff');
            Route::get('pages/create',           [PageController::class, 'create'])->name('pages.create')->middleware('checkRole:superadmin,admin,manager');
            Route::post('pages',                 [PageController::class, 'store'])->name('pages.store')->middleware('checkRole:superadmin,admin,manager');
            Route::get('pages/{id}/edit',        [PageController::class, 'edit'])->name('pages.edit')->middleware('checkRole:superadmin,admin,manager');
            Route::put('pages/{id}',             [PageController::class, 'update'])->name('pages.update')->middleware('checkRole:superadmin,admin,manager');
            Route::delete('pages/{id}',          [PageController::class, 'destroy'])->name('pages.destroy')->middleware('checkRole:superadmin,admin');
            Route::get('pages/trash',            [PageController::class, 'trash'])->name('pages.trash')->middleware('checkRole:superadmin,admin');
            Route::post('pages/{id}/restore',    [PageController::class, 'restore'])->name('pages.restore')->middleware('checkRole:superadmin,admin');
            Route::delete('pages/{id}/force',    [PageController::class, 'forceDelete'])->name('pages.force-delete')->middleware('checkRole:superadmin,admin');
            Route::post('pages/{id}/remove-image', [PageController::class, 'removeImage'])->name('pages.remove-image')->middleware('checkRole:superadmin,admin,manager');
            Route::post('pages/update-order', [PageController::class, 'updateOrder'])->name('pages.update-order')->middleware('checkRole:superadmin,admin,manager');


            Route::get('posts',                    [PostController::class, 'index'])->name('posts.index')->middleware('checkRole:superadmin,admin,manager,staff');
            Route::get('posts/create',             [PostController::class, 'create'])->name('posts.create')->middleware('checkRole:superadmin,admin,manager');
            Route::post('posts',                   [PostController::class, 'store'])->name('posts.store')->middleware('checkRole:superadmin,admin,manager');
            Route::get('posts/{id}/edit',          [PostController::class, 'edit'])->name('posts.edit')->middleware('checkRole:superadmin,admin,manager');
            Route::put('posts/{id}',               [PostController::class, 'update'])->name('posts.update')->middleware('checkRole:superadmin,admin,manager');
            Route::delete('posts/{id}',            [PostController::class, 'destroy'])->name('posts.destroy')->middleware('checkRole:superadmin,admin');
            Route::get('posts/trash',              [PostController::class, 'trash'])->name('posts.trash')->middleware('checkRole:superadmin,admin');
            Route::post('posts/{id}/restore',      [PostController::class, 'restore'])->name('posts.restore')->middleware('checkRole:superadmin,admin');
            Route::delete('posts/{id}/force',      [PostController::class, 'forceDelete'])->name('posts.force-delete')->middleware('checkRole:superadmin,admin');
            Route::post('posts/{id}/remove-image', [PostController::class, 'removeImage'])->name('posts.remove-image')->middleware('checkRole:superadmin,admin,manager');
            Route::post('posts/update-order',      [PostController::class, 'updateOrder'])->name('posts.update-order')->middleware('checkRole:superadmin,admin,manager');
            Route::post('posts/{id}/toggle-featured', [PostController::class, 'toggleFeatured'])->name('posts.toggle-featured')->middleware('checkRole:superadmin,admin,manager');
            Route::post('posts/{id}/toggle-status',   [PostController::class, 'toggleStatus'])->name('posts.toggle-status')->middleware('checkRole:superadmin,admin,manager');
            Route::post('posts/sort-order',           [PostController::class, 'sortOrder'])->name('posts.sort-order')->middleware('checkRole:superadmin,admin,manager');
            Route::post('posts/{id}/remove-image-2', [PostController::class, 'removeImage2'])->name('posts.remove-image-2')->middleware('checkRole:superadmin,admin,manager');
            // ── Notices ──
            // Route::get('notices',                      [NoticeController::class, 'index'])->name('notices.index')->middleware('checkRole:superadmin,admin,manager,staff');
            // Route::get('notices/create',               [NoticeController::class, 'create'])->name('notices.create')->middleware('checkRole:superadmin,admin,manager');
            // Route::post('notices',                     [NoticeController::class, 'store'])->name('notices.store')->middleware('checkRole:superadmin,admin,manager');
            // Route::get('notices/{id}/edit',            [NoticeController::class, 'edit'])->name('notices.edit')->middleware('checkRole:superadmin,admin,manager');
            // Route::get('notices/{id}',                 [NoticeController::class, 'show'])->name('notices.show')->middleware('checkRole:superadmin,admin,manager,staff');
            // Route::put('notices/{id}',                 [NoticeController::class, 'update'])->name('notices.update')->middleware('checkRole:superadmin,admin,manager');
            // Route::delete('notices/{id}',              [NoticeController::class, 'destroy'])->name('notices.destroy')->middleware('checkRole:superadmin,admin');
            // Route::post('notices/{id}/toggle-status',  [NoticeController::class, 'toggleStatus'])->name('notices.toggle-status')->middleware('checkRole:superadmin,admin,manager');
            // Route::post('notices/{id}/toggle-featured', [NoticeController::class, 'toggleFeatured'])->name('notices.toggle-featured')->middleware('checkRole:superadmin,admin,manager');
            // Route::post('notices/reorder',             [NoticeController::class, 'reorder'])->name('notices.reorder')->middleware('checkRole:superadmin,admin,manager');
            // Route::get('notices/check-slug',           [NoticeController::class, 'checkSlug'])->name('notices.checkSlug')->middleware('checkRole:superadmin,admin,manager');


            // ── Testimonials ──
            Route::get('testimonials',                       [TestimonialController::class, 'index'])->name('testimonials.index')->middleware('checkRole:superadmin,admin,manager,staff');
            Route::get('testimonials/create',                [TestimonialController::class, 'create'])->name('testimonials.create')->middleware('checkRole:superadmin,admin,manager');
            Route::post('testimonials',                      [TestimonialController::class, 'store'])->name('testimonials.store')->middleware('checkRole:superadmin,admin,manager');
            Route::get('testimonials/{id}/edit',             [TestimonialController::class, 'edit'])->name('testimonials.edit')->middleware('checkRole:superadmin,admin,manager');
            Route::get('testimonials/{id}', [TestimonialController::class, 'show'])->name('testimonials.show')->middleware('checkRole:superadmin,admin,manager,staff');
            Route::put('testimonials/{id}',                  [TestimonialController::class, 'update'])->name('testimonials.update')->middleware('checkRole:superadmin,admin,manager');
            Route::delete('testimonials/{id}',               [TestimonialController::class, 'destroy'])->name('testimonials.destroy')->middleware('checkRole:superadmin,admin');
            Route::get('testimonials/trash', [TestimonialController::class, 'trash'])->name('testimonials.trash')->middleware('checkRole:superadmin,admin');
            Route::post('testimonials/{id}/toggle-status',   [TestimonialController::class, 'toggleStatus'])->name('testimonials.toggle-status')->middleware('checkRole:superadmin,admin,manager');
            Route::post('testimonials/{id}/toggle-featured', [TestimonialController::class, 'toggleFeatured'])->name('testimonials.toggle-featured')->middleware('checkRole:superadmin,admin,manager');
            Route::post('testimonials/reorder',              [TestimonialController::class, 'reorder'])->name('testimonials.reorder')->middleware('checkRole:superadmin,admin,manager');
            Route::post('testimonials/update-order',         [TestimonialController::class, 'updateOrder'])->name('testimonials.update-order')->middleware('checkRole:superadmin,admin,manager');


            // // ── Events ──
            Route::get('events',                      [EventController::class, 'index'])->name('events.index')->middleware('checkRole:superadmin,admin,manager,staff');
            Route::get('events/create',               [EventController::class, 'create'])->name('events.create')->middleware('checkRole:superadmin,admin,manager');
            Route::post('events',                     [EventController::class, 'store'])->name('events.store')->middleware('checkRole:superadmin,admin,manager');
            Route::get('events/{id}/edit',            [EventController::class, 'edit'])->name('events.edit')->middleware('checkRole:superadmin,admin,manager');
            Route::put('events/{id}',                 [EventController::class, 'update'])->name('events.update')->middleware('checkRole:superadmin,admin,manager');
            Route::delete('events/{id}',              [EventController::class, 'destroy'])->name('events.destroy')->middleware('checkRole:superadmin,admin');
            Route::post('events/{id}/toggle-status',  [EventController::class, 'toggleStatus'])->name('events.toggle-status')->middleware('checkRole:superadmin,admin,manager');
            Route::post('events/{id}/toggle-featured', [EventController::class, 'toggleFeatured'])->name('events.toggle-featured')->middleware('checkRole:superadmin,admin,manager');
            Route::post('events/{id}/remove-image',   [EventController::class, 'removeImage'])->name('events.remove-image')->middleware('checkRole:superadmin,admin,manager');
            Route::post('events/reorder',             [EventController::class, 'reorder'])->name('events.reorder')->middleware('checkRole:superadmin,admin,manager');
            Route::get('events/check-slug',           [EventController::class, 'checkSlug'])->name('events.checkSlug')->middleware('checkRole:superadmin,admin,manager');


            // // ── FAQs ──
            // Route::get('faqs',                      [FaqController::class, 'index'])->name('faqs.index')->middleware('checkRole:superadmin,admin,manager,staff');
            // Route::get('faqs/create',               [FaqController::class, 'create'])->name('faqs.create')->middleware('checkRole:superadmin,admin,manager');
            // Route::post('faqs',                     [FaqController::class, 'store'])->name('faqs.store')->middleware('checkRole:superadmin,admin,manager');
            // Route::get('faqs/{id}/edit',            [FaqController::class, 'edit'])->name('faqs.edit')->middleware('checkRole:superadmin,admin,manager');
            // Route::put('faqs/{id}',                 [FaqController::class, 'update'])->name('faqs.update')->middleware('checkRole:superadmin,admin,manager');
            // Route::delete('faqs/{id}',              [FaqController::class, 'destroy'])->name('faqs.destroy')->middleware('checkRole:superadmin,admin');
            // Route::post('faqs/reorder',             [FaqController::class, 'reorder'])->name('faqs.reorder')->middleware('checkRole:superadmin,admin,manager');
            // Route::post('faqs/{id}/toggle-status',  [FaqController::class, 'toggleStatus'])->name('faqs.toggle-status')->middleware('checkRole:superadmin,admin,manager');
            // Route::post('faqs/{id}/toggle-featured', [FaqController::class, 'toggleFeatured'])->name('faqs.toggle-featured')->middleware('checkRole:superadmin,admin,manager');


            // // ── Clients ──
            // Route::get('clients',              [ClientController::class, 'index'])->name('clients.index')->middleware('checkRole:superadmin,admin,manager,staff');
            // Route::get('clients/create',       [ClientController::class, 'create'])->name('clients.create')->middleware('checkRole:superadmin,admin,manager');
            // Route::post('clients',             [ClientController::class, 'store'])->name('clients.store')->middleware('checkRole:superadmin,admin,manager');
            // Route::get('clients/{id}/edit',    [ClientController::class, 'edit'])->name('clients.edit')->middleware('checkRole:superadmin,admin,manager');
            // Route::put('clients/{id}',         [ClientController::class, 'update'])->name('clients.update')->middleware('checkRole:superadmin,admin,manager');
            // Route::delete('clients/{id}',      [ClientController::class, 'destroy'])->name('clients.destroy')->middleware('checkRole:superadmin,admin');
            // Route::post('clients/bulk-action', [ClientController::class, 'bulkAction'])->name('clients.bulk-action')->middleware('checkRole:superadmin,admin');
            // Route::post('clients/{id}/toggle', [ClientController::class, 'toggleStatus'])->name('clients.toggle')->middleware('checkRole:superadmin,admin,manager');
            // Route::post('clients/{id}/verify', [ClientController::class, 'verify'])->name('clients.verify')->middleware('checkRole:superadmin,admin');
            // Route::get('clients/export',       [ClientController::class, 'export'])->name('clients.export')->middleware('checkRole:superadmin,admin');
            // Route::delete('clients/{id}/remove-image', [ClientController::class, 'removeImage'])->name('clients.remove-image')->middleware('checkRole:superadmin,admin,manager');


            // // ── Menus ──
            // Route::get('menus',                          [MenuController::class, 'index'])->name('menus.index')->middleware('checkRole:superadmin,admin,manager,staff');
            // Route::get('menus/create',                   [MenuController::class, 'create'])->name('menus.create')->middleware('checkRole:superadmin,admin,manager');
            // Route::post('menus',                         [MenuController::class, 'store'])->name('menus.store')->middleware('checkRole:superadmin,admin,manager');
            // Route::get('menus/{menu}/edit',                [MenuController::class, 'edit'])->name('menus.edit')->middleware('checkRole:superadmin,admin,manager');
            // Route::put('menus/{menu}',                     [MenuController::class, 'update'])->name('menus.update')->middleware('checkRole:superadmin,admin,manager');
            // Route::delete('menus/{menu}',                  [MenuController::class, 'destroy'])->name('menus.destroy')->middleware('checkRole:superadmin,admin');
            // Route::post('menus/reorder',                 [MenuController::class, 'reorder'])->name('menus.reorder')->middleware('checkRole:superadmin,admin,manager');
            // Route::post('menus/{menu}/items',              [MenuController::class, 'addItem'])->name('menus.items.add')->middleware('checkRole:superadmin,admin,manager');
            // Route::put('menus/{menu}/items/{item}',        [MenuController::class, 'updateItem'])->name('menus.items.update')->middleware('checkRole:superadmin,admin,manager');
            // Route::delete('menus/{menu}/items/{item}',     [MenuController::class, 'deleteItem'])->name('menus.items.delete')->middleware('checkRole:superadmin,admin');
            // Route::get('menus/check-slug',               [MenuController::class, 'checkSlug'])->name('menus.checkSlug')->middleware('checkRole:superadmin,admin,manager');


            // ── Media ──
            Route::get('media',              [MediaController::class, 'index'])->name('media.index')->middleware('checkRole:superadmin,admin,manager,staff');
            Route::post('media',             [MediaController::class, 'store'])->name('media.store')->middleware('checkRole:superadmin,admin,manager,staff');
            Route::get('media/{id}/edit', [MediaController::class, 'edit'])->name('media.edit')->middleware('checkRole:superadmin,admin,manager,staff');
            Route::put('media/{id}',         [MediaController::class, 'update'])->name('media.update')->middleware('checkRole:superadmin,admin,manager,staff');
            Route::delete('media/{id}',      [MediaController::class, 'destroy'])->name('media.destroy')->middleware('checkRole:superadmin,admin');
            Route::get('media/{id}', [MediaController::class, 'show'])->name('media.show')->middleware('checkRole:superadmin,admin,manager,staff');
            Route::post('media/bulk-delete', [MediaController::class, 'bulkDelete'])->name('media.bulk-delete')->middleware('checkRole:superadmin,admin');

            Route::get('brochure', [BrochureController::class, 'index'])
                ->name('brochures.index');

            Route::post('brochure', [BrochureController::class, 'update'])
                ->name('brochures.update');

            // ── Contacts ──
            Route::get('contacts',                   [ContactController::class, 'index'])->name('contacts.index')->middleware('checkRole:superadmin,admin,manager,staff');
            Route::get('contacts/{contact}',              [ContactController::class, 'show'])->name('contacts.show')->middleware('checkRole:superadmin,admin,manager,staff');
            Route::post('contacts/{contact}/reply',       [ContactController::class, 'reply'])->name('contacts.reply')->middleware('checkRole:superadmin,admin,manager');
            Route::delete('contacts/{contact}',           [ContactController::class, 'destroy'])->name('contacts.destroy')->middleware('checkRole:superadmin,admin');
            Route::post('contacts/bulk-action',      [ContactController::class, 'bulkAction'])->name('contacts.bulk-action')->middleware('checkRole:superadmin,admin');
            Route::get('contacts/export/excel',      [ContactController::class, 'exportExcel'])->name('contacts.export.excel')->middleware('checkRole:superadmin,admin');
            Route::get('contacts/export/pdf',        [ContactController::class, 'exportPdf'])->name('contacts.export.pdf')->middleware('checkRole:superadmin,admin');


            // ── Pricings ──
            // Route::get('pricings',                  [PricingController::class, 'index'])->name('pricings.index')->middleware('checkRole:superadmin,admin,manager,staff');
            // Route::get('pricings/create',           [PricingController::class, 'create'])->name('pricings.create')->middleware('checkRole:superadmin,admin,manager');
            // Route::post('pricings',                 [PricingController::class, 'store'])->name('pricings.store')->middleware('checkRole:superadmin,admin,manager');
            // Route::get('pricings/{id}/edit',        [PricingController::class, 'edit'])->name('pricings.edit')->middleware('checkRole:superadmin,admin,manager');
            // Route::put('pricings/{id}',             [PricingController::class, 'update'])->name('pricings.update')->middleware('checkRole:superadmin,admin,manager');
            // Route::delete('pricings/{id}',          [PricingController::class, 'destroy'])->name('pricings.destroy')->middleware('checkRole:superadmin,admin');
            // Route::get('pricings/{id}/toggle-status', [PricingController::class, 'toggleStatus'])->name('pricings.toggle-status')->middleware('checkRole:superadmin,admin,manager');
            // Route::post('pricings/update-order',    [PricingController::class, 'updateOrder'])->name('pricings.update-order')->middleware('checkRole:superadmin,admin,manager');

            Route::resource('blog-categories', BlogCategoryController::class)->except(['show']);

            // Blogs
            Route::get('/blogs/trash',              [BlogController::class, 'trash'])->name('blogs.trash')->middleware('checkRole:superadmin,admin,manager');
            Route::post('/blogs/{id}/restore',      [BlogController::class, 'restore'])->name('blogs.restore')->middleware('checkRole:superadmin,admin,manager');
            Route::delete('/blogs/{id}/force',      [BlogController::class, 'forceDelete'])->name('blogs.force-delete')->middleware('checkRole:superadmin,admin,manager');
            Route::post('/blogs/{id}/toggle-status',  [BlogController::class, 'toggleStatus'])->name('blogs.toggle-status')->middleware('checkRole:superadmin,admin,manager');
            Route::post('/blogs/{id}/toggle-featured', [BlogController::class, 'toggleFeatured'])->name('blogs.toggle-featured')->middleware('checkRole:superadmin,admin,manager');
            Route::post('blogs/sort-order', [BlogController::class, 'updateSortOrder'])->name('blogs.sort-order')->middleware('checkRole:superadmin,admin,manager');
            Route::post('/blogs/{id}/remove-image',   [BlogController::class, 'removeImage'])->name('blogs.remove-image')->middleware('checkRole:superadmin,admin,manager');
            Route::resource('blogs', BlogController::class)->except(['show']);


            // ── Counters ──
            Route::get('counters',                   [CounterController::class, 'index'])->name('counters.index')->middleware('checkRole:superadmin,admin,manager,staff');
            Route::get('counters/create',            [CounterController::class, 'create'])->name('counters.create')->middleware('checkRole:superadmin,admin,manager');
            Route::post('counters',                  [CounterController::class, 'store'])->name('counters.store')->middleware('checkRole:superadmin,admin,manager');
            Route::get('counters/{id}/edit',         [CounterController::class, 'edit'])->name('counters.edit')->middleware('checkRole:superadmin,admin,manager');
            Route::put('counters/{id}',              [CounterController::class, 'update'])->name('counters.update')->middleware('checkRole:superadmin,admin,manager');
            Route::delete('counters/{id}',           [CounterController::class, 'destroy'])->name('counters.destroy')->middleware('checkRole:superadmin,admin');
            Route::post('counters/reorder',          [CounterController::class, 'reorder'])->name('counters.reorder')->middleware('checkRole:superadmin,admin,manager');
            Route::post('counters/{id}/toggle-status', [CounterController::class, 'toggleStatus'])->name('counters.toggle-status')->middleware('checkRole:superadmin,admin,manager');


            // ── Email Settings (superadmin only) ──
            Route::middleware('checkRole:superadmin')->group(function () {
                Route::get('email-settings',                    [EmailSettingController::class, 'index'])->name('email-settings.index');
                Route::get('email-settings/create',             [EmailSettingController::class, 'create'])->name('email-settings.create');
                Route::post('email-settings',                   [EmailSettingController::class, 'store'])->name('email-settings.store');
                Route::get('email-settings/{id}/edit',          [EmailSettingController::class, 'edit'])->name('email-settings.edit');
                Route::put('email-settings/{id}',               [EmailSettingController::class, 'update'])->name('email-settings.update');
                Route::delete('email-settings/{id}',            [EmailSettingController::class, 'destroy'])->name('email-settings.destroy');
                Route::post('email-settings/{id}/set-active',   [EmailSettingController::class, 'setActive'])->name('email-settings.set-active');
                Route::post('email-settings/{id}/write-env',    [EmailSettingController::class, 'writeToEnv'])->name('email-settings.write-env');
                Route::post('email-settings/{id}/test',         [EmailSettingController::class, 'testEmail'])->name('email-settings.test');
            });


            // ── Site Settings (superadmin + admin only) ──
            Route::prefix('settings')->name('settings.')->middleware('checkRole:superadmin,admin')->group(function () {
                Route::get('/general',        [SiteSettingController::class, 'general'])->name('general');
                Route::post('/general',       [SiteSettingController::class, 'updateGeneral'])->name('general.update');
                Route::get('/social',         [SiteSettingController::class, 'social'])->name('social');
                Route::post('/social',        [SiteSettingController::class, 'updateSocial'])->name('social.update');
                Route::get('/seo',            [SiteSettingController::class, 'seo'])->name('seo');
                Route::post('/seo',           [SiteSettingController::class, 'updateSeo'])->name('seo.update');
                Route::get('/scripts',        [SiteSettingController::class, 'scripts'])->name('scripts');
                Route::post('/scripts',       [SiteSettingController::class, 'updateScripts'])->name('scripts.update');
                Route::get('/footer',         [SiteSettingController::class, 'footer'])->name('footer');
                Route::post('/footer',        [SiteSettingController::class, 'updateFooter'])->name('footer.update');
                Route::post('/reset/{group}', [SiteSettingController::class, 'reset'])->name('reset');
                Route::post('/social/store', [SiteSettingController::class, 'storeSocial'])
                    ->name('social.store');
                Route::delete('social/{key}', [SiteSettingController::class, 'destroySocial'])
                    ->name('social.destroy');
            });
        });
});
