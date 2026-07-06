<?php

namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'content',
        'image',
        'location',
        'venue',
        'start_date',
        'end_date',
        'organizer',
        'contact_email',
        'contact_phone',
        'registration_url',
        'type',
        'status',
        'is_featured',
        'order',
    ];

    protected $casts = [
        'start_date'  => 'datetime',
        'end_date'    => 'datetime',
        'is_featured' => 'boolean',
    ];

    // slug lai auto generate garxa.
    public static function generateSlug($title, $excludeId = null)
    {
        $slug = Str::slug($title);

        $query = self::where('slug', $slug);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $count = $query->count();

        if ($count > 0) {
            $newSlug = $slug . '-' . ($count + 1);

            // Ensure the new slug is also unique
            while (self::where('slug', $newSlug)->when($excludeId, function ($q) use ($excludeId) {
                $q->where('id', '!=', $excludeId);
            })->exists()) {
                $count++;
                $newSlug = $slug . '-' . ($count + 1);
            }

            return $newSlug;
        }

        return $slug;
    }

    // Check if upcoming
    public function isUpcoming(): bool
    {
        return $this->start_date->isFuture();
    }

    // Check if ongoing
    public function isOngoing(): bool
    {
        return $this->start_date->isPast()
            && ($this->end_date === null || $this->end_date->isFuture());
    }

    // Check if past
    public function isPast(): bool
    {
        return $this->end_date
            ? $this->end_date->isPast()
            : $this->start_date->isPast();
    }

    // Get status label
    public function getEventStatusAttribute(): string
    {
        if ($this->isUpcoming()) return 'upcoming';
        if ($this->isOngoing())  return 'ongoing';
        return 'past';
    }

    // Get badge color
    public function getEventStatusBadgeAttribute(): string
    {
        return match ($this->event_status) {
            'upcoming' => 'primary',
            'ongoing'  => 'success',
            'past'     => 'secondary',
            default    => 'secondary',
        };
    }
}
