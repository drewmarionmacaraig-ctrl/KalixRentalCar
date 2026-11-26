@extends('layouts.admin')

@section('header', 'Add-ons')

@section('actions')
    <a href="{{ route('admin.addons.create') }}" class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-semibold transition">
        + Add add-on
    </a>
@endsection

@section('content')
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-[#F8F9FA] text-xs font-semibold text-gray-500 border-b border-gray-200">
                <tr>
                    <th class="px-5 py-3">Name</th>
                    <th class="px-5 py-3">Pricing Type</th>
                    <th class="px-5 py-3">Rate</th>
                    <th class="px-5 py-3">Active</th>
                    <th class="px-5 py-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-xs">
                @foreach($addons as $addon)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3">
                            <div class="font-bold text-gray-950">{{ $addon->name }}</div>
                            <div class="text-gray-500">{{ $addon->description }}</div>
                        </td>
                        <td class="px-5 py-3">{{ $addon->pricing_type === 'per_day' ? 'Per day' : 'Flat / One-time' }}</td>
                        <td class="px-5 py-3 font-bold text-gray-950">₱{{ number_format((float) $addon->price, 2) }}</td>
                        <td class="px-5 py-3">
                            <form action="{{ route('admin.addons.toggle', $addon) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-2.5 py-1 rounded-full text-xs font-semibold cursor-pointer {{ $addon->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-500 border border-gray-200' }}">
                                    {{ $addon->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('admin.addons.edit', $addon) }}" class="font-semibold text-red-600 hover:underline">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
