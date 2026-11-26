<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\PaymentSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PaymentSettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.payment-settings', [
            'settings' => PaymentSetting::current(),
            'methods' => PaymentMethod::orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'credit_card_fee_percentage' => ['required', 'numeric', 'min:0', 'max:50'],
            'down_payment_type' => ['required', 'in:percentage,fixed'],
            'down_payment_percentage' => ['required', 'numeric', 'min:1', 'max:100'],
            'down_payment_fixed_amount' => ['required', 'numeric', 'min:100'],
            'methods' => ['nullable', 'array'],
            'methods.*.name' => ['required', 'string', 'max:120'],
            'methods.*.account_name' => ['nullable', 'string', 'max:120'],
            'methods.*.account_number' => ['nullable', 'string', 'max:80'],
            'methods.*.instructions' => ['nullable', 'string', 'max:255'],
            'methods.*.qr_image' => ['nullable', 'image', 'max:4096'],
        ]);

        $settings = PaymentSetting::current();
        $settings->update([
            'credit_card_enabled' => $request->boolean('credit_card_enabled'),
            'credit_card_fee_percentage' => $validated['credit_card_fee_percentage'],
            'down_payment_type' => $validated['down_payment_type'],
            'down_payment_percentage' => $validated['down_payment_percentage'],
            'down_payment_fixed_amount' => $validated['down_payment_fixed_amount'],
        ]);

        $methodsInput = $request->input('methods', []);
        foreach ($methodsInput as $id => $data) {
            $method = PaymentMethod::find($id);
            if (! $method) {
                continue;
            }

            $updateData = [
                'name' => $data['name'],
                'account_name' => $data['account_name'] ?? null,
                'account_number' => $data['account_number'] ?? null,
                'instructions' => $data['instructions'] ?? null,
                'is_enabled' => ! empty($data['is_enabled']),
            ];

            if ($request->hasFile("methods.{$id}.qr_image")) {
                if ($method->qr_path) {
                    Storage::disk('public')->delete($method->qr_path);
                }
                $updateData['qr_path'] = $request->file("methods.{$id}.qr_image")->store('payment-qr', 'public');
            }

            $method->update($updateData);
        }

        return back()->with('success', 'Payment settings saved.');
    }

    public function storeMethod(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'account_name' => ['nullable', 'string', 'max:120'],
            'account_number' => ['nullable', 'string', 'max:80'],
            'instructions' => ['nullable', 'string', 'max:255'],
            'qr_image' => ['nullable', 'image', 'max:4096'],
        ]);

        $code = Str::slug($validated['name'], '_');
        if ($code === '' || PaymentMethod::where('code', $code)->exists()) {
            $code = ($code ?: 'method') . '_' . Str::lower(Str::random(4));
        }

        $qrPath = $request->hasFile('qr_image')
            ? $request->file('qr_image')->store('payment-qr', 'public')
            : null;

        PaymentMethod::create([
            'code' => $code,
            'name' => $validated['name'],
            'account_name' => $validated['account_name'] ?? null,
            'account_number' => $validated['account_number'] ?? null,
            'instructions' => $validated['instructions'] ?? null,
            'qr_path' => $qrPath,
            'is_enabled' => true,
            'sort_order' => (PaymentMethod::max('sort_order') ?? 0) + 1,
        ]);

        return back()->with('success', 'New payment method added.');
    }

    public function destroyMethod(PaymentMethod $method): RedirectResponse
    {
        if ($method->qr_path) {
            Storage::disk('public')->delete($method->qr_path);
        }

        $method->delete();

        return back()->with('success', 'Payment method deleted.');
    }
}
