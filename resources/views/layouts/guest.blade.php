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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Background Patterns -->
    <style>
        .auth-bg {
            background-color: #f3f4f6;
            background-image:
                radial-gradient(at 0% 0%, rgba(251, 82, 0, 0.1) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(37, 99, 235, 0.1) 0px, transparent 50%);
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased auth-bg">
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-6xl bg-white shadow-2xl rounded-3xl overflow-hidden flex min-h-[600px]">
            <!-- Left Side: Form -->
            <div class="w-full lg:w-1/2 p-8 md:p-12 lg:p-16 flex flex-col justify-center relative bg-white z-10">
                <div class="mb-8 text-center lg:text-left">
                    <a href="/" class="inline-flex items-center gap-2 font-bold text-2xl tracking-wider text-gray-800">
                        <span class="text-primary text-3xl">⚡</span> Simply Motoring
                    </a>
                </div>

                {{ $slot }}

                <div class="mt-8 text-center text-sm text-gray-400">
                    &copy; {{ date('Y') }} Simply Motoring. All rights reserved.
                </div>
            </div>

            <!-- Right Side: Image -->
            <div class="hidden lg:block w-1/2 relative">
                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('/images/garage.jpg');">
                </div>
                <!-- Dark Overlay -->
                <div class="absolute inset-0 bg-black/40"></div>

                <div class="absolute bottom-0 left-0 right-0 p-12 text-white">
                    <h2 class="text-3xl font-bold mb-4">Professional Garage Management</h2>
                    <p class="text-lg text-gray-200">Streamline your workflow with our advanced booking system.</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>