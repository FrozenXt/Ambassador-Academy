<?php

namespace Modules\Common\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;
use Modules\Common\Repositories\AlbumRepositoryInterface;
use Modules\Common\Repositories\AlbumRepository;
use Modules\Common\Services\AlbumServiceInterface;
use Modules\Common\Services\AlbumService;
use Modules\Common\Repositories\GalleryRepositoryInterface;
use Modules\Common\Repositories\GalleryRepository;
use Modules\Common\Services\GalleryServiceInterface;
use Modules\Common\Services\GalleryService;
use Illuminate\Console\Scheduling\Schedule;

class CommonServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Common';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'common';

    /**
     * Command classes to register.
     *
     * @var string[]
     */
    // protected array $commands = [];

    /**
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    public function register(): void
    {
        parent::register();


        // Contact
        $this->app->bind(
            \Modules\Common\Repositories\ContactRepositoryInterface::class,
            \Modules\Common\Repositories\ContactRepository::class
        );

        // Service Repository
        $this->app->bind(
            \Modules\Common\Repositories\ServiceRepositoryInterface::class,
            \Modules\Common\Repositories\ServiceRepository::class
        );

        // Service Service
        $this->app->bind(
            \Modules\Common\Services\ServiceServiceInterface::class,
            \Modules\Common\Services\ServiceService::class
        );
        // Media service ko lagi
        $this->app->bind(
            \Modules\Common\Repositories\MediaRepositoryInterface::class,
            \Modules\Common\Repositories\MediaRepository::class
        );
        // Page Service ko lagi
        $this->app->bind(
            \Modules\Common\Repositories\PageRepositoryInterface::class,
            \Modules\Common\Repositories\PageRepository::class
        );
        //Testimonials Service ko lagi
        $this->app->bind(
            \Modules\Common\Repositories\TestimonialRepositoryInterface::class,
            \Modules\Common\Repositories\TestimonialRepository::class
        );
        // Event Service ko lagi
        $this->app->bind(
            \Modules\Common\Repositories\EventRepositoryInterface::class,
            \Modules\Common\Repositories\EventRepository::class
        );
        $this->app->bind(
            \Modules\Common\Repositories\NoticeRepositoryInterface::class,
            \Modules\Common\Repositories\NoticeRepository::class
        );
        $this->app->bind(
            \Modules\Common\Repositories\SiteSettingRepositoryInterface::class,
            \Modules\Common\Repositories\SiteSettingRepository::class
        );
        $this->app->bind(
            \Modules\Common\Repositories\CategoryRepositoryInterface::class,
            \Modules\Common\Repositories\CategoryRepository::class
        );

        $this->app->bind(
            \Modules\Common\Repositories\ProductRepositoryInterface::class,
            \Modules\Common\Repositories\ProductRepository::class
        );
        $this->app->bind(
            \Modules\Common\Repositories\ClientRepositoryInterface::class,
            \Modules\Common\Repositories\ClientRepository::class
        );
        $this->app->bind(
            \Modules\Common\Repositories\FaqRepositoryInterface::class,
            \Modules\Common\Repositories\FaqRepository::class
        );
        $this->app->bind(
            \Modules\Common\Repositories\CounterRepositoryInterface::class,
            \Modules\Common\Repositories\CounterRepository::class
        );
        $this->app->bind(
            \Modules\Common\Repositories\PricingRepositoryInterface::class,
            \Modules\Common\Repositories\PricingRepository::class
        );
        $this->app->bind(
            \Modules\Common\Repositories\EmailSettingRepositoryInterface::class,
            \Modules\Common\Repositories\EmailSettingRepository::class
        );
        $this->app->bind(AlbumRepositoryInterface::class, AlbumRepository::class);
        $this->app->bind(AlbumServiceInterface::class, AlbumService::class);


        $this->app->bind(GalleryServiceInterface::class, GalleryService::class);
        $this->app->bind(GalleryRepositoryInterface::class, GalleryRepository::class);

        $this->app->bind(
            \Modules\Common\Repositories\BlogRepositoryInterface::class,
            \Modules\Common\Repositories\BlogRepository::class
        );

        // Blog Category
        $this->app->bind(
            \Modules\Common\Repositories\BlogCategoryRepositoryInterface::class,
            \Modules\Common\Repositories\BlogCategoryRepository::class
        );
    }
    public function boot(): void
    {
        parent::boot();

        // Load migrations from Common module
        $this->loadMigrationsFrom(module_path('Common', 'database/migrations'));
    }
}
