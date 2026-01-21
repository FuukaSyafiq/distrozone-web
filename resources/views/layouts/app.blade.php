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
    <div class="w-full">
        {{ $slot }}
    </div>
    <x-footer />
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
    @if (session('success'))
    <script>
        // Contoh simple alert
            alert("{{ session('success') }}");
    </script>
    @endif

    @if (session('error'))
    <script>
        alert("{{ session('error') }}");
    </script>
    @endif
    @livewireScripts

    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/69702737742235197e52c2d0/1jff1isgo';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
        })();
    </script>
    
    <!--End of Tawk.to Script-->
</body>

</html>