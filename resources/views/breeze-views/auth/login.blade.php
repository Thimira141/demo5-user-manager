<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input label="{{ __('Email *') }}" />
        </div>

        <!-- Password -->
        <div class="mt-4">
           <x-password label="{{ __('Password *') }}" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <x-checkbox label="{{ __('Remember me') }}" />
        </div>

        <div class="flex items-center justify-end mt-4">
            {{-- @if (Route::has('password.request'))
            <div class="me-2">
                <x-link wire:navigate href="{{ route('password.request') }}" text="{{ __('Forgot your password?') }}" underline />
            </div>
            @endif --}}
            <div class="ms-2">
                <x-button text="{{ __('Login') }}" submit />
            </div>
        </div>
    </form>
</x-guest-layout>
