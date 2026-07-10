<?php


namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Admin\Http\Requests\AlbumRequest;
use Modules\Common\Services\AlbumServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Common\Entities\Album;

class AlbumController extends Controller
{
    protected $albumService;

    public function __construct(AlbumServiceInterface $albumService)
    {
        $this->albumService = $albumService;
    }

    public function index(Request $request)
    {
        $perPage    = $request->input('per_page', 15);
        $filters    = $request->only(['search', 'status']);
        $albums     = $this->albumService->getPaginatedAlbums($perPage, $filters);
        $statistics = $this->albumService->getAlbumStatistics();

        return view('admin::albums.index', compact('albums', 'statistics'));
    }

    public function create()
    {
        return view('admin::albums.create');
    }

    public function store(AlbumRequest $request)
    {
        try {
            $album = $this->albumService->createAlbum($request->validated());

            return redirect()
                ->route('admin.albums.index')
                ->with('success', 'Album created successfully!');
        } catch (\Exception $e) {
            Log::error('Album creation failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Failed to create album: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $album = $this->albumService->getAlbumById($id);

        return view('admin::albums.edit', compact('album'));
    }

    public function update(AlbumRequest $request, $id)
    {
        try {
            $album = $this->albumService->updateAlbum($id, $request->validated());

            return redirect()
                ->route('admin.albums.index')
                ->with('success', 'Album updated successfully!');
        } catch (\Exception $e) {
            Log::error('Album update failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Failed to update album: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->albumService->deleteAlbum($id);

            return redirect()
                ->route('admin.albums.index')
                ->with('success', 'Album deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Album deletion failed: ' . $e->getMessage());

            return back()->with('error', 'Failed to delete album: ' . $e->getMessage());
        }
    }
    public function updateSortOrder(Request $request)
    {
        try {
            foreach ($request->orders as $item) {
                Album::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
            }
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
