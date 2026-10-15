<x-layouts::errors>
    <div class="flex justify-center-safe items-center h-full">
        <div class="text-center">
            <x-card :header="$exception->getMessage()" color="red" accent>
                You don't have permissions to access this section!
                <x-slot:footer>
                    <x-button href="{{ url()->previous() }}" icon="arrow-uturn-left" wire:navigate>Back</x-button>
                    <x-button href="{{ route('dashboard') }}" icon="home" wire:navigate>Dashboard</x-button>
                </x-slot:footer>
            </x-card>
        </div>
    </div>
</x-layouts::errors>
