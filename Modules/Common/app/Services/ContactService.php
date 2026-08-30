<?php

namespace Modules\Common\Services;

use Modules\Common\Repositories\ContactRepositoryInterface;
use Illuminate\Support\Facades\Mail;
use Modules\Common\Entities\EmailSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class ContactService
{
    protected $repository;

    public function __construct(ContactRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getPaginatedContacts(array $filters = [])
    {
        return $this->repository->paginate(15, $filters);
    }

    public function getContactById(int $id)
    {
        $contact = $this->repository->findById($id);

        if ($contact->status === 'unread') {
            $this->repository->markAsRead($id);
            $contact->status = 'read';
        }

        return $contact;
    }

    public function createContact(array $data)
    {
        $data['name']    = trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));
        $data['message'] = $data['message'] ?? '';

        $contact = $this->repository->create($data);

        $this->notifyAdmin($contact);

        return $contact;
    }

    public function updateContact(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function deleteContact(int $id)
    {
        return $this->repository->delete($id);
    }

    public function replyToContact(int $id, string $reply)
    {
        $contact = $this->repository->findById($id);

        try {
            $data = [
                'replyName'    => $contact->display_name ?? trim($contact->first_name . ' ' . $contact->last_name),
                'reply'        => $reply,
                'replySubject' => $contact->subject ?? 'your inquiry',
                'year'         => now()->format('Y'),
            ];

            Mail::send('common::emails.contact-reply', $data, function ($mail) use ($contact) {
                $mail->to($contact->email, $contact->display_name ?? trim($contact->first_name . ' ' . $contact->last_name))
                    ->subject('Re: ' . ($contact->subject ?? 'Your inquiry'));
            });
        } catch (\Exception $e) {
            Log::error('Contact reply email failed: ' . $e->getMessage());
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

    private function notifyAdmin($contact)
    {
        try {
            $setting = EmailSetting::getActive();

            if (!$setting || empty($setting->admin_mail)) {
                return;
            }

            $this->applyMailConfig($setting);

            $isBooking = !empty($contact->model);

            if ($isBooking) {
                $subjectLine = 'New Booking Request from ' . trim($contact->first_name . ' ' . $contact->last_name);
                $badgeColor  = '#fef3c7';
                $badgeText   = '#b45309';
                $badgeBorder = '#fde68a';
                $badgeLabel  = 'Booking';
                $formLabel   = 'Book Now Form';
            } else {
                $subjectLine = 'New Enquiry from ' . trim($contact->first_name . ' ' . $contact->last_name);
                $badgeColor  = '#fff1f2';
                $badgeText   = '#be123c';
                $badgeBorder = '#fecdd3';
                $badgeLabel  = 'Enquiry';
                $formLabel   = 'Enquiry Form';
            }

            $siteSettings = app(\Modules\Common\Services\SiteSettingService::class);

            // Get the actual local file path for embedding (not a URL)
            $logoPath = $siteSettings->getByKey('site_logo');
            $logoAbsolutePath = $logoPath ? \Illuminate\Support\Facades\Storage::disk('public')->path($logoPath) : null;
            $logoExists = $logoAbsolutePath && file_exists($logoAbsolutePath);

            $data = [
                'contact'      => $contact,
                'name'         => $contact->display_name ?? trim($contact->first_name . ' ' . $contact->last_name),
                'initial'      => strtoupper(mb_substr($contact->display_name ?? $contact->first_name, 0, 1)),
                'email'        => $contact->email,
                'phone'        => $contact->phone ?? '—',
                'subject'      => $contact->subject ?? 'General Enquiry',
                'date'         => now_np()->format('d M Y'),
                'time'         => now_np()->format('h:i A'),
                'sentAt'       => now_np()->format('d M Y, h:i A'),
                'userMessage'  => $contact->message ?? '',
                'dashboardUrl' => url('/admin/contacts'),
                'year'         => now_np()->format('Y'),
                'isBooking'    => $isBooking,
                'formLabel'    => $formLabel,
                'subjectLine'  => $subjectLine,
                'badgeColor'   => $badgeColor,
                'badgeText'    => $badgeText,
                'badgeBorder'  => $badgeBorder,
                'badgeLabel'   => $badgeLabel,
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

                // Embed logo as inline attachment — works regardless of APP_URL/localhost
                if ($logoExists) {
                    $mail->embed($logoAbsolutePath, 'site-logo');
                }
            });
        } catch (\Exception $e) {
            Log::warning('Contact admin notification failed: ' . $e->getMessage());
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
