<div>
    <form wire:submit='login'>
        @csrf

        <!-- Email Address -->
        <div>
            <x-input label="{{ __('Email *') }}" wire:model.live.debounce.250ms='email' :error="$errors->first('email')" />
        </div>

        <!-- Password -->
        <div class="mt-4">
           <x-password label="{{ __('Password *') }}" wire:model.live.debounce.250ms='password' :error="$errors->first('password')" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <x-checkbox label="{{ __('Remember me') }}" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <div class="ms-2">
                <x-button text="{{ __('Login') }}" submit />
            </div>
        </div>
    </form>
</div>
