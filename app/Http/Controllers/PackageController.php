<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\PackageCategory;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $query = Package::with(['category'])
            ->withCount(['schedules' => function ($q) {
                $q->where('status', true)->where('available_seat', '>', 0);
            }])
            ->withMin(['schedules' => function ($q) {
                $q->where('status', true)->where('available_seat', '>', 0);
            }], 'price')
            ->where('status', true);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $packages = $query->latest()->paginate(9)->withQueryString();
        $categories = PackageCategory::where('status', true)->orderBy('name')->get();

        return view('packages.index', compact('packages', 'categories'));
    }

    public function show(Package $package)
    {
        abort_unless($package->status, 404);

        $package->load(['category', 'schedules' => function ($q) {
            $q->where('status', true)
                ->where('departure_date', '>=', now()->toDateString())
                ->orderBy('departure_date');
        }]);

        return view('packages.show', compact('package'));
    }
}
