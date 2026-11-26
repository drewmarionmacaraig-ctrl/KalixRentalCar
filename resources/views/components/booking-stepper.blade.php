@props(['currentStep' => 1])

@php
    $steps = [
        1 => 'Details & Add-ons',
        2 => 'Rental Agreement',
        3 => 'Payment',
        4 => 'Confirmation',
    ];
@endphp

<div class="bg-white border-b border-gray-200">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <ol class="flex items-center justify-between gap-2 sm:gap-4">
            @foreach($steps as $num => $label)
                @php
                    $isDone = $num < $currentStep;
                    $isCurrent = $num === $currentStep;
                @endphp
                <li class="flex items-center gap-2.5 {{ $num < 4 ? 'flex-1' : '' }}">
                    <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold shrink-0
                        {{ $isDone ? 'bg-red-600 text-white' : ($isCurrent ? 'bg-red-600 text-white ring-4 ring-red-100' : 'bg-gray-100 text-gray-500 border border-gray-300') }}">
                        @if($isDone)
                            &#10003;
                        @else
                            {{ $num }}
                        @endif
                    </span>
                    <span class="text-xs sm:text-sm font-semibold whitespace-nowrap {{ $isCurrent || $isDone ? 'text-gray-950' : 'text-gray-400' }}">
                        {{ $label }}
                    </span>
                    @if($num < 4)
                        <div class="hidden sm:block flex-1 h-0.5 {{ $isDone ? 'bg-red-600' : 'bg-gray-200' }} mx-2"></div>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>
</div>
