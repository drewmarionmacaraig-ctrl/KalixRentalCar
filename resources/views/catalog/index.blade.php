@extends('layouts.app')

@section('title', 'Browse Vehicles — Kalix Car Rental Palawan')

@section('content')
    <div class="bg-[#F8F9FA] min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-black text-gray-950">Browse vehicles</h1>
                <p class="text-sm text-gray-600 mt-1">Find the right car for your Puerto Princesa or Palawan road trip.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                {{-- Left Sidebar Filters --}}
                <aside class="lg:col-span-3 bg-white rounded-2xl border border-gray-200 p-5 space-y-5">
                    <div class="flex items-center justify-between">
                        <h2 class="font-bold text-gray-950 text-sm">Filters</h2>
                        <a href="{{ route('catalog.index') }}" class="text-xs font-semibold text-red-600 hover:underline">Reset</a>
                    </div>

                    <form action="{{ route('catalog.index') }}" method="GET" class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="e.g. Innova, Vios..." class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Pickup Date & Time</label>
                            <input type="datetime-local" name="pickup_datetime" value="{{ request('pickup_datetime') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Return Date & Time</label>
                            <input type="datetime-local" name="return_datetime" value="{{ request('return_datetime') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Category</label>
                            <select name="category" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Transmission</label>
                            <select name="transmission" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                                <option value="">Any</option>
                                <option value="Automatic" @selected(request('transmission') === 'Automatic')>Automatic</option>
                                <option value="Manual" @selected(request('transmission') === 'Manual')>Manual</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Minimum Seats</label>
                            <select name="seats" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                                <option value="">Any</option>
                                <option value="5" @selected(request('seats') == '5')>5+ Seats</option>
                                <option value="7" @selected(request('seats') == '7')>7+ Seats</option>
                                <option value="12" @selected(request('seats') == '12')>12+ Seats</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Max Daily Rate (₱)</label>
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="e.g. 4000" min="500" step="100" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                        </div>

                        <label class="flex items-center gap-2 text-xs text-gray-700 pt-1">
                            <input type="checkbox" name="available_only" value="1" @checked(request('available_only')) class="rounded border-gray-300 text-red-600 focus:ring-red-600">
                            Show only available for selected dates
                        </label>

                        <button type="submit" class="w-full py-2.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition cursor-pointer">
                            Apply filters
                        </button>
                    </form>
                </aside>

                {{-- Vehicle Grid --}}
                <div class="lg:col-span-9">
                    <div class="flex items-center justify-between mb-4 text-sm text-gray-600">
                        <span>Showing <strong class="text-gray-950">{{ $vehicles->count() }}</strong> vehicle(s)</span>
                        @if($pickup && $return)
                            <span class="text-xs bg-white border border-gray-200 px-3 py-1 rounded-full">
                                Dates: {{ $pickup->format('M d, g:i A') }} &ndash; {{ $return->format('M d, g:i A') }}
                            </span>
                        @endif
                    </div>

                    @if($vehicles->isEmpty())
                        <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center space-y-3">
                            <p class="text-gray-900 font-bold text-lg">No vehicles match your filters</p>
                            <p class="text-sm text-gray-500">Try clearing some filters or selecting different rental dates.</p>
                            <a href="{{ route('catalog.index') }}" class="inline-flex px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-semibold">Reset filters</a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach($vehicles as $vehicle)
                                <x-vehicle-card :vehicle="$vehicle" :pickup="$pickup" :return="$return" />
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
