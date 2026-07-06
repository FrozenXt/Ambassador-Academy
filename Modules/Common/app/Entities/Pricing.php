<?php


namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Pricing extends Model
{
    use SoftDeletes;

    protected $table = 'pricings';

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'price',
        'currency',
        'period',
        'features',
        'is_popular',
        'sort_order',
        'is_active',
        'button_text',
        'button_url',
    ];

    protected $casts = [
        'features' => 'array',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pricing) {
            if (empty($pricing->slug)) {
                $pricing->slug = Str::slug($pricing->name);
            }
        });

        static::updating(function ($pricing) {
            if ($pricing->isDirty('name')) {
                $pricing->slug = Str::slug($pricing->name);
            }
        });
    }

    public function getFormattedPriceAttribute()
    {
        return $this->currency . number_format($this->price, 0);
    }

    public function getFeaturesListAttribute()
    {
        return is_array($this->features) ? $this->features : [];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
    }
}
