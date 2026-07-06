<?php


namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        'name',
        'file_name',
        'file_path',
        'file_size',
        'mime_type',
        'extension',
        'type',
        'width',
        'height',
        'alt_text',
        'title',
        'description',
        'tags'
    ];

    protected $casts = [
        'tags' => 'array',
        'file_size' => 'integer',
        'width' => 'integer',
        'height' => 'integer'
    ];

    public function getFormattedSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
}
