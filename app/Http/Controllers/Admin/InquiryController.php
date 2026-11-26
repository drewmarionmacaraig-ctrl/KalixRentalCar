<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InquiryController extends Controller
{
    public function index(Request $request): View
    {
        $query = Inquiry::with('vehicle')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        return view('admin.inquiries.index', [
            'inquiries' => $query->paginate(15)->withQueryString(),
        ]);
    }

    public function update(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:new,replied,resolved'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $inquiry->update($validated);

        return back()->with('success', 'Inquiry updated.');
    }
}
