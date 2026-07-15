<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Modules\Common\Entities\Post;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status']);

        $posts = Post::query()
            ->when($filters['search'] ?? null, fn($q, $v) => $q->where('title', 'like', "%{$v}%"))
            ->when($filters['status'] ?? null, fn($q, $v) => $q->where('status', $v))
            ->orderBy('sort_order')
            ->get();

        return view('admin::posts.index', compact('posts', 'filters'));
    }

    public function create()
    {
        return view('admin::posts.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        $post = Post::create($validated);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Post created successfully.');

        // or: return response()->json($post, 201);
    }

    public function show(Post $post)
    {
        return view('admin::posts.show', compact('post'));
        return $post;
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);

        return view('admin::posts.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $validated = $this->validateData($request, $post->id);

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($validated);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Post updated successfully.');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->forceDelete(); // permanent delete, bypasses soft-delete entirely

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Post permanently deleted.');
    }
    protected function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title'             => 'required|string|max:255',
            'subtitle'          => 'nullable|string|max:255',
            'slug'              => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('posts', 'slug')->ignore($ignoreId),
            ],
            'position'          => 'nullable|string|max:255',
            'code'              => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('posts', 'code')->ignore($ignoreId),
            ],
            'description'       => 'nullable|string',
            'content'           => 'nullable|string',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'status'            => 'required|in:draft,published',
            'is_featured'       => 'boolean',
            'sort_order'        => 'nullable|integer|min:0',
            'published_at'      => 'nullable|date',
            'meta_title'        => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string|max:500',
        ]);
    }

    public function toggleFeatured($id)
    {
        $post = Post::findOrFail($id);
        $post->update(['is_featured' => !$post->is_featured]);

        return redirect()->back()->with('success', 'Featured status updated.');
    }

    public function toggleStatus($id)
    {
        $post = Post::findOrFail($id);
        $post->update([
            'status' => $post->status === 'published' ? 'draft' : 'published',
        ]);

        return redirect()->back()->with('success', 'Status updated.');
    }

    public function sortOrder(Request $request)
    {
        $validated = $request->validate([
            'orders'              => 'required|array',
            'orders.*.id'         => 'required|integer|exists:posts,id',
            'orders.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($validated['orders'] as $item) {
            Post::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true]);
    }
}
