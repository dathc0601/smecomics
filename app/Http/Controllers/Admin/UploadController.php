<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    /**
     * Handle TinyMCE image upload.
     */
    public function tinymceUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('blog/content', 'public');

            return response()->json([
                'location' => Storage::url($path),
            ]);
        }

        return response()->json([
            'error' => 'No file uploaded',
        ], 400);
    }
}
