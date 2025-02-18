<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();
        if (auth()->user()->role == 0) {
            $this->redirectIntended(default: route('admin-dashboard', absolute: false), navigate: true);
        } elseif (auth()->user()->role == 1) {
            $this->redirectIntended(default: route('owner-dashboard', absolute: false), navigate: true);
        } elseif (auth()->user()->role == 2) {
            $this->redirectIntended(default: route('vet-dashboard', absolute: false), navigate: true);
        } elseif (auth()->user()->role == 3) {
            $this->redirectIntended(default: route('receptionist-dashboard', absolute: false), navigate: true);
        }
        

    }
}; ?>
<div class="min-h-screen bg-cover bg-center" style="background-image: url('{{ asset('assets/bg3.jpg') }}');">
    <!-- This outer div now has the background image, covering the whole screen -->
    <div class="flex justify-center items-center h-full">
        <div class="w-full max-w-md space-y-8">
            <!-- The login form content -->
            <div class="text-center mb-8">
                <a href="/">
                    <img class="h-24 w-auto mx-auto hover:scale-105 transition-transform duration-400" src="{{ asset('assets/1.jpg') }}" alt="Your Logo">
                </a>
            </div>

            <!-- Session Status Message -->
            @if (session('message'))
                <div class="text-center text-red-600 mb-4">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit="login">
                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full" type="email" name="email" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember" class="inline-flex items-center">
                        <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}" wire:navigate>
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <x-primary-button class="ms-3 hover:bg-green-100 hover:text-green-500">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>

                <div class="text-center mt-6">
                    <p class="text-sm text-gray-600">
                        {{ __('Don\'t have an account?') }}
                        <a href="{{ route('register') }}" class="text-green-600 hover:text-green-800 font-semibold">
                            {{ __('Create one now!') }}
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
