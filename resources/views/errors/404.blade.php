<x-layouts::errors>
    <div class="flex justify-center-safe items-center h-full">
        <div class="text-center">
            <x-card header="Err:404 - Not Found!" color="red" accent>
                {{ $exception->getMessage() }}
                <x-slot:footer>
                    <x-button href="{{ url()->previous() }}" icon="arrow-uturn-left" wire:navigate>Back</x-button>
                    <x-button href="{{ route('dashboard') }}" icon="home" wire:navigate>Dashboard</x-button>
                </x-slot:footer>
            </x-card>
        </div>
    </div>
</x-layouts::errors>
