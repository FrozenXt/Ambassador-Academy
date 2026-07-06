<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Modules\Common\Repositories\EmailSettingRepositoryInterface;

class ApplyMailConfig
{
    protected $repo;

    public function __construct(EmailSettingRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function handle(Request $request, Closure $next)
    {
        try {
            $active = $this->repo->getActive();

            if ($active) {
                Config::set('mail.default',                 $active->mailer);
                Config::set('mail.mailers.smtp.host',       $active->host);
                Config::set('mail.mailers.smtp.port',       $active->port);
                Config::set('mail.mailers.smtp.username',   $active->username);
                Config::set('mail.mailers.smtp.password',   $active->password);
                Config::set('mail.mailers.smtp.encryption', $active->encryption);
                Config::set('mail.from.address',            $active->from_address);
                Config::set('mail.from.name',               $active->from_name);
            }
        } catch (\Exception $e) {
            // fail silently — don't break the app if DB is unavailable
        }

        return $next($request);
    }
}
