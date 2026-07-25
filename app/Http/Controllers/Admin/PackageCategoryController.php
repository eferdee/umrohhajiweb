<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackageCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PackageCategoryController extends Controller
{
    public function index()
    {
        $categories = PackageCategory::withCount('packages')->latest()->paginate(10);
        return view('admin.package-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.package-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:package_categories,name',
            'description' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = $request->boolean('status', true);

        PackageCategory::create($validated);

        return redirect()->route('admin.package-categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(PackageCategory $packageCategory)
    {
        return view('admin.package-categories.edit', ['category' => $packageCategory]);
    }

    public function update(Request $request, PackageCategory $packageCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:package_categories,name,' . $packageCategory->id,
            'description' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = $request->boolean('status');

        $packageCategory->update($validated);

        return redirect()->route('admin.package-categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(PackageCategory $packageCategory)
    {
        if ($packageCategory->packages()->exists()) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih memiliki paket terkait.');
        }

        $packageCategory->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
