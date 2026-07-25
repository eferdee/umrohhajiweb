<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\PackageSchedule;
use Illuminate\Http\Request;

class PackageScheduleController extends Controller
{
    public function index(Package $package)
    {
        $schedules = $package->schedules()->withCount('bookings')->orderBy('departure_date')->paginate(10);
        return view('admin.package-schedules.index', compact('package', 'schedules'));
    }

    public function create(Package $package)
    {
        return view('admin.package-schedules.create', compact('package'));
    }

    public function store(Request $request, Package $package)
    {
        $validated = $request->validate([
            'departure_city' => 'required|string|max:255',
            'departure_date' => 'required|date|after:today',
            'return_date' => 'required|date|after:departure_date',
            'price' => 'required|numeric|min:0',
            'quota' => 'required|integer|min:1',
            'status' => 'boolean',
        ]);

        $validated['available_seat'] = $validated['quota'];
        $validated['status'] = $request->boolean('status', true);

        $package->schedules()->create($validated);

        return redirect()->route('admin.packages.schedules.index', $package)->with('success', 'Jadwal keberangkatan berhasil ditambahkan.');
    }

    public function edit(Package $package, PackageSchedule $schedule)
    {
        return view('admin.package-schedules.edit', compact('package', 'schedule'));
    }

    public function update(Request $request, Package $package, PackageSchedule $schedule)
    {
        $validated = $request->validate([
            'departure_city' => 'required|string|max:255',
            'departure_date' => 'required|date',
            'return_date' => 'required|date|after:departure_date',
            'price' => 'required|numeric|min:0',
            'quota' => 'required|integer|min:1',
            'status' => 'boolean',
        ]);

        // jaga konsistensi kursi tersisa kalau kuota diubah manual
        $bookedSeats = $schedule->quota - $schedule->available_seat;
        $validated['available_seat'] = max(0, $validated['quota'] - $bookedSeats);
        $validated['status'] = $request->boolean('status');

        $schedule->update($validated);

        return redirect()->route('admin.packages.schedules.index', $package)->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Package $package, PackageSchedule $schedule)
    {
        if ($schedule->bookings()->exists()) {
            return back()->with('error', 'Jadwal tidak bisa dihapus karena sudah ada booking untuk jadwal ini.');
        }

        $schedule->delete();

        return back()->with('success', 'Jadwal berhasil dihapus.');
    }
}
