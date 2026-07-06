<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Modules\Common\Entities\EmailSetting;

class EmailSettingBootProvider extends ServiceProvider
{
    public function boot()
    {
        try {
            if (Schema::hasTable('email_settings')) {
                $setting = EmailSetting::getActive();

                if ($setting) {
                    Config::set('mail.default',               $setting->mailer);
                    Config::set('mail.mailers.smtp.host',     $setting->host);
                    Config::set('mail.mailers.smtp.port',     $setting->port);
                    Config::set('mail.mailers.smtp.username', $setting->username);
                    Config::set('mail.mailers.smtp.password', $setting->password);
                    Config::set('mail.mailers.smtp.encryption', $setting->encryption);
                    Config::set('mail.from.address',          $setting->from_address);
                    Config::set('mail.from.name',             $setting->from_name);
                }
            }
        } catch (\Exception $e) {
            // Silently fail if DB not ready
        }
    }
}
