<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Common\Entities\Brochure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrochureController extends Controller
{
    public function index()
    {
        $brochure = Brochure::first();


        return view('admin::brochures.index', compact('brochure'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'brochure' => 'required|file|mimes:pdf|max:2048',
        ]);

        $brochure = Brochure::first();

        if (!$brochure) {
            $brochure = new Brochure();
        }

        if ($brochure->file_path && Storage::disk('public')->exists($brochure->file_path)) {
            Storage::disk('public')->delete($brochure->file_path);
        }

        $file = $request->file('brochure');

        $path = $file->store('brochures', 'public');

        $brochure->updateOrCreate(
            ['id' => $brochure->id],
            [
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
            ]
        );

        return redirect()->back()->with('success', 'Brochure updated successfully.');
    }
}
