<?php

namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class EmailSetting extends Model
{
    protected $table = 'email_settings';

    protected $fillable = [
        'mailer',
        'host',
        'port',
        'username',
        'password',
        'encryption',
        'from_address',
        'from_name',
        'admin_mail',
        'cc_mail',
        'bcc_mail',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'port'      => 'integer',
    ];

    protected $hidden = ['password'];

    // Encrypt password before saving
    public function setPasswordAttribute(string $value)
    {
        $this->attributes['password'] = Crypt::encryptString($value);
    }

    // Decrypt password when reading
    public function getPasswordAttribute(string $value): string
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return '';
        }
    }

    // Get active setting
    public static function getActive(): ?self
    {
        return static::where('is_active', true)->latest()->first();
    }

    // Mailer label
    public function getMailerLabelAttribute(): string
    {
        return match ($this->mailer) {
            'smtp'     => 'SMTP',
            'sendmail' => 'Sendmail',
            'mailgun'  => 'Mailgun',
            'ses'      => 'Amazon SES',
            'postmark' => 'Postmark',
            default    => strtoupper($this->mailer),
        };
    }

    // Encryption badge color
    public function getEncryptionBadgeAttribute(): string
    {
        return match ($this->encryption) {
            'tls'  => 'success',
            'ssl'  => 'warning',
            'none' => 'danger',
            default => 'secondary',
        };
    }
}
