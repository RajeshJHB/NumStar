<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NumStar')</title>
    
    <!-- Google Fonts - Calligraphic Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Favicons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('icons/favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('icons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('icons/favicon-48x48.png') }}">
    
    <!-- Apple Touch Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('icons/apple-touch-icon.png') }}">
    
    <!-- Android/Chrome Icons -->
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('icons/android-chrome-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('icons/android-chrome-512x512.png') }}">
    
    <!-- Web App Manifest -->
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('job1') }}" class="hover:opacity-80 transition-opacity">
                        <h1 class="text-3xl font-bold" style="font-family: 'Dancing Script', cursive; color: #1f2937;">NumStar</h1>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('about') }}" class="text-gray-700 hover:text-gray-900 transition-colors">About</a>
                    <a href="{{ route('help') }}" class="text-gray-700 hover:text-gray-900 transition-colors">Help</a>
                    @auth
                        <div class="relative" id="user-menu-container">
                            <button id="user-menu-button" class="flex items-center text-gray-700 hover:text-gray-900 focus:outline-none">
                                <span>{{ Auth::user()->name }}</span>
                                <svg id="user-menu-arrow" class="ml-1 h-4 w-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <div id="user-menu-dropdown" 
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200 hidden">
                                @if(Auth::user()->isRoleManager())
                                    <a href="{{ route('roles.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Manage Roles
                                    </a>
                                    <a href="{{ route('user-roles.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Assign Roles
                                    </a>
                                @endif
                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Profile
                                </a>
                                <a href="{{ route('profile.password') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Password Reset
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">Login</a>
                        <a href="{{ route('register') }}" class="text-gray-700 hover:text-gray-900">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // User Menu dropdown (only if user is logged in)
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenuDropdown = document.getElementById('user-menu-dropdown');
            const userMenuArrow = document.getElementById('user-menu-arrow');
            
            if (userMenuButton && userMenuDropdown) {
                // Toggle dropdown on button click
                userMenuButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isHidden = userMenuDropdown.classList.contains('hidden');
                    
                    if (isHidden) {
                        userMenuDropdown.classList.remove('hidden');
                        if (userMenuArrow) {
                            userMenuArrow.style.transform = 'rotate(180deg)';
                        }
                    } else {
                        userMenuDropdown.classList.add('hidden');
                        if (userMenuArrow) {
                            userMenuArrow.style.transform = 'rotate(0deg)';
                        }
                    }
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    const container = document.getElementById('user-menu-container');
                    if (container && !container.contains(e.target)) {
                        userMenuDropdown.classList.add('hidden');
                        if (userMenuArrow) {
                            userMenuArrow.style.transform = 'rotate(0deg)';
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>
