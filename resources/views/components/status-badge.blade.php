@props(['status'])

@php
    $styles = match($status) {
        'available', 'confirmed', 'verified', 'resolved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'rented' => 'bg-blue-50 text-blue-700 border-blue-200',
        'payment_submitted', 'pending', 'pending_payment', 'new', 'replied' => 'bg-amber-50 text-amber-700 border-amber-200',
        'maintenance', 'cancelled', 'rejected' => 'bg-red-50 text-red-700 border-red-200',
        'completed' => 'bg-gray-100 text-gray-700 border-gray-200',
        default => 'bg-gray-100 text-gray-700 border-gray-200',
    };

    $dot = match($status) {
        'available', 'confirmed', 'verified', 'resolved' => 'bg-emerald-500',
        'rented' => 'bg-blue-500',
        'payment_submitted', 'pending', 'pending_payment', 'new', 'replied' => 'bg-amber-500',
        'maintenance', 'cancelled', 'rejected' => 'bg-red-500',
        default => 'bg-gray-500',
    };

    $label = match($status) {
        'pending_payment' => 'Pending Payment',
        'payment_submitted' => 'Payment Review',
        default => ucfirst(str_replace('_', ' ', $status)),
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold border {$styles}"]) }}>
    <span class="w-1.5 h-1.5 rounded-full {{ $dot }}"></span>
    {{ $label }}
</span>
