<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::orderBy('sort_order')->latest()->paginate(12);
        return view('admin.gallery.index', compact('galleries'));
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:image,video',
            'file' => 'required|file|max:10240',
            'sort_order' => 'nullable|integer',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['file_path'] = $request->file('file')->store('gallery', 'public');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_published'] = $request->boolean('is_published', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        unset($validated['file']);

        Gallery::create($validated);

        return redirect()->route('admin.gallery.index')->with('success', 'Item galeri berhasil ditambahkan.');
    }

    public function destroy(Gallery $gallery)
    {
        $gallery->delete();
        return back()->with('success', 'Item galeri berhasil dihapus.');
    }
}
