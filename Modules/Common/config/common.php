<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Common Module Models
    |--------------------------------------------------------------------------
    |
    | Central place to store references to all Common module models.
    | You can use these references in services, repositories, or controllers.
    |
    */

    'models' => [
        'user'         => Modules\Common\Entities\User::class,
        'site_setting' => Modules\Common\Entities\SiteSetting::class,
        'page'         => Modules\Common\Entities\Page::class,
        'media'        => Modules\Common\Entities\Media::class,
        'event'        => Modules\Common\Entities\Event::class,
        'notice'       => Modules\Common\Entities\Notice::class,
        'testimonial'  => Modules\Common\Entities\Testimonial::class,
        'service'      => Modules\Common\Entities\Service::class,
        'contact'      => Modules\Common\Entities\Contact::class,
        'banner'       => Modules\Common\Entities\Banner::class,
        'menu'         => Modules\Common\Entities\Menu::class,
    ],

];
