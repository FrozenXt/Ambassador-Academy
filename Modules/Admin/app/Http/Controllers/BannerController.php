<?php

namespace Modules\Admin\Http\Controllers;

use Modules\Admin\Http\Requests\BannerRequest;
use App\Http\Controllers\Controller;
use Modules\Common\Entities\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('order')->get();
        return view('admin::banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin::banners.create');
    }

    public function store(BannerRequest $request)
    {
        $imagePath = $request->file('image')->store('banners', 'public');

        Banner::create([
            'title'         => $request->title,
            'subtitle'      => $request->subtitle,
            'image'         => $imagePath,
            'button_text'   => $request->button_text,
            'button_url'    => $request->button_url,
            'button_text_2' => $request->button_text_2,
            'button_url_2'  => $request->button_url_2,
            'status'        => $request->status,
            'order'         => $request->order ?? 0,
        ]);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner created successfully.');
    }


    public function edit(Banner $banner)
    {
        return view('admin::banners.edit', compact('banner'));
    }

    public function update(BannerRequest $request, Banner $banner)
    {
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($banner->image);
            $banner->image = $request->file('image')->store('banners', 'public');
        }

        $banner->update([
            'title'         => $request->title,
            'subtitle'      => $request->subtitle,
            'image'         => $banner->image,
            'button_text'   => $request->button_text,
            'button_url'    => $request->button_url,
            'button_text_2' => $request->button_text_2,
            'button_url_2'  => $request->button_url_2,
            'status'        => $request->status,
            'order'         => $request->order ?? 0,
        ]);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        Storage::disk('public')->delete($banner->image);
        $banner->delete();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner deleted successfully.');
    }

    // public function toggleStatus(Banner $banner)
    // {
    //     $banner->update([
    //         'status' => $banner->status === 'active' ? 'inactive' : 'active'
    //     ]);

    //     return back()->with('success', 'Banner status updated.');
    // }
    // In BannerController.php
    public function reorder(Request $request)
    {
        $orders = $request->input('orders');

        foreach ($orders as $order) {
            Banner::where('id', $order['id'])->update(['order' => $order['order']]);
        }

        return response()->json(['success' => true]);
    }

    public function toggle(Banner $banner)
    {
        $banner->status = $banner->status === 'active' ? 'inactive' : 'active';
        $banner->save();

        return response()->json([
            'success' => true,
            'message' => 'Banner status updated successfully.',
            'status'  => $banner->status
        ]);
    }
}
