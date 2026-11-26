@extends('layouts.admin')

@section('header', 'Inquiries')

@section('content')
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200 flex items-center gap-2 text-xs font-semibold">
            <a href="{{ route('admin.inquiries.index') }}" class="px-3 py-1.5 rounded-lg {{ !request('status') ? 'bg-gray-950 text-white' : 'bg-gray-100 text-gray-600' }}">All</a>
            <a href="{{ route('admin.inquiries.index', ['status' => 'new']) }}" class="px-3 py-1.5 rounded-lg {{ request('status') === 'new' ? 'bg-gray-950 text-white' : 'bg-gray-100 text-gray-600' }}">New</a>
            <a href="{{ route('admin.inquiries.index', ['status' => 'replied']) }}" class="px-3 py-1.5 rounded-lg {{ request('status') === 'replied' ? 'bg-gray-950 text-white' : 'bg-gray-100 text-gray-600' }}">Replied</a>
            <a href="{{ route('admin.inquiries.index', ['status' => 'resolved']) }}" class="px-3 py-1.5 rounded-lg {{ request('status') === 'resolved' ? 'bg-gray-950 text-white' : 'bg-gray-100 text-gray-600' }}">Resolved</a>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse($inquiries as $inq)
                <div class="p-5 space-y-3">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2.5">
                                <h3 class="font-bold text-gray-950 text-sm">{{ $inq->subject }}</h3>
                                <x-status-badge :status="$inq->status" />
                            </div>
                            <div class="text-xs text-gray-500 mt-0.5">
                                From <strong>{{ $inq->name }}</strong> &bull; {{ $inq->contact_no }}
                                @if($inq->email) &bull; {{ $inq->email }} @endif
                                @if($inq->vehicle) &bull; Vehicle: <strong>{{ $inq->vehicle->name }}</strong> @endif
                                &bull; {{ $inq->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>

                    <p class="text-sm text-gray-700 bg-[#F8F9FA] p-3 rounded-xl border border-gray-200">{{ $inq->message }}</p>

                    <form action="{{ route('admin.inquiries.update', $inq) }}" method="POST" class="flex flex-wrap items-center gap-3">
                        @csrf @method('PUT')
                        <select name="status" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs">
                            <option value="new" @selected($inq->status === 'new')>New</option>
                            <option value="replied" @selected($inq->status === 'replied')>Replied</option>
                            <option value="resolved" @selected($inq->status === 'resolved')>Resolved</option>
                        </select>
                        <input type="text" name="admin_notes" value="{{ $inq->admin_notes }}" placeholder="Add internal note..." class="flex-1 min-w-[200px] rounded-lg border border-gray-300 px-3 py-1.5 text-xs">
                        <button type="submit" class="px-4 py-1.5 rounded-lg bg-gray-950 hover:bg-gray-800 text-white text-xs font-semibold cursor-pointer">
                            Update
                        </button>
                    </form>
                </div>
            @empty
                <div class="p-10 text-center text-sm text-gray-400">No inquiries yet.</div>
            @endforelse
        </div>
    </div>
@endsection
