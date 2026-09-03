<?php

namespace Modules\Common\Services;

use Modules\Common\Repositories\ApplicationRepositoryInterface;
use Illuminate\Support\Facades\Mail;
use Modules\Common\Entities\EmailSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class ApplicationService
{
    protected $repository;

    public function __construct(ApplicationRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getPaginatedApplications(array $filters = [])
    {
        return $this->repository->paginate(15, $filters);
    }

    public function getApplicationById(int $id)
    {
        $application = $this->repository->findById($id);

        if ($application->status === 'unread') {
            $this->repository->markAsRead($id);
            $application->status = 'read';
        }

        return $application;
    }

    public function createApplication(array $data)
    {
        $application = $this->repository->create($data);

        $this->notifyAdmin($application);

        return $application;
    }

    public function updateApplication(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function deleteApplication(int $id)
    {
        return $this->repository->delete($id);
    }

    public function replyToApplication(int $id, string $reply)
    {
        $application = $this->repository->findById($id);

        try {
            $data = [
                'replyName'    => $application->student_name,
                'reply'        => $reply,
                'replySubject' => 'your admission application',
                'year'         => now()->format('Y'),
            ];

            Mail::send('common::emails.contact-reply', $data, function ($mail) use ($application) {
                $mail->to($application->email, $application->guardian_name)
                    ->subject('Re: Your Admission Application');
            });
        } catch (\Exception $e) {
            Log::error('Application reply email failed: ' . $e->getMessage());
        }

        return $this->repository->reply($id, $reply);
    }

    public function getStats()
    {
        return $this->repository->getStats();
    }

    public function getUnreadCount()
    {
        return $this->repository->getUnreadCount();
    }

    public function bulkDelete(array $ids)
    {
        return $this->repository->bulkDelete($ids);
    }

    public function bulkMarkRead(array $ids)
    {
        return $this->repository->bulkMarkRead($ids);
    }

    private function notifyAdmin($application)
    {
        try {
            $setting = EmailSetting::getActive();

            if (!$setting || empty($setting->admin_mail)) {
                return;
            }

            $this->applyMailConfig($setting);

            $siteSettings = app(\Modules\Common\Services\SiteSettingService::class);

            $logoPath = $siteSettings->getByKey('site_logo');
            $logoAbsolutePath = $logoPath ? \Illuminate\Support\Facades\Storage::disk('public')->path($logoPath) : null;
            $logoExists = $logoAbsolutePath && file_exists($logoAbsolutePath);

            $subjectLine = 'New Admission Application from ' . $application->student_name;

            $data = [
                'contact'      => $application,
                'name'         => $application->student_name,
                'initial'      => strtoupper(mb_substr($application->first_name, 0, 1)),
                'email'        => $application->email,
                'phone'        => $application->phone ?? '—',
                'subject'      => 'Admission Application — ' . $application->applying_for,
                'date'         => now_np()->format('d M Y'),
                'time'         => now_np()->format('h:i A'),
                'sentAt'       => now_np()->format('d M Y, h:i A'),
                'userMessage'  => "Guardian: {$application->guardian_name}\nAddress: {$application->address}\nDOB: {$application->dob->format('d M Y')}\nGender: {$application->gender}",
                'dashboardUrl' => url('/admin/applications'),
                'year'         => now_np()->format('Y'),
                'isBooking'    => false,
                'formLabel'    => 'Admission Application',
                'subjectLine'  => $subjectLine,
                'badgeColor'   => '#e0f2fe',
                'badgeText'    => '#0369a1',
                'badgeBorder'  => '#bae6fd',
                'badgeLabel'   => 'Application',
                'siteName'     => $siteSettings->getByKey('site_name', 'Ambassador Academy'),
            ];

            $toEmails = array_filter(array_map('trim', explode(',', $setting->admin_mail ?? '')));
            $ccEmails = array_filter(array_map('trim', explode(',', $setting->cc_mail ?? '')));
            $bccEmails = array_filter(array_map('trim', explode(',', $setting->bcc_mail ?? '')));

            Mail::send('common::emails.admin-notification', $data, function ($mail) use (
                $setting,
                $subjectLine,
                $toEmails,
                $ccEmails,
                $bccEmails,
                $logoExists,
                $logoAbsolutePath,
            ) {
                $mail->to($toEmails)
                    ->from($setting->from_address, $setting->from_name)
                    ->subject($subjectLine);

                if (!empty($ccEmails)) {
                    $mail->cc($ccEmails);
                }

                if (!empty($bccEmails)) {
                    $mail->bcc($bccEmails);
                }

                if ($logoExists) {
                    $mail->embed($logoAbsolutePath, 'site-logo');
                }
            });
        } catch (\Exception $e) {
            Log::warning('Application admin notification failed: ' . $e->getMessage());
        }
    }

    private function applyMailConfig($setting)
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
}
