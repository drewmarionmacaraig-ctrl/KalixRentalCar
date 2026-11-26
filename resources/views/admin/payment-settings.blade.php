@extends('layouts.admin')

@section('header', 'Payment settings')

@section('actions')
    <button type="button" x-data @click="$dispatch('open-add-method')" class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-semibold transition cursor-pointer">
        + Add payment method
    </button>
@endsection

@section('content')
    <div x-data="{ showAddModal: false }" @open-add-method.window="showAddModal = true">
        {{-- Main Save Form --}}
        <form action="{{ route('admin.payment-settings.update') }}" method="POST" enctype="multipart/form-data"
              x-data="{ dpType: '{{ old('down_payment_type', $settings->down_payment_type) }}' }"
              class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            @csrf @method('PUT')

            {{-- Left Column: Dynamic Payment Methods --}}
            <div class="lg:col-span-7 space-y-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-bold text-gray-950">Payment methods</h2>
                    <button type="button" @click="showAddModal = true" class="text-xs font-semibold text-red-600 hover:underline cursor-pointer">
                        + Add another payment method
                    </button>
                </div>

                @forelse($methods as $method)
                    <div x-data="{
                            qrPreview: '{{ $method->qr_url ?? '' }}',
                            qrName: '',
                            handleQr(e) {
                                const file = e.target.files[0];
                                if (!file) return;
                                this.qrName = file.name;
                                this.qrPreview = URL.createObjectURL(file);
                            }
                         }"
                         class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
                        <div class="flex items-center justify-between gap-4 pb-3 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <h3 class="font-bold text-gray-950 text-sm">{{ $method->name }}</h3>
                                <span class="text-[11px] text-gray-400 font-mono">{{ $method->code }}</span>
                            </div>
                            <div class="flex items-center gap-4">
                                <label class="flex items-center gap-2 text-xs font-semibold cursor-pointer">
                                    <input type="checkbox" name="methods[{{ $method->id }}][is_enabled]" value="1" @checked($method->is_enabled) class="rounded border-gray-300 text-red-600 focus:ring-red-600">
                                    Enabled
                                </label>
                                <button type="button"
                                        @click="if(confirm('Delete {{ addslashes($method->name) }}?')) { document.getElementById('delete-method-{{ $method->id }}').submit(); }"
                                        class="px-2.5 py-1 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 text-xs font-semibold transition cursor-pointer">
                                    Delete
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Method Name *</label>
                                <input type="text" name="methods[{{ $method->id }}][name]" value="{{ old("methods.{$method->id}.name", $method->name) }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Account Name</label>
                                <input type="text" name="methods[{{ $method->id }}][account_name]" value="{{ old("methods.{$method->id}.account_name", $method->account_name) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Account / Mobile Number</label>
                                <input type="text" name="methods[{{ $method->id }}][account_number]" value="{{ old("methods.{$method->id}.account_number", $method->account_number) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Instructions (Optional)</label>
                            <input type="text" name="methods[{{ $method->id }}][instructions]" value="{{ old("methods.{$method->id}.instructions", $method->instructions) }}" placeholder="e.g. Scan QR or send via InstaPay" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                        </div>

                        {{-- QR Code Preview & Styled Uploader --}}
                        <div class="pt-2">
                            <label class="block text-xs font-semibold text-gray-700 mb-2">QR Code Image</label>
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                                <div class="w-28 h-28 rounded-xl border border-gray-200 bg-[#F8F9FA] flex items-center justify-center overflow-hidden shrink-0 p-1.5">
                                    <template x-if="qrPreview">
                                        <img :src="qrPreview" alt="QR Preview" class="w-full h-full object-contain rounded-lg">
                                    </template>
                                    <template x-if="!qrPreview">
                                        <div class="text-center text-[10px] font-semibold text-gray-400 px-2">
                                            No QR uploaded
                                        </div>
                                    </template>
                                </div>

                                <label class="flex-1 w-full flex flex-col items-center justify-center gap-1.5 rounded-xl border-2 border-dashed border-gray-300 hover:border-red-500 bg-[#F8F9FA] hover:bg-red-50/30 px-4 py-4 cursor-pointer transition">
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-950 hover:bg-gray-800 text-white text-xs font-semibold transition">
                                        <span x-text="qrPreview ? 'Change QR Code' : 'Upload QR Code'"></span>
                                    </span>
                                    <span class="text-[11px] text-gray-500 truncate max-w-full" x-text="qrName || 'JPG, PNG, WEBP up to 4MB'"></span>
                                    <input type="file" name="methods[{{ $method->id }}][qr_image]" accept="image/*" @change="handleQr($event)" class="sr-only">
                                </label>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl border border-gray-200 p-8 text-center space-y-3">
                        <p class="text-sm font-semibold text-gray-700">No payment methods configured yet.</p>
                        <button type="button" @click="showAddModal = true" class="px-4 py-2 rounded-lg bg-red-600 text-white text-xs font-semibold cursor-pointer">
                            + Add payment method
                        </button>
                    </div>
                @endforelse
            </div>

            {{-- Right Column: Down Payment & Credit Card Fee --}}
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
                    <h2 class="font-bold text-gray-950 text-sm">Down payment rule</h2>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="p-3 rounded-xl border-2 cursor-pointer text-xs font-bold transition" :class="dpType === 'percentage' ? 'border-red-600 bg-red-50/40 text-red-700' : 'border-gray-200 text-gray-700'">
                            <input type="radio" name="down_payment_type" value="percentage" x-model="dpType" class="sr-only">
                            Percentage of total
                        </label>
                        <label class="p-3 rounded-xl border-2 cursor-pointer text-xs font-bold transition" :class="dpType === 'fixed' ? 'border-red-600 bg-red-50/40 text-red-700' : 'border-gray-200 text-gray-700'">
                            <input type="radio" name="down_payment_type" value="fixed" x-model="dpType" class="sr-only">
                            Fixed amount (₱)
                        </label>
                    </div>

                    <div x-show="dpType === 'percentage'">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Percentage (%)</label>
                        <input type="number" name="down_payment_percentage" value="{{ old('down_payment_percentage', $settings->down_payment_percentage) }}" min="1" max="100" step="1" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    </div>

                    <div x-show="dpType === 'fixed'">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Fixed Amount (₱)</label>
                        <input type="number" name="down_payment_fixed_amount" value="{{ old('down_payment_fixed_amount', $settings->down_payment_fixed_amount) }}" min="100" step="100" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="font-bold text-gray-950 text-sm">Credit Card Processing Fee</h2>
                        <label class="flex items-center gap-2 text-xs font-semibold">
                            <input type="checkbox" name="credit_card_enabled" value="1" @checked($settings->credit_card_enabled) class="rounded border-gray-300 text-red-600">
                            Enabled
                        </label>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Fee Percentage (%) — per Contract Section 2</label>
                        <input type="number" name="credit_card_fee_percentage" value="{{ old('credit_card_fee_percentage', $settings->credit_card_fee_percentage) }}" min="0" max="50" step="0.5" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    </div>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition cursor-pointer">
                    Save payment settings
                </button>
            </div>
        </form>

        {{-- Hidden Delete Forms (outside main form to avoid nested forms) --}}
        @foreach($methods as $method)
            <form id="delete-method-{{ $method->id }}" action="{{ route('admin.payment-settings.methods.destroy', $method) }}" method="POST" class="hidden">
                @csrf @method('DELETE')
            </form>
        @endforeach

        {{-- Add Payment Method Modal --}}
        <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div @click.away="showAddModal = false" class="bg-white rounded-2xl border border-gray-200 max-w-md w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="font-bold text-gray-950 text-base">Add payment method</h2>
                    <button type="button" @click="showAddModal = false" class="text-gray-400 hover:text-gray-700 text-lg font-bold cursor-pointer">&times;</button>
                </div>

                <form action="{{ route('admin.payment-settings.methods.store') }}" method="POST" enctype="multipart/form-data"
                      x-data="{ newQrPreview: '', newQrName: '' }" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Method Name *</label>
                        <input type="text" name="name" required placeholder="e.g. Maya, GoTyme, UnionBank" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Account Name</label>
                        <input type="text" name="account_name" placeholder="KLX Affordable Cars for Rent" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Account / Mobile Number</label>
                        <input type="text" name="account_number" placeholder="09XX-XXX-XXXX" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Instructions (Optional)</label>
                        <input type="text" name="instructions" placeholder="Optional note for customer" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">QR Code Image (Optional)</label>
                        <div class="flex items-center gap-3">
                            <div class="w-16 h-16 rounded-xl border border-gray-200 bg-[#F8F9FA] flex items-center justify-center overflow-hidden shrink-0">
                                <template x-if="newQrPreview">
                                    <img :src="newQrPreview" class="w-full h-full object-contain">
                                </template>
                                <template x-if="!newQrPreview">
                                    <span class="text-[10px] text-gray-400">QR</span>
                                </template>
                            </div>
                            <label class="flex-1 rounded-xl border border-dashed border-gray-300 hover:border-red-500 px-3 py-3 text-center cursor-pointer text-xs font-semibold text-gray-700">
                                <span x-text="newQrName || 'Upload QR Code'"></span>
                                <input type="file" name="qr_image" accept="image/*" @change="if($event.target.files[0]) { newQrName = $event.target.files[0].name; newQrPreview = URL.createObjectURL($event.target.files[0]); }" class="sr-only">
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="flex-1 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold cursor-pointer">
                            Add method
                        </button>
                        <button type="button" @click="showAddModal = false" class="px-4 py-2.5 rounded-xl border border-gray-300 text-gray-700 text-sm font-semibold cursor-pointer">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
