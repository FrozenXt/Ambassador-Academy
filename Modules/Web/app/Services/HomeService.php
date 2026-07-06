<?php

namespace Modules\Web\Services;

use Modules\Common\Services\AlbumService;
use Modules\Common\Services\CounterService;
use Modules\Common\Services\BlogService;

class HomeService
{
    protected $albumService;
    protected $counterService;
    protected $blogService;

    public function __construct(
        AlbumService $albumService,
        CounterService $counterService,
        BlogService $blogService
    ) {
        $this->albumService = $albumService;
        $this->counterService = $counterService;
        $this->blogService = $blogService;
    }

    public function getHomeData(): array
    {
        // Section 1
        $section1 = $this->albumService->getByCodeWithGalleries('intro');
        $section1Sub1 = $section1?->galleries->get(0);
        $section1Sub2 = $section1?->galleries->get(1);

        // Counter
        $counterAlbum = $this->albumService->getByCodeWithGalleries('counter');
        $counters = $this->counterService->getAllOrdered();
        $counterGallery = $counterAlbum?->galleries?->first();

        // Comfort
        $section3 = $this->albumService->getByCodeWithGalleries('comfort');
        $section3Sub1 = $section3?->galleries->get(0);
        $section3Sub2 = $section3?->galleries->get(1);

        // Safety
        $safetyAlbum = $this->albumService->getByCodeWithGalleries('safety');
        $section4Sub1 = $safetyAlbum?->galleries->get(0);
        $section4Sub2 = $safetyAlbum?->galleries->get(1);

        // Features
        $section5 = $this->albumService->getByCodeWithGalleries('features');
        $section5Sub1 = $section5?->galleries[0] ?? null;
        $section5Sub2 = $section5?->galleries[1] ?? null;

        // Landing
        $landingHero = $this->albumService->getLatestGalleryByAlbumCode('landing');

        // Color picker
        $exteriorAlbum = $this->albumService->getByCodeWithGalleries('exterior');
        $interiorAlbum = $this->albumService->getByCodeWithGalleries('interior');

        // Blogs
        $recentBlogs = $this->blogService->getRecentBlogs(3);
        $blog = $this->blogService->getLatestPublished();

        return compact(
            'section1',
            'section1Sub1',
            'section1Sub2',
            'counters',
            'counterGallery',
            'section3',
            'section3Sub1',
            'section3Sub2',
            'safetyAlbum',
            'section4Sub1',
            'section4Sub2',
            'section5',
            'section5Sub1',
            'section5Sub2',
            'landingHero',
            'exteriorAlbum',
            'interiorAlbum',
            'recentBlogs',
            'blog'
        );
    }
}
