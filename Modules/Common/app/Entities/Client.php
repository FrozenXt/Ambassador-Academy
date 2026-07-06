<?php

namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Client extends Model
{
    use SoftDeletes;

    protected $table = 'clients';

    protected $fillable = [
        'client_code',
        'name',
        'company_name',
        'email',
        'phone',
        'mobile',
        'website',
        'tax_number',
        'registration_number',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'country',
        'contact_person_name',
        'contact_person_email',
        'contact_person_phone',
        'contact_person_designation',
        'industry_type',
        'employee_count',
        'annual_revenue',
        'currency',
        'assigned_to',
        'client_type',
        'payment_terms',
        'credit_limit',
        'is_active',
        'is_verified',
        'verified_at',
        'notes',
        'preferences',
        'social_media',
        'image',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'is_verified'   => 'boolean',
        'verified_at'   => 'datetime',
        'annual_revenue' => 'decimal:2',
        'credit_limit'  => 'decimal:2',
        'preferences'   => 'array',
        'social_media'  => 'array',
    ];

    // ── Auto generate client code ──
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($client) {
            if (empty($client->client_code)) {
                $client->client_code = 'CLT-' . strtoupper(Str::random(6));
            }
        });
    }

    // ── Relationships ──
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // ── Scopes ──
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeVerified(Builder $query): Builder
    {
        return $query->where('is_verified', true);
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name',         'like', '%' . $term . '%')
                ->orWhere('company_name', 'like', '%' . $term . '%')
                ->orWhere('email',       'like', '%' . $term . '%')
                ->orWhere('client_code', 'like', '%' . $term . '%')
                ->orWhere('phone',       'like', '%' . $term . '%')
                ->orWhere('city',        'like', '%' . $term . '%')
                ->orWhere('country',     'like', '%' . $term . '%');
        });
    }

    // ── Accessors ──
    public function getDisplayNameAttribute(): string
    {
        return $this->company_name ?? $this->name;
    }

    public function getInitialsAttribute(): string
    {
        $name  = $this->company_name ?? $this->name;
        $words = explode(' ', $name);
        return strtoupper(
            collect($words)->take(2)->map(fn($w) => substr($w, 0, 1))->join('')
        );
    }

    public function getFullAddressAttribute(): string
    {
        return collect([
            $this->address_line1,
            $this->address_line2,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ])->filter()->join(', ');
    }

    public function getClientTypeLabelAttribute(): string
    {
        return match ($this->client_type) {
            'individual'  => 'Individual',
            'business'    => 'Business',
            'government'  => 'Government',
            'nonprofit'   => 'Non-Profit',
            default       => ucfirst($this->client_type),
        };
    }

    public function getClientTypeBadgeAttribute(): string
    {
        return match ($this->client_type) {
            'individual'  => 'primary',
            'business'    => 'info',
            'government'  => 'warning',
            'nonprofit'   => 'success',
            default       => 'secondary',
        };
    }

    public function getPaymentTermsLabelAttribute(): string
    {
        return match ($this->payment_terms) {
            'immediate' => 'Immediate',
            'net_7'     => 'Net 7 Days',
            'net_15'    => 'Net 15 Days',
            'net_30'    => 'Net 30 Days',
            'net_60'    => 'Net 60 Days',
            default     => ucfirst($this->payment_terms),
        };
    }
}
