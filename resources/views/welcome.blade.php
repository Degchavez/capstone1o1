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
        class="antialiased font-sans" 
        style="background-image: url('{{ asset('assets/bg.jpg') }}'); 
               background-size: cover; 
               background-position: center; 
               background-attachment: fixed;">

                <!-- ========== HEADER ========== -->
                <livewire:components_landing.top-bar-navigation />
                <!-- ========== END HEADER ========== -->
                <!-- ========== HERO ========== -->
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <livewire:components_landing.landing-page-body />  
                     
                    </div>
                </div>
                <div class="py-12 bg-gray-100">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            
                            <section id="team" class="py-10">
                                <div class="max-w-5xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
                                    <div class="text-center mb-8">
                                        <a href="/">
                                            <img class="h-24 w-auto mx-auto" src="{{ asset('assets/1.jpg') }}" alt="Your Logo">
                                        </a>
                                    </div>
                                    <div class="max-w-2xl mx-auto text-center mb-10 lg:mb-14">
                                        <h2 class="text-2xl font-bold md:text-4xl md:leading-tight dark:text-white">Meet Our Veterinarians</h2>
                                        <p class="mt-1 text-gray-600 dark:text-neutral-400">The professionals</p>
                                    </div>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-8 md:gap-12">
                                        @foreach ($veterinarians as $vet)
                                            <div class="text-center">
                                                <img 
                                                    class="w-36 h-36 object-cover rounded-full border-4 border-green-500 shadow-lg hover:scale-105 transition-transform duration-300" 
                                                    src="{{ $vet->profile_image ? Storage::url($vet->profile_image) : asset('assets/default-avatar.png') }}" 
                                                    alt="{{ $vet->complete_name }}">
                                                
                                                <div class="mt-2 sm:mt-4">
                                                    <h3 class="font-medium text-gray-800 dark:text-neutral-200">
                                                        {{ $vet->complete_name }}
                                                    </h3>
                                                    <p class="text-sm text-gray-600 dark:text-neutral-400">
                                                        {{ $vet->designation->name ?? 'Veterinarian' }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    
                                </div>
                            </section>
                            
                    </div>
                </div>
                <div class="py-12 bg-gray-100">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            
                            <livewire:components_landing.feature-section /> 
                    </div>
                </div>
                <div class="py-12 bg-gray-100">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            
                            <livewire:components_landing.gallery-section /> 
                    </div>
                </div>
      
      
      
                <!-- ========== END HERO ========== -->
    </body>
    <br>
    <br>
    <br>    
    <footer>
                    <livewire:components_landing.footer-section /> 
  
    </footer>
</html>
