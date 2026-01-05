<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <!-- Scripts -->
    @vite(['resources/css/app.css'])
    @livewireStyles
</head>

<body>
    <x-header />
    @livewire('notifications')

    <!-- Page Content -->
    <div class="min-h-screen">
        {{ $slot }}
    </div>
    <x-footer />
    @livewireScripts
    <div x-data="{ show: false, message: '' }" x-on:toast.window="
        message = $event.detail.message;
        show = true;
        setTimeout(() => show = false, 3000)
    " x-show="show" x-transition
        class="fixed bottom-6 right-6 z-50 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg">
        <span x-text="message"></span>
    </div>
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, {{ session('toast.ttl') }})" x-show="show">
        {{ session('toast.message') }}
    </div>
    @vite(['resources/js/app.js'])
</body>

</html>