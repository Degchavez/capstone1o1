<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
};
?>

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ 
                        auth()->check() 
                            ? (auth()->user()->role == 0 
                                ? route('admin-dashboard') 
                                : (auth()->user()->role == 1 
                                    ? route('owner-dashboard') 
                                    : (auth()->user()->role == 2 
                                        ? route('vet-dashboard') 
                                        : (auth()->user()->role == 3 
                                            ? route('receptionist-dashboard') 
                                            : route('dashboard'))))) 
                            : route('login') }}">
                        <img src="{{ asset('assets/1.jpg') }}" alt="Application Logo" class="h-12 w-auto hover:scale-105 transition-transform duration-400" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="flex flex-wrap items-center space-x-4 sm:space-x-8 sm:ms-10 hidden sm:flex hover:scale-105 transition-transform duration-400">
            
                                                  <!-- Admin Links -->

                    @if(auth()->check() && auth()->user()->role == 0) <!-- Assuming 0 is the admin role -->
                        <x-nav-link :href="route('admin-dashboard')" :active="request()->routeIs('admin-dashboard')" wire:navigate>
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin-users')" :active="request()->routeIs('admin-users')" wire:navigate>
                            {{ __('Users') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin-owners')" :active="request()->routeIs('admin-owners')" wire:navigate>
                            {{ __('Owners') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin-animals')" :active="request()->routeIs('admin-animals')" wire:navigate>
                            {{ __('Animals') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin-veterinarians')" :active="request()->routeIs('admin-veterinarians')" wire:navigate>
                            {{ __('Veterinarians') }}
                        </x-nav-link>
                        
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        Manage
                                        <svg class="ml-2 h-4 w-4 text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M5 7l5 5 5-5"></path>
                                        </svg>
                                    </button>
                                </x-slot>
                        
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('admin-technicians')" wire:navigate>
                                        {{ __('Technicians') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('vaccines.load')" wire:navigate>
                                        {{ __('Vaccines') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('barangay.load')" wire:navigate>
                                        {{ __('Barangays') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('species.breed')" wire:navigate>
                                        {{ __('Species & Breeds') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('subtype.index')" wire:navigate>
                                        {{ __('Transactions') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('designation.index')" wire:navigate>
                                        {{ __('Designations') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                        
                        {{-- <x-nav-link :href="route('admin-veterinarians')" :active="request()->routeIs('admin-veterinarians')" wire:navigate>
                            {{ __('Veterinarians') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin-settings')" :active="request()->routeIs('admin-settings')" wire:navigate>
                            {{ __('Settings') }}
                        </x-nav-link> --}}
                        
                        
                    @endif
                     <!-- Owner Navigation -->
                     @if(auth()->check() && auth()->user()->role == 1) <!-- Assuming 1 is the owner role -->
                     <x-nav-link :href="route('owner-dashboard')" :active="request()->routeIs('owner-dashboard')" wire:navigate>
                         {{ __('Dashboard') }}
                     </x-nav-link>
                 
                     <x-nav-link :href="route('owners.profile', ['owner_id' => auth()->user()->owner->owner_id])"
                        :active="request()->routeIs('owners.profile')"
                        wire:navigate>
                        {{ __('Profile') }}
                    </x-nav-link>
                    
                 @endif
                 
                     <!-- Vet Navigation -->

                     @if(auth()->check() && auth()->user()->role == 2) <!-- Assuming 0 is the admin role -->
                     <x-nav-link :href="route('vet-dashboard')" :active="request()->routeIs('vet-dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-nav-link>

                     
                    <x-nav-link :href="route('vet.veterinarian.profile', ['user_id' => auth()->user()->user_id])"
                        :active="request()->routeIs('vet.veterinarian.profile')"
                        wire:navigate>
                        {{ __('Profile') }}
                    </x-nav-link>
                     @endif
                    
                      <!-- Receptionist Navigation -->

                    @if(auth()->check() && auth()->user()->role == 3) <!-- Assuming 0 is the admin role -->
                    <x-nav-link :href="route('receptionist-dashboard')" :active="request()->routeIs('receptionist-dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('rec-owners')" :active="request()->routeIs('rec-owners')" wire:navigate>
                        {{ __('Owners') }}
                    </x-nav-link>
                    <x-nav-link :href="route('rec-animals')" :active="request()->routeIs('rec-animals')" wire:navigate>
                        {{ __('Animals') }}
                    </x-nav-link>
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    Manage
                                    <svg class="ml-2 h-4 w-4 text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 7l5 5 5-5"></path>
                                    </svg>
                                </button>
                            </x-slot>
                    
                            <x-slot name="content">
                                <x-dropdown-link :href="route('rec-technicians')" wire:navigate>
                                    {{ __('Technicians') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('recvaccines.load')" wire:navigate>
                                    {{ __('Vaccines') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('newbarangay.load')" wire:navigate>
                                    {{ __('Barangays') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('recspecies.breed')" wire:navigate>
                                    {{ __('Species & Breeds') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('recsubtype.index')" wire:navigate>
                                    {{ __('Transactions') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('recdesignation.index')" wire:navigate>
                                    {{ __('Designations') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    @endif
                </div>
            </div>
            @if(auth()->check() && auth()->user()->role == 0) <!-- Assuming 0 is the admin role -->
            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('assets/default-avatar.png') }}" 
                                 alt="{{ auth()->user()->complete_name }}" 
                                 class="h-8 w-8 rounded-full me-2" />
                            <div x-data="{{ json_encode(['complete_name' => auth()->user()->complete_name]) }}" 
                                x-text="complete_name" 
                                x-on:profile-updated.window="complete_name = $event.detail.complete_name"></div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

  
                    <x-slot name="content">
                        <x-dropdown-link :href="route('users.nav-profile', ['id' => auth()->user()->user_id])" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('users.settings')" wire:navigate>
                            {{ __('Settings') }}
                        </x-dropdown-link>
                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                
                </x-dropdown>
            </div>
            @endif

            @if(auth()->check() && auth()->user()->role == 1) <!-- Assuming 0 is the admin role -->
            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('assets/default-avatar.png') }}" 
                                 alt="{{ auth()->user()->complete_name }}" 
                                 class="h-8 w-8 rounded-full me-2" />
                            <div x-data="{{ json_encode(['complete_name' => auth()->user()->complete_name]) }}" 
                                x-text="complete_name" 
                                x-on:profile-updated.window="complete_name = $event.detail.complete_name"></div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

  
                    <x-slot name="content">
                        <x-dropdown-link :href="route('owners.settings')" wire:navigate>
                            {{ __('Settings') }}
                        </x-dropdown-link>
                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                
                </x-dropdown>
            </div>
            @endif
            @if(auth()->check() && auth()->user()->role == 2) <!-- Assuming 0 is the admin role -->
            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('assets/default-avatar.png') }}" 
                                 alt="{{ auth()->user()->complete_name }}" 
                                 class="h-8 w-8 rounded-full me-2" />
                            <div x-data="{{ json_encode(['complete_name' => auth()->user()->complete_name]) }}" 
                                x-text="complete_name" 
                                x-on:profile-updated.window="complete_name = $event.detail.complete_name"></div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

  
                    <x-slot name="content">
                        <x-dropdown-link :href="route('vet.settings')" wire:navigate>
                            {{ __('Settings') }}
                        </x-dropdown-link>
                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                
                </x-dropdown>
            </div>
            @endif
            @if(auth()->check() && auth()->user()->role == 3) <!-- Assuming 0 is the admin role -->
            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('assets/default-avatar.png') }}" 
                                 alt="{{ auth()->user()->complete_name }}" 
                                 class="h-8 w-8 rounded-full me-2" />
                            <div x-data="{{ json_encode(['complete_name' => auth()->user()->complete_name]) }}" 
                                x-text="complete_name" 
                                x-on:profile-updated.window="complete_name = $event.detail.complete_name"></div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

  
                    <x-slot name="content">
                        <x-dropdown-link :href="route('rec.settings')" wire:navigate>
                            {{ __('Settings') }}
                        </x-dropdown-link>
                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                
                </x-dropdown>
            </div>
            @endif
            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('assets/default-profile.png') }}" 
                     alt="{{ auth()->user()->complete_name }}" 
                     class="h-8 w-8 rounded-full mb-2" />
                <div class="font-medium text-base text-gray-800" x-data="{{ json_encode(['complete_name' => auth()->user()->complete_name]) }}" 
                     x-text="complete_name" 
                     x-on:profile-updated.window="complete_name = $event.detail.complete_name"></div>
                <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
