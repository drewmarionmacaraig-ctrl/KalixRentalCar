<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AddonController extends Controller
{
    public function index(): View
    {
        return view('admin.addons.index', [
            'addons' => Addon::orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.addons.form', [
            'addon' => new Addon(['pricing_type' => 'per_day', 'is_active' => true]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:300'],
            'pricing_type' => ['required', 'in:per_day,flat'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);
        $validated['is_active'] = $request->boolean('is_active');

        Addon::create($validated);

        return redirect()->route('admin.addons.index')->with('success', 'Add-on created.');
    }

    public function edit(Addon $addon): View
    {
        return view('admin.addons.form', [
            'addon' => $addon,
        ]);
    }

    public function update(Request $request, Addon $addon): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:300'],
            'pricing_type' => ['required', 'in:per_day,flat'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);
        $validated['is_active'] = $request->boolean('is_active');

        $addon->update($validated);

        return redirect()->route('admin.addons.index')->with('success', 'Add-on updated.');
    }

    public function toggle(Addon $addon): RedirectResponse
    {
        $addon->update(['is_active' => ! $addon->is_active]);

        return back()->with('success', 'Add-on status updated.');
    }
}
