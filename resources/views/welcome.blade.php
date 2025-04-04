<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }}</title>
           <!-- fav.ico -->
        <link rel="icon" href="{{ asset('assets/2.png') }}" type="image/png"> <!-- If using an ICO file -->
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body 
        class="antialiased font-sans min-h-screen flex flex-col" 
        style="background: url('{{ asset('assets/bg.jpg') }}');
               background-size: cover; 
               background-position: center; 
               background-attachment: fixed;">

                <!-- ========== HEADER ========== -->
                <header class="sticky top-4 inset-x-0 z-50">
                    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 bg-white/95 rounded-2xl shadow-lg">
                        <div class="flex items-center justify-between h-20">
                            <!-- Logo -->
                            <div class="flex-shrink-0">
                                <a href="/" class="flex items-center" aria-label="Brand">
                                    <img class="w-12 h-12 rounded-full object-cover hover:scale-105 transition-transform duration-300" 
                                         src="{{ asset('assets/1.jpg') }}" 
                                         alt="Brand Image">
                                    <span class="ml-3 text-xl font-semibold text-gray-900">City Vet Office</span>
                                </a>
                            </div>

                            <!-- Desktop Navigation -->
                            <div class="hidden md:flex md:items-center md:space-x-8">
                                <a href="#" class="text-gray-700 hover:text-green-600 px-3 py-2 text-sm font-medium transition-colors duration-200" aria-current="page">
                                    Home
                                </a>
                                <a href="#team" class="text-gray-700 hover:text-green-600 px-3 py-2 text-sm font-medium transition-colors duration-200">
                                    Team
                                </a>
                                <a href="#features" class="text-gray-700 hover:text-green-600 px-3 py-2 text-sm font-medium transition-colors duration-200">
                                    Features
                                </a>
                                <a href="#gallery" class="text-gray-700 hover:text-green-600 px-3 py-2 text-sm font-medium transition-colors duration-200">
                                    Gallery
                                </a>
                                <a href="#services" class="text-gray-700 hover:text-green-600 px-3 py-2 text-sm font-medium transition-colors duration-200">
                                    Services
                                </a>
                            </div>

                            <!-- Auth Buttons -->
                            <div class="hidden md:flex md:items-center md:space-x-4">
                                <a href="/login" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 hover:text-green-600 transition-colors duration-200">
                                    Log in
                                </a>
                                <a href="/register" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors duration-200">
                                    Register
                                </a>
                            </div>

                            <!-- Updated Mobile menu button -->
                            <div class="md:hidden">
                                <button type="button" 
                                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-green-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-green-500 transition-colors duration-200"
                                        onclick="toggleMobileMenu()">
                                    <svg id="menuOpen" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                    </svg>
                                    <svg id="menuClose" class="h-6 w-6 hidden" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Updated Mobile menu -->
                        <div id="mobileMenu" class="hidden md:hidden">
                            <div class="px-2 pt-2 pb-3 space-y-1">
                                <a href="#" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-green-600 hover:bg-gray-50 rounded-md transition-colors duration-200">
                                    Home
                                </a>
                                <a href="#team" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-green-600 hover:bg-gray-50 rounded-md transition-colors duration-200">
                                    Team
                                </a>
                                <a href="#features" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-green-600 hover:bg-gray-50 rounded-md transition-colors duration-200">
                                    Features
                                </a>
                                <a href="#gallery" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-green-600 hover:bg-gray-50 rounded-md transition-colors duration-200">
                                    Gallery
                                </a>
                                <a href="#services" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-green-600 hover:bg-gray-50 rounded-md transition-colors duration-200">
                                    Services
                                </a>
                                <!-- Mobile Auth Buttons -->
                                <div class="mt-4 space-y-2 pb-3">
                                    <a href="/login" class="block w-full px-4 py-2 text-center text-base font-medium text-gray-700 hover:text-green-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                        Log in
                                    </a>
                                    <a href="/register" class="block w-full px-4 py-2 text-center text-base font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors duration-200">
                                        Register
                                    </a>
                                </div>
                            </div>
                        </div>
                    </nav>
                </header>
                <!-- ========== END HEADER ========== -->
                <!-- ========== HERO ========== -->
                <div class="relative min-h-screen flex flex-col">
                    <!-- Hero Section with full-height background -->
                    <div class="relative flex-1 flex items-center py-32 lg:py-48">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <div class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-2xl rounded-3xl p-8 lg:p-12">
                                <div class="grid lg:grid-cols-2 gap-12 items-center">
                                    <div class="space-y-8">
                                <div>
                                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight">
                                                Your Pet's Health is 
                                                <span class="text-green-600">Our Priority</span>
                                    </h1>
                                            <p class="mt-6 text-xl text-gray-600 leading-relaxed">
                                                Providing compassionate and expert veterinary care for your beloved pets. Trust us to keep them healthy and happy.
                                    </p>
                                        </div>
                                        <div class="flex flex-col sm:flex-row gap-4">
                                            <a href="/register" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white bg-green-600 rounded-xl hover:bg-green-700 transform hover:scale-105 transition-all duration-200">
                                            Get Started
                                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                            <a href="#services" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-green-600 bg-green-50 rounded-xl hover:bg-green-100 transform hover:scale-105 transition-all duration-200">
                                                Our Services
                                            </a>
                                        </div>
                                    </div>
                                    <div class="hidden lg:block">
                                    <img class="w-full rounded-2xl shadow-2xl transform hover:scale-105 transition-transform duration-500" 
                                             src="{{ asset('assets/1.jpg') }}" 
                                         alt="Animal Care Image">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Features Section -->
                    <section id="features" class="relative bg-white py-24">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <!-- Section Header -->
                            <div class="text-center max-w-3xl mx-auto mb-20">
                                <span class="inline-block px-4 py-1 rounded-full bg-green-100 text-green-600 text-sm font-semibold mb-4">Our Features</span>
                                <h2 class="text-4xl font-bold text-gray-900 sm:text-5xl">Why Choose Our Services?</h2>
                                <p class="mt-6 text-xl text-gray-600">Experience exceptional veterinary care with our comprehensive range of services and dedicated team</p>
                            </div>

                            <!-- Features Grid -->
                            <div class="grid md:grid-cols-3 gap-12">
                                <!-- Feature Cards -->
                            @foreach ([
                                [
                                        'icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z',
                                        'title' => 'Modern Equipment',
                                        'description' => 'State-of-the-art facilities for accurate diagnosis and treatment'
                                    ],
                                    [
                                        'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                                        'title' => 'Expert Team',
                                        'description' => 'Experienced veterinarians dedicated to animal care'
                                    ],
                                    [
                                        'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
'title' => 'Weekdays: 8 AM - 5 PM',
'description' => 'Emergency veterinary services available during business hours.',

                                    ]
                                ] as $feature)
                                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-6">
                                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $feature['title'] }}</h3>
                                    <p class="text-gray-600">{{ $feature['description'] }}</p>
                                </div>
                                @endforeach
                            </div>
                                    </div>
                    </section>

                    <!-- Gallery Section -->
                    <section id="gallery" class="relative bg-gradient-to-b from-gray-50 to-white py-24">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <!-- Gallery Header -->
                            <div class="text-center max-w-3xl mx-auto mb-20">
                                <span class="inline-block px-4 py-1 rounded-full bg-green-100 text-green-600 text-sm font-semibold mb-4">Our Gallery</span>
                                <h2 class="text-4xl font-bold text-gray-900 sm:text-5xl">See Our Facility & Care</h2>
                                <p class="mt-6 text-xl text-gray-600">Take a look at our modern facilities and the exceptional care we provide</p>
                </div>

                            <!-- Gallery Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                @foreach ([
                                    [
                                        'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2M7 7h10',
                                        'title' => 'Modern Treatment Room',
                                        'description' => 'State-of-the-art equipment for precise diagnosis and treatment'
                                    ],
                                    [
                                        'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                                        'title' => 'Professional Care',
                                        'description' => 'Expert veterinary care with a compassionate touch'
                                    ],
                                    [
                                        'icon' => 'M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z',
                                        'title' => 'Advanced Equipment',
                                        'description' => 'Latest medical technology for optimal treatment'
                                    ],
                                    [
                                        'icon' => 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z',
                                        'title' => 'Recovery Area',
                                        'description' => 'Comfortable spaces for post-treatment care'
                                    ],
                                    [
                                        'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                        'title' => 'Consultation Room',
                                        'description' => 'Private spaces for thorough medical consultations'
                                    ],
                                    [
                                        'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
                                        'title' => 'Emergency Care Unit',
                                        'description' => 'Ready for immediate medical attention'
                                    ]
                                ] as $item)
                                <div class="group relative bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                                    <div class="aspect-w-16 aspect-h-12 bg-gradient-to-br from-green-50 to-green-100">
                                        <div class="p-8 flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-md mb-6 group-hover:scale-110 transition-transform duration-300">
                                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                                                </svg>
                                            </div>
                                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-green-600 transition-colors duration-200">
                                                {{ $item['title'] }}
                                            </h3>
                                            <p class="text-gray-600 text-center text-sm">
                                                {{ $item['description'] }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="absolute inset-0 bg-gradient-to-t from-green-600/75 via-green-500/0 opacity-0 group-hover:opacity-100 transition-all duration-300">
                                        <div class="absolute bottom-0 left-0 right-0 p-6 text-white transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                            <p class="text-sm font-medium">Click to view more</p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- View More Button -->
                            <div class="mt-16 text-center">
                                <button class="inline-flex items-center px-8 py-4 text-lg font-semibold text-white bg-green-600 rounded-xl hover:bg-green-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                                    View Full Gallery
                                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </section>

                    <!-- Update team section -->
                    <section id="team" class="relative bg-gray-50 py-24">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <div class="text-center mb-16">
                                <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Meet Our Expert Team</h2>
                                <p class="mt-4 text-xl text-gray-600">Dedicated professionals committed to your pet's health</p>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                                        @foreach ($veterinarians as $vet)
                                        <div class="bg-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition-all duration-300">
                                            <img class="w-32 h-32 mx-auto rounded-full object-cover border-4 border-green-500" 
                                                 src="{{ $vet->profile_image ? Storage::url($vet->profile_image) : asset('assets/default-avatar.png') }}" 
                                                 alt="{{ $vet->complete_name }}">
                                            <div class="mt-4 text-center">
                                                <h3 class="text-xl font-semibold text-gray-900">{{ $vet->complete_name }}</h3>
                                                <p class="mt-2 text-green-600">{{ $vet->designation->name ?? 'Veterinarian' }}</p>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </section>
                            
                    <!-- Call to Action Section -->
                    <div class="relative bg-green-600 py-16">
                                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <div class="text-center">
                                <h2 class="text-3xl font-bold text-white mb-4">Ready to Give Your Pet the Best Care?</h2>
                                <p class="text-xl text-green-100 mb-8">Join our family of happy pet owners today</p>
                                <a href="/register" class="inline-flex items-center px-8 py-4 text-lg font-semibold text-green-600 bg-white rounded-xl hover:bg-green-50 transform hover:scale-105 transition-all duration-200">
                                    Get Started Now
                                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                                      </div>
                        </div>
                    </div>
                </div>
                <!-- ========== END HERO ========== -->
                
                <!-- Services Section -->
                <section id="services" class="relative bg-gradient-to-b from-gray-50 to-white py-24">
                                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="text-center mb-20">
                            <span class="text-green-600 font-semibold text-sm uppercase tracking-wider">Our Services</span>
                            <h2 class="mt-2 text-4xl md:text-5xl font-bold text-gray-900">Comprehensive Veterinary Services</h2>
                            <p class="mt-4 text-xl text-gray-600">Professional care for livestock and animal welfare</p>
                </div>
          
                        <!-- LPPD Section -->
                        <div class="mb-32">
                            <div class="flex items-center justify-center mb-12">
                                <div class="bg-green-600 h-1 w-16 mr-4"></div>
                                <h3 class="text-3xl font-bold text-gray-900">LPPD Services</h3>
                                <div class="bg-green-600 h-1 w-16 ml-4"></div>
          </div>
                            <p class="text-center text-gray-600 mb-12 max-w-3xl mx-auto">
                                Livestock Production and Pest Diagnosis services focused on maintaining healthy livestock and optimal breeding programs
                            </p>

                            <div class="grid lg:grid-cols-3 gap-10">
                                <!-- LPPD Service 1 -->
                                <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300">
                                    <div class="relative h-64">
                                        <div class="absolute inset-0 bg-gradient-to-r from-green-600 to-green-400 opacity-90"></div>
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <div class="text-center text-white p-6">
                                                <div class="w-16 h-16 mx-auto mb-4 bg-white/20 rounded-full flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                                <h4 class="text-2xl font-bold">Pregnancy Diagnosis</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-8">
                                        <p class="text-gray-600 mb-6">Professional pregnancy testing and monitoring services for livestock with expert evaluation and health tracking.</p>
                                        <ul class="space-y-3">
                                            <li class="flex items-center text-gray-700">
                                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Early Detection Systems
                                            </li>
                                            <li class="flex items-center text-gray-700">
                                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Regular Monitoring
                                            </li>
                                            <li class="flex items-center text-gray-700">
                                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Health Assessment
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- LPPD Service 2 -->
                                <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300">
                                    <div class="relative h-64">
                                        <div class="absolute inset-0 bg-gradient-to-r from-green-600 to-green-400 opacity-90"></div>
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <div class="text-center text-white p-6">
                                                <div class="w-16 h-16 mx-auto mb-4 bg-white/20 rounded-full flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                                    </svg>
                                                </div>
                                                <h4 class="text-2xl font-bold">Artificial Insemination</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-8">
                                        <p class="text-gray-600 mb-6">Professional breeding services with careful selection and timing for optimal results.</p>
                                        <ul class="space-y-3">
                                            <li class="flex items-center text-gray-700">
                                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Breed Selection
                                            </li>
                                            <li class="flex items-center text-gray-700">
                                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Timing Optimization
                                            </li>
                                            <li class="flex items-center text-gray-700">
                                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Success Monitoring
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- LPPD Service 3 -->
                                <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300">
                                    <div class="relative h-64">
                                        <div class="absolute inset-0 bg-gradient-to-r from-green-600 to-green-400 opacity-90"></div>
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <div class="text-center text-white p-6">
                                                <div class="w-16 h-16 mx-auto mb-4 bg-white/20 rounded-full flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                    </svg>
                                                </div>
                                                <h4 class="text-2xl font-bold">Technical Services</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-8">
                                        <p class="text-gray-600 mb-6">Expert technical support and consultation for optimal livestock management.</p>
                                        <ul class="space-y-3">
                                            <li class="flex items-center text-gray-700">
                                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Farm Consultation
                                            </li>
                                            <li class="flex items-center text-gray-700">
                                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Management Advice
                                            </li>
                                            <li class="flex items-center text-gray-700">
                                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Problem Solving
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- AHWD Section -->
                    <div class="bg-white py-16">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <!-- Section Header -->
                            <div class="text-center mb-12">
                                <div class="inline-flex items-center justify-center space-x-2">
                                    <span class="h-px w-8 bg-green-600"></span>
                                    <h3 class="text-2xl font-bold text-gray-900">AHWD Services</h3>
                                    <span class="h-px w-8 bg-green-600"></span>
                        </div>
                                <p class="mt-4 text-gray-600 max-w-2xl mx-auto">
                                    Animal Health and Welfare Division services ensuring comprehensive healthcare for all animals
                        </p>
                            </div>

                            <!-- Services Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ([
                                [
                                        'title' => 'Surveillance & Investigation',
                                        'description' => 'Monitoring and investigation of animal health issues',
                                        'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                        'color' => 'emerald'
                                ],
                                [
                                    'title' => 'Farm Visit',
                                        'description' => 'On-site evaluation and consultation services',
                                        'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                                        'color' => 'green'
                                    ],
                                    [
                                        'title' => 'Vaccination',
                                        'description' => 'Preventive care and immunization programs',
                                        'icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z',
                                        'color' => 'teal'
                                    ],
                                    [
                                        'title' => 'Treatment',
                                        'description' => 'Professional medical care for all conditions',
                                        'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
                                        'color' => 'green'
                                    ],
                                    [
                                        'title' => 'Vitamin Supplementation',
                                        'description' => 'Essential nutrients for optimal health',
                                        'icon' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z',
                                        'color' => 'emerald'
                                    ],
                                    [
                                        'title' => 'Health Certificate',
                                        'description' => 'Official documentation for transport',
                                        'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                                        'color' => 'teal'
                                    ]
                                ] as $service)
                                <div class="group relative bg-white rounded-xl p-6 hover:shadow-lg transition-all duration-300 border border-gray-100">
                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center group-hover:bg-green-100 transition-colors duration-200">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $service['icon'] }}"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-lg font-semibold text-gray-900 group-hover:text-green-600 transition-colors duration-200">
                                                {{ $service['title'] }}
                                            </h4>
                                            <p class="mt-1 text-sm text-gray-500">
                                                {{ $service['description'] }}
                                            </p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <svg class="w-5 h-5 text-gray-400 group-hover:text-green-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Action Button -->
                            <div class="mt-12 text-center">
                                <a href="#contact" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 transition-all duration-200">
                                 
                                    <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ========== END HERO ========== -->
    </body>
    <br>
    <br>
    <br>    
    <footer class="bg-gray-900 text-white py-12">
                      <!-- ========== FOOTER ========== -->
      <footer class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <img class="h-24 w-auto mx-auto mb-8" src="{{ asset('assets/1.jpg') }}" alt="Logo">
          <h3 class="text-2xl font-semibold">City Veterinarians Office</h3>
          <p class="mt-4 text-gray-400">Providing exceptional care for your beloved pets</p>
          <div class="mt-8 flex justify-center space-x-6">
            <a class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-500 hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" href="#">
              <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z"/>
              </svg>
            </a>
            <a class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-500 hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" href="#">
              <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z"/>
              </svg>
            </a>
            <a class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-500 hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" href="#">
              <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z"/>
              </svg>
            </a>
            <a class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-500 hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" href="#">
              <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M3.362 10.11c0 .926-.756 1.681-1.681 1.681S0 11.036 0 10.111C0 9.186.756 8.43 1.68 8.43h1.682v1.68zm.846 0c0-.924.756-1.68 1.681-1.68s1.681.756 1.681 1.68v4.21c0 .924-.756 1.68-1.68 1.68a1.685 1.685 0 0 1-1.682-1.68v-4.21zM5.89 3.362c-.926 0-1.682-.756-1.682-1.681S4.964 0 5.89 0s1.68.756 1.68 1.68v1.682H5.89zm0 .846c.924 0 1.68.756 1.68 1.681S6.814 7.57 5.89 7.57H1.68C.757 7.57 0 6.814 0 5.89c0-.926.756-1.682 1.68-1.682h4.21zm6.749 1.682c0-.926.755-1.682 1.68-1.682.925 0 1.681.756 1.681 1.681s-.756 1.681-1.68 1.681h-1.681V5.89zm-.848 0c0 .924-.755 1.68-1.68 1.68A1.685 1.685 0 0 1 8.43 5.89V1.68C8.43.757 9.186 0 10.11 0c.926 0 1.681.756 1.681 1.68v4.21zm-1.681 6.748c.926 0 1.682.756 1.681 1.681S11.036 16 10.11 16s-1.681-.756-1.681-1.68v-1.682h1.68zm0-.847c-.924 0-1.68-.755-1.68 0-.925.756-1.681 1.68-1.681h4.21c.924 0 1.68.756 1.68 1.68 0 .926-.756 1.681-1.68 1.681h-4.21z"/>
              </svg>
            </a>
          </div>
          <div class="mt-8 text-gray-400">
            <p>© 2024 City Veterinarians Office. All rights reserved.</p>
          </div>
        </div>
      </footer>
      <!-- ========== END FOOTER ========== -->
  
    </footer>

    <!-- Add this script at the end of your body tag -->
    <script>
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            const menuOpen = document.getElementById('menuOpen');
            const menuClose = document.getElementById('menuClose');
            
            mobileMenu.classList.toggle('hidden');
            menuOpen.classList.toggle('hidden');
            menuClose.classList.toggle('hidden');
        }

        // Close mobile menu when clicking on a link
        document.querySelectorAll('#mobileMenu a').forEach(link => {
            link.addEventListener('click', () => {
                document.getElementById('mobileMenu').classList.add('hidden');
                document.getElementById('menuOpen').classList.remove('hidden');
                document.getElementById('menuClose').classList.add('hidden');
            });
        });
    </script>
</html>
