<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Common\Services\MediaService;
use Modules\Admin\Http\Requests\MediaRequest;

class MediaController extends Controller
{
    protected $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'type', 'extension']);
        $media   = $this->mediaService->getPaginatedMedia($filters);
        $stats   = $this->mediaService->getStats();

        return view('admin::media.index', compact('media', 'stats', 'filters'));
    }

    /**
     * Show the form for creating a new media file.
     */
    public function create()
    {
        return view('admin::media.create');
    }

    public function store(MediaRequest $request)
    {
        $uploaded = [];

        foreach ($request->file('files') as $file) {
            $uploaded[] = $this->mediaService->uploadFile($file, [
                'alt_text'    => $request->alt_text,
                'title'       => $request->title,
                'description' => $request->description,
                'tags'        => $request->tags,
            ]);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => count($uploaded) . ' file(s) uploaded successfully.',
                'media'   => $uploaded,
            ]);
        }

        return redirect()->route('admin.media.index')
            ->with('success', count($uploaded) . ' file(s) uploaded successfully.');
    }

    public function show(int $id)
    {
        $media = $this->mediaService->getMediaById($id);

        if (request()->ajax()) {
            return response()->json($media);
        }

        return view('admin::media.show', compact('media'));
    }

    /**
     * Show the form for editing the specified media.
     */
    public function edit(int $id)
    {
        $media = $this->mediaService->getMediaById($id);
        return view('admin::media.edit', compact('media'));
    }

    public function update(MediaRequest $request, int $id)
    {
        $this->mediaService->updateMedia($id, $request->validated());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Media updated successfully.',
            ]);
        }

        return redirect()->route('admin.media.show', $id)
            ->with('success', 'Media updated successfully.');
    }

    public function destroy(int $id)
    {
        $this->mediaService->deleteMedia($id);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Media deleted successfully.',
            ]);
        }

        return redirect()->route('admin.media.index')
            ->with('success', 'Media deleted successfully.');
    }

    public function bulkDelete(MediaRequest $request)
    {
        $this->mediaService->bulkDelete($request->validated()['ids']);

        return response()->json([
            'success' => true,
            'message' => count($request->ids) . ' file(s) deleted.',
        ]);
    }
}
