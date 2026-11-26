<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Kalix Car Rental Palawan</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/kalix-icon.jpg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0A0A0A] min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl p-8 shadow-2xl space-y-6">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/kalix-icon.jpg') }}" alt="Kalix" class="w-12 h-12 rounded-full object-cover border border-red-600">
            <div>
                <h1 class="text-xl font-black text-gray-950">KALIX Admin</h1>
                <p class="text-xs text-gray-500">Sign in to manage fleet, bookings &amp; contracts</p>
            </div>
        </div>

        @if($errors->any())
            <div class="rounded-xl bg-red-50 border border-red-200 p-3 text-xs text-red-700 font-medium">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email', 'admin@kalixrental.ph') }}" required autofocus class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-red-600 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-red-600 focus:ring-red-600">
            </div>
            <label class="flex items-center gap-2 text-xs text-gray-600">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-red-600 focus:ring-red-600">
                Remember me
            </label>
            <button type="submit" class="w-full py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm transition cursor-pointer">
                Sign in
            </button>
        </form>

        <div class="pt-4 border-t border-gray-100 text-center">
            <a href="{{ route('home') }}" class="text-xs font-semibold text-gray-500 hover:text-gray-900">&larr; Back to public website</a>
        </div>
    </div>
</body>
</html>
