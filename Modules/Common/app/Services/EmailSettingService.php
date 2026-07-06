<?php

namespace Modules\Common\Services;

use Modules\Common\Repositories\EmailSettingRepositoryInterface;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;

class EmailSettingService
{
    protected $repository;

    public function __construct(EmailSettingRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function findById(int $id)
    {
        return $this->repository->findById($id);
    }

    public function getActive()
    {
        return $this->repository->getActive();
    }

    public function create(array $data)
    {
        if (!empty($data['is_active'])) {
            $this->repository->getAll()->each(function ($s) {
                $s->update(['is_active' => false]);
            });
        }

        $data['is_active'] = !empty($data['is_active']);


        $data['cc_mail'] = $data['cc_mail'] ?? null;
        $data['bcc_mail'] = $data['bcc_mail'] ?? null;

        return $this->repository->create($data);
    }

    public function update(int $id, array $data)
    {
        if (!empty($data['is_active'])) {
            $this->getAll()->each(function ($s) use ($id) {
                if ($s->id !== $id) {
                    $s->update(['is_active' => false]);
                }
            });
        }

        $data['is_active'] = !empty($data['is_active']);

        if (empty($data['password'])) {
            unset($data['password']);
        }


        $data['cc_mail'] = $data['cc_mail'] ?? null;
        $data['bcc_mail'] = $data['bcc_mail'] ?? null;

        return $this->repository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }

    public function setActive(int $id)
    {
        $setting = $this->repository->setActive($id);
        $this->applyToConfig($setting);
        return $setting;
    }

    public function sendTestEmail(int $id, string $toEmail)
    {
        $setting = $this->repository->findById($id);

        $this->applyToConfig($setting);

        try {
            $data = [
                'mailerLabel' => $setting->mailer_label,
                'host'        => $setting->host,
                'port'        => $setting->port,
                'fromAddress' => $setting->from_address,
                'fromName'    => $setting->from_name,
                'toEmail'     => $toEmail,
                'sentAt'      => now()->format('d M Y, h:i A'),
                'year'        => now()->format('Y'),
            ];

            Mail::send('common::emails.test-email', $data, function ($mail) use ($toEmail, $setting) {
                $mail->to($toEmail)
                    ->from($setting->from_address, $setting->from_name)
                    ->subject('Test Email from ' . $setting->from_name);
            });

            return ['success' => true, 'message' => 'Test email sent successfully to ' . $toEmail];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed: ' . $e->getMessage()];
        }
    }

    public function applyToConfig(object $setting)
    {
        Config::set('mail.default',                 $setting->mailer);
        Config::set('mail.mailers.smtp.host',       $setting->host);
        Config::set('mail.mailers.smtp.port',       $setting->port);
        Config::set('mail.mailers.smtp.username',   $setting->username);
        Config::set('mail.mailers.smtp.password',   $setting->password);
        Config::set('mail.mailers.smtp.encryption', $setting->encryption);
        Config::set('mail.from.address',            $setting->from_address);
        Config::set('mail.from.name',               $setting->from_name);
    }

    public function writeToEnv(int $id)
    {
        $setting = $this->repository->findById($id);
        $envPath = base_path('.env');

        $envData = [
            'MAIL_MAILER'       => $setting->mailer,
            'MAIL_HOST'         => $setting->host,
            'MAIL_PORT'         => $setting->port,
            'MAIL_USERNAME'     => $setting->username,
            'MAIL_PASSWORD'     => $setting->password,
            'MAIL_ENCRYPTION'   => $setting->encryption,
            'MAIL_FROM_ADDRESS' => '"' . $setting->from_address . '"',
            'MAIL_FROM_NAME'    => '"' . $setting->from_name . '"',
        ];

        $envContent = file_get_contents($envPath);

        foreach ($envData as $key => $value) {
            $pattern     = '/^' . $key . '=.*/m';
            $replacement = $key . '=' . $value;

            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, $replacement, $envContent);
            } else {
                $envContent .= "\n" . $replacement;
            }
        }

        file_put_contents($envPath, $envContent);
        Artisan::call('config:clear');

        return true;
    }
}
