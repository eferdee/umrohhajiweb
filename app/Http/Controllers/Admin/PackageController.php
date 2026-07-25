<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\PackageCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $query = Package::with('category')->withCount('schedules');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $packages = $query->latest()->paginate(10)->withQueryString();
        $categories = PackageCategory::orderBy('name')->get();

        return view('admin.packages.index', compact('packages', 'categories'));
    }

    public function create()
    {
        $categories = PackageCategory::where('status', true)->orderBy('name')->get();
        return view('admin.packages.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:package_categories,id',
            'title' => 'required|string|max:255',
            'airline' => 'nullable|string|max:255',
            'hotel_makkah' => 'nullable|string|max:255',
            'hotel_madinah' => 'nullable|string|max:255',
            'duration' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'facilities' => 'nullable|string',
            'itinerary' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048',
            'status' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . uniqid();
        $validated['status'] = $request->boolean('status', true);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('packages', 'public');
        }

        Package::create($validated);

        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil ditambahkan.');
    }

    public function edit(Package $package)
    {
        $categories = PackageCategory::orderBy('name')->get();
        return view('admin.packages.edit', compact('package', 'categories'));
    }

    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:package_categories,id',
            'title' => 'required|string|max:255',
            'airline' => 'nullable|string|max:255',
            'hotel_makkah' => 'nullable|string|max:255',
            'hotel_madinah' => 'nullable|string|max:255',
            'duration' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'facilities' => 'nullable|string',
            'itinerary' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048',
            'status' => 'boolean',
        ]);

        $validated['status'] = $request->boolean('status');

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('packages', 'public');
        }

        $package->update($validated);

        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil diperbarui.');
    }

    public function destroy(Package $package)
    {
        if ($package->schedules()->exists()) {
            return back()->with('error', 'Paket tidak bisa dihapus karena masih memiliki jadwal keberangkatan.');
        }

        $package->delete();

        return back()->with('success', 'Paket berhasil dihapus.');
    }
}
