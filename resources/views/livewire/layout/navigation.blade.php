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

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
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
                            : route('login') }}"
                       class="transform hover:scale-105 transition-all duration-300">
                        <img src="{{ asset('assets/1.jpg') }}" alt="Application Logo" class="h-12 w-auto rounded-lg shadow-sm" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:flex sm:items-center sm:space-x-6 sm:ms-10">
                    @if(auth()->check() && auth()->user()->role == 0)
                        <x-nav-link :href="route('admin-dashboard')" :active="request()->routeIs('admin-dashboard')" 
                            class="px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 hover:bg-green-50 hover:text-green-600" wire:navigate>
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin-users')" :active="request()->routeIs('admin-users')" 
                            class="px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 hover:bg-green-50 hover:text-green-600" wire:navigate>
                            {{ __('Users') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin-owners')" :active="request()->routeIs('admin-owners')" 
                            class="px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 hover:bg-green-50 hover:text-green-600" wire:navigate>
                            {{ __('Owners') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin-animals')" :active="request()->routeIs('admin-animals')" 
                            class="px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 hover:bg-green-50 hover:text-green-600" wire:navigate>
                            {{ __('Animals') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin-veterinarians')" :active="request()->routeIs('admin-veterinarians')" 
                            class="px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 hover:bg-green-50 hover:text-green-600" wire:navigate>
                            {{ __('Veterinarians') }}
                        </x-nav-link>
                        
                        <!-- Manage Dropdown -->
                        <div class="hidden sm:flex sm:items-center">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-600 focus:outline-none transition-all duration-300">
                                        Manage
                                        <svg class="ml-2 h-4 w-4 transition-transform duration-300" 
                                             :class="{'rotate-180': open}"
                                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7l5 5 5-5"/>
                                        </svg>
                                    </button>
                                </x-slot>
                        
                                <x-slot name="content">
                                    <div class="p-1">
                                        <x-dropdown-link :href="route('admin-technicians')" 
                                            class="px-4 py-2 text-sm rounded-lg hover:bg-green-50 hover:text-green-600 transition-all duration-300" wire:navigate>
                                            {{ __('Technicians') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('vaccines.load')"
                                            class="px-4 py-2 text-sm rounded-lg hover:bg-green-50 hover:text-green-600 transition-all duration-300" wire:navigate>
                                            {{ __('Vaccines') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('barangay.load')"
                                            class="px-4 py-2 text-sm rounded-lg hover:bg-green-50 hover:text-green-600 transition-all duration-300" wire:navigate>
                                            {{ __('Barangays') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('species.breed')"
                                            class="px-4 py-2 text-sm rounded-lg hover:bg-green-50 hover:text-green-600 transition-all duration-300" wire:navigate>
                                            {{ __('Species & Breeds') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('subtype.index')"
                                            class="px-4 py-2 text-sm rounded-lg hover:bg-green-50 hover:text-green-600 transition-all duration-300" wire:navigate>
                                            {{ __('Transactions') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('designation.index')"
                                            class="px-4 py-2 text-sm rounded-lg hover:bg-green-50 hover:text-green-600 transition-all duration-300" wire:navigate>
                                            {{ __('Designations') }}
                                        </x-dropdown-link>
                                    </div>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif

                    <!-- Owner Navigation -->
                    @if(auth()->check() && auth()->user()->role == 1)
                        <x-nav-link :href="route('owner-dashboard')" :active="request()->routeIs('owner-dashboard')"
                            class="px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 hover:bg-green-50 hover:text-green-600" wire:navigate>
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('owners.profile', ['owner_id' => auth()->user()->owner->owner_id])"
                            :active="request()->routeIs('owners.profile')"
                            class="px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 hover:bg-green-50 hover:text-green-600" wire:navigate>
                            {{ __('Profile') }}
                        </x-nav-link>
                    @endif

                    <!-- Vet Navigation -->
                    @if(auth()->check() && auth()->user()->role == 2)
                        <x-nav-link :href="route('vet-dashboard')" :active="request()->routeIs('vet-dashboard')"
                            class="px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 hover:bg-green-50 hover:text-green-600" wire:navigate>
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('vet.veterinarian.profile', ['user_id' => auth()->user()->user_id])"
                            :active="request()->routeIs('vet.veterinarian.profile')"
                            class="px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 hover:bg-green-50 hover:text-green-600" wire:navigate>
                            {{ __('Profile') }}
                        </x-nav-link>
                        <x-nav-link :href="route('reports.index')"
                            :active="request()->routeIs('reports.index')"
                            class="px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 hover:bg-green-50 hover:text-green-600">
                            {{ __('Generate Report') }}
                        </x-nav-link>
                    @endif

                    <!-- Receptionist Navigation -->
                    @if(auth()->check() && auth()->user()->role == 3)
                        <x-nav-link :href="route('receptionist-dashboard')" :active="request()->routeIs('receptionist-dashboard')"
                            class="px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 hover:bg-green-50 hover:text-green-600" wire:navigate>
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('rec-owners')" :active="request()->routeIs('rec-owners')"
                            class="px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 hover:bg-green-50 hover:text-green-600" wire:navigate>
                            {{ __('Owners') }}
                        </x-nav-link>
                        <x-nav-link :href="route('rec-animals')" :active="request()->routeIs('rec-animals')"
                            class="px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 hover:bg-green-50 hover:text-green-600" wire:navigate>
                            {{ __('Animals') }}
                        </x-nav-link>
                        <x-nav-link :href="route('rec-animals')" :active="request()->routeIs('rec-animals')"
                            class="px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 hover:bg-green-50 hover:text-green-600" wire:navigate>
                            {{ __('Generate Report') }}
                        </x-nav-link>


                        <!-- Receptionist Manage Dropdown -->
                        <div class="hidden sm:flex sm:items-center">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-600 focus:outline-none transition-all duration-300">
                                        Manage
                                        <svg class="ml-2 h-4 w-4 transition-transform duration-300" 
                                             :class="{'rotate-180': open}"
                                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7l5 5 5-5"/>
                                        </svg>
                                    </button>
                                </x-slot>
                        
                                <x-slot name="content">
                                    <div class="p-1">
                                        <x-dropdown-link :href="route('rec-technicians')"
                                            class="px-4 py-2 text-sm rounded-lg hover:bg-green-50 hover:text-green-600 transition-all duration-300" wire:navigate>
                                            {{ __('Technicians') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('recvaccines.load')"
                                            class="px-4 py-2 text-sm rounded-lg hover:bg-green-50 hover:text-green-600 transition-all duration-300" wire:navigate>
                                            {{ __('Vaccines') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('newbarangay.load')"
                                            class="px-4 py-2 text-sm rounded-lg hover:bg-green-50 hover:text-green-600 transition-all duration-300" wire:navigate>
                                            {{ __('Barangays') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('recspecies.breed')"
                                            class="px-4 py-2 text-sm rounded-lg hover:bg-green-50 hover:text-green-600 transition-all duration-300" wire:navigate>
                                            {{ __('Species & Breeds') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('recsubtype.index')"
                                            class="px-4 py-2 text-sm rounded-lg hover:bg-green-50 hover:text-green-600 transition-all duration-300" wire:navigate>
                                            {{ __('Transactions') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('recdesignation.index')"
                                            class="px-4 py-2 text-sm rounded-lg hover:bg-green-50 hover:text-green-600 transition-all duration-300" wire:navigate>
                                            {{ __('Designations') }}
                                        </x-dropdown-link>
                                    </div>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            @if(auth()->check())
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-lg text-gray-600 hover:bg-green-50 hover:text-green-600 focus:outline-none transition-all duration-300">
                            <div class="flex items-center gap-2">
                                <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('assets/default-avatar.png') }}" 
                                     alt="{{ auth()->user()->complete_name }}" 
                                     class="h-8 w-8 rounded-full object-cover ring-2 ring-gray-100" />
                                <div x-data="{{ json_encode(['complete_name' => auth()->user()->complete_name]) }}" 
                                    x-text="complete_name" 
                                    x-on:profile-updated.window="complete_name = $event.detail.complete_name"
                                    class="max-w-[150px] truncate"></div>
                                <svg class="h-4 w-4 transition-transform duration-300" 
                                     :class="{'rotate-180': open}"
                                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7l5 5 5-5"/>
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="p-1">
                            @if(auth()->user()->role == 0)
                                <x-dropdown-link :href="route('users.nav-profile', ['id' => auth()->user()->user_id])"
                                    class="px-4 py-2 text-sm rounded-lg hover:bg-green-50 hover:text-green-600 transition-all duration-300" wire:navigate>
                                    {{ __('Profile') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('users.settings')"
                                    class="px-4 py-2 text-sm rounded-lg hover:bg-green-50 hover:text-green-600 transition-all duration-300" wire:navigate>
                                    {{ __('Settings') }}
                                </x-dropdown-link>
                            @elseif(auth()->user()->role == 1)
                                <x-dropdown-link :href="route('owners.settings')"
                                    class="px-4 py-2 text-sm rounded-lg hover:bg-green-50 hover:text-green-600 transition-all duration-300" wire:navigate>
                                    {{ __('Settings') }}
                                </x-dropdown-link>
                            @elseif(auth()->user()->role == 2)
                                <x-dropdown-link :href="route('vet.settings')"
                                    class="px-4 py-2 text-sm rounded-lg hover:bg-green-50 hover:text-green-600 transition-all duration-300" wire:navigate>
                                    {{ __('Settings') }}
                                </x-dropdown-link>
                            @elseif(auth()->user()->role == 3)
                                <x-dropdown-link :href="route('rec.settings')"
                                    class="px-4 py-2 text-sm rounded-lg hover:bg-green-50 hover:text-green-600 transition-all duration-300" wire:navigate>
                                    {{ __('Settings') }}
                                </x-dropdown-link>
                            @endif

                            <button wire:click="logout" class="w-full text-start">
                                <x-dropdown-link class="px-4 py-2 text-sm rounded-lg hover:bg-red-50 hover:text-red-600 transition-all duration-300">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </button>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>
            @endif

            <!-- Hamburger Menu -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" 
                    class="inline-flex items-center justify-center p-2 rounded-lg text-gray-500 hover:text-gray-600 hover:bg-gray-100 focus:outline-none transition duration-300">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" 
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" 
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if(auth()->check())
                @if(auth()->user()->role == 0)
                    <x-responsive-nav-link :href="route('admin-dashboard')" :active="request()->routeIs('admin-dashboard')"
                        class="block px-4 py-2 text-sm hover:bg-green-50 hover:text-green-600 transition-all duration-300" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    <!-- Add other admin mobile links -->
                @elseif(auth()->user()->role == 1)
                    <x-responsive-nav-link :href="route('owner-dashboard')" :active="request()->routeIs('owner-dashboard')"
                        class="block px-4 py-2 text-sm hover:bg-green-50 hover:text-green-600 transition-all duration-300" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    <!-- Add other owner mobile links -->
                @endif
                <!-- Add other role-specific mobile links -->
            @endif
        </div>

        <!-- Mobile Settings -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4 flex items-center">
                <div class="flex-shrink-0">
                    <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('assets/default-avatar.png') }}" 
                         alt="{{ auth()->user()->complete_name }}" 
                         class="h-10 w-10 rounded-full object-cover ring-2 ring-gray-100" />
                </div>
                <div class="ms-3">
                    <div class="font-medium text-base text-gray-800" 
                         x-data="{{ json_encode(['complete_name' => auth()->user()->complete_name]) }}" 
                         x-text="complete_name" 
                         x-on:profile-updated.window="complete_name = $event.detail.complete_name"></div>
                    <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')"
                    class="block px-4 py-2 text-sm hover:bg-green-50 hover:text-green-600 transition-all duration-300" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link class="block px-4 py-2 text-sm hover:bg-red-50 hover:text-red-600 transition-all duration-300">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
