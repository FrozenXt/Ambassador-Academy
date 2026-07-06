<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Common\Entities\Gallery;
use Modules\Common\Entities\Album;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Modules\Admin\Http\Requests\GalleryRequest;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $albumId = $request->input('album_id');
        $perPage = $request->input('per_page', 15);

        $query = Gallery::with('album')->ordered();

        if ($albumId) {
            $query->where('album_id', $albumId);
        }

        $gallery = $query->paginate($perPage);

        $albums = Album::withCount('gallery')->ordered()->get();

        $statsQuery = Gallery::query();
        if ($albumId) {
            $statsQuery->where('album_id', $albumId);
        }

        $statistics = [
            'total' => $statsQuery->count(),
            'active' => (clone $statsQuery)->where('status', 'active')->count(),
            'inactive' => (clone $statsQuery)->where('status', 'inactive')->count(),
            'featured' => (clone $statsQuery)->where('is_featured', true)->count(),
            'total_size' => $this->getTotalGallerySize()
        ];

        return view('admin::gallery.index', compact('gallery', 'albums', 'statistics', 'albumId'));
    }

    protected function getCountByType($albumId = null)
    {
        $query = Gallery::query();
        if ($albumId) {
            $query->where('album_id', $albumId);
        }

        $types = Gallery::getImageTypes();
        $counts = [];

        foreach (array_keys($types) as $type) {
            $counts[$type] = (clone $query)->where('image_type', $type)->count();
        }

        return $counts;
    }

    public function create(Request $request)
    {
        $albums = Album::withCount('gallery')->ordered()->get();  // Add withCount
        $selectedAlbumId = $request->input('album_id');

        return view('admin::gallery.create', compact('albums', 'selectedAlbumId'));
    }

    public function store(GalleryRequest $request)
    {
        $data = $request->validated();

        // Let boot() handle sort_order
        if (empty($data['sort_order'])) {
            unset($data['sort_order']);
        }

        // Set featured
        $data['is_featured'] = $request->boolean('is_featured');

        // NEW: Handle file_type
        $data['file_type'] = $request->file_type;

        // CASE 1: YouTube
        if ($request->file_type === 'youtube') {
            $data['youtube_url'] = $request->youtube_url;
            $data['path'] = null;
        }

        // CASE 2: Image or Video upload
        elseif ($request->hasFile('media')) {

            $file = $request->file('media');
            $mime = $file->getMimeType();

            // IMAGE
            if (str_starts_with($mime, 'image')) {
                $data['file_type'] = 'image';
                $data['path'] = $this->uploadImage($file); // reuse your function
            }

            // VIDEO
            elseif (str_starts_with($mime, 'video')) {
                $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
                $path = 'gallery/videos/' . date('Y/m/d');

                Storage::disk('public')->putFileAs($path, $file, $filename);

                $data['file_type'] = 'video';
                $data['path'] = $path . '/' . $filename;
            } else {
                return back()->withErrors(['media' => 'Unsupported file type']);
            }

            $data['youtube_url'] = null;
        }


        Gallery::create($data);

        return redirect()
            ->route('admin.gallery.index', ['album_id' => $data['album_id']])
            ->with('success', 'Media uploaded successfully.');
    }
    public function show($id)
    {
        $gallery = Gallery::with('album')->findOrFail($id);

        // Get next and previous images in the same album
        $previous = Gallery::where('album_id', $gallery->album_id)
            ->where('sort_order', '<', $gallery->sort_order)
            ->orderBy('sort_order', 'desc')
            ->first();

        $next = Gallery::where('album_id', $gallery->album_id)
            ->where('sort_order', '>', $gallery->sort_order)
            ->orderBy('sort_order', 'asc')
            ->first();

        // If no sort_order based navigation, fallback to id
        if (!$previous) {
            $previous = Gallery::where('album_id', $gallery->album_id)
                ->where('id', '<', $gallery->id)
                ->orderBy('id', 'desc')
                ->first();
        }

        if (!$next) {
            $next = Gallery::where('album_id', $gallery->album_id)
                ->where('id', '>', $gallery->id)
                ->orderBy('id', 'asc')
                ->first();
        }

        // Get all images in the same album for the gallery grid
        $albumImages = Gallery::where('album_id', $gallery->album_id)
            ->ordered()
            ->get();

        return view('admin::gallery.show', compact('gallery', 'previous', 'next', 'albumImages'));
    }

    /**
     * Show the form for editing the specified gallery item.
     */
    public function edit($id)
    {
        $gallery = Gallery::with('album')->findOrFail($id);
        $albums = Album::withCount('gallery')->ordered()->get();  // Add withCount

        return view('admin::gallery.edit', compact('gallery', 'albums'));
    }

    /**
     * Update the specified gallery item.
     */
    public function update(GalleryRequest $request, $id)
    {
        try {
            $gallery = Gallery::findOrFail($id);
            $data = $request->validated();

            $data['file_type'] = $request->file_type;
            $data['is_featured'] = $request->boolean('is_featured');

            // CASE: YouTube
            if ($request->file_type === 'youtube') {
                $data['youtube_url'] = $request->youtube_url;

                // delete old file if exists
                if ($gallery->path) {
                    $this->deleteImage($gallery->path);
                }

                $data['path'] = null;
            }

            // CASE: Image or Video
            elseif ($request->hasFile('media')) {

                // delete old file
                if ($gallery->path) {
                    $this->deleteImage($gallery->path);
                }

                $file = $request->file('media');
                $mime = $file->getMimeType();

                if (str_starts_with($mime, 'image')) {
                    $data['file_type'] = 'image';
                    $data['path'] = $this->uploadImage($file);
                } elseif (str_starts_with($mime, 'video')) {
                    $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
                    $path = 'gallery/videos/' . date('Y/m/d');

                    Storage::disk('public')->putFileAs($path, $file, $filename);

                    $data['file_type'] = 'video';
                    $data['path'] = $path . '/' . $filename;
                }
            }

            $gallery->update($data);

            return redirect()
                ->route('admin.gallery.index', ['album_id' => $gallery->album_id])
                ->with('success', 'Updated successfully');
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return back()->with('error', 'Update failed');
        }
    }

    /**
     * Remove the specified gallery item.
     */
    public function destroy($id)
    {
        try {
            $gallery = Gallery::findOrFail($id);
            $albumId = $gallery->album_id;

            if ($gallery->path) {
                $this->deleteImage($gallery->path);
            }

            $gallery->delete();

            return redirect()
                ->route('admin.gallery.index', ['album_id' => $albumId])
                ->with('success', 'Deleted successfully');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Delete failed');
        }
    }

    /**
     * Bulk upload images.
     */
    public function bulkUpload(Request $request)
    {
        $request->validate([
            'album_id' => 'required|exists:albums,id',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        try {
            $uploaded = [];

            foreach ($request->file('images') as $file) {
                try {
                    $imagePath = $this->uploadImage($file);

                    $gallery = Gallery::create([
                        'album_id' => $request->album_id,
                        'file_type' => 'image',
                        'path' => $imagePath,
                        'status' => 'active',
                        'sort_order' => Gallery::max('sort_order') + 1
                    ]);

                    $uploaded[] = $gallery;
                } catch (\Exception $e) {
                    Log::error('Failed to upload file: ' . $e->getMessage());
                    continue;
                }
            }

            return response()->json([
                'success' => true,
                'message' => count($uploaded) . ' images uploaded successfully',
                'images' => $uploaded
            ]);
        } catch (\Exception $e) {
            Log::error('Bulk upload failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to upload images: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete images.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:gallery,id'
        ]);

        try {
            $deleted = 0;

            foreach ($request->ids as $id) {
                try {
                    $gallery = Gallery::findOrFail($id);
                    if ($gallery->path) {
                        $this->deleteImage($gallery->path);
                    }
                    $gallery->delete();
                    $deleted++;
                } catch (\Exception $e) {
                    continue;
                }
            }

            return response()->json([
                'success' => true,
                'message' => $deleted . ' images deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Bulk delete failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete images: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update sort order.
     */
    public function updateSortOrder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:gallery,id',
            'orders.*.sort_order' => 'required|integer'
        ]);

        try {
            foreach ($request->orders as $order) {
                Gallery::where('id', $order['id'])->update(['sort_order' => $order['sort_order']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Sort order updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Sort order update failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update sort order'
            ], 500);
        }
    }

    /**
     * Upload image and create thumbnail.
     */
    protected function uploadImage($image)
    {
        $filename = Str::random(40) . '.' . $image->getClientOriginalExtension();
        $path = 'gallery/' . date('Y/m/d');
        $fullPath = $path . '/' . $filename;

        // Store original image
        Storage::disk('public')->putFileAs($path, $image, $filename);

        // Create thumbnail
        // $this->createThumbnail($fullPath);

        return $fullPath;
    }

    /**
     * Create thumbnail for image.
     */
    // protected function createThumbnail($imagePath)
    // {
    //     try {
    //         $fullPath = Storage::disk('public')->path($imagePath);

    //         if (!file_exists($fullPath)) {
    //             return false;
    //         }

    //         $thumbnailPath = dirname($fullPath) . '/thumbnails/' . basename($fullPath);

    //         if (!file_exists(dirname($thumbnailPath))) {
    //             mkdir(dirname($thumbnailPath), 0755, true);
    //         }

    //         // For Intervention Image 4.x - using manager
    //         $manager = ImageManager::usingDriver(Driver::class);
    //         $image = $manager->decodePath($fullPath);
    //         $image->cover(300, 300);
    //         $image->save($thumbnailPath);

    //         return true;
    //     } catch (\Exception $e) {
    //         Log::error('Thumbnail creation failed: ' . $e->getMessage());
    //         return false;
    //     }
    // }

    /**
     * Delete image and its thumbnail.
     */
    protected function deleteImage($imagePath)
    {
        // Delete original
        if (Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }

        // Delete thumbnail
        $thumbnailPath = dirname($imagePath) . '/thumbnails/' . basename($imagePath);
        if (Storage::disk('public')->exists($thumbnailPath)) {
            Storage::disk('public')->delete($thumbnailPath);
        }

        return true;
    }

    /**
     * Calculate total gallery size.
     */
    protected function getTotalGallerySize()
    {
        try {
            $files = Storage::disk('public')->allFiles('gallery');
            $totalSize = 0;

            foreach ($files as $file) {
                if (strpos($file, '/thumbnails/') === false) {
                    $totalSize += Storage::disk('public')->size($file);
                }
            }

            return $this->formatBytes($totalSize);
        } catch (\Exception $e) {
            Log::error('Failed to calculate gallery size: ' . $e->getMessage());
            return '0 B';
        }
    }

    /**
     * Format bytes to human readable format.
     */
    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        return round($bytes / pow(1024, $pow), $precision) . ' ' . $units[$pow];
    }
}
