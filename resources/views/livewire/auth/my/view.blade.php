<div>
    <div class="mb-3 pb-3 border-b-2">
        <x-button text="Edit Profile" href="{{ route('profile.edit') }}" wire:navigate />
    </div>
    <div class="mb-3">
        <x-input label="Name *" placeholder="Account Name" readonly value="{{ $this->user->name }}" />
    </div>
    <div class="mb-3">
        <x-input label="Email *" placeholder="Account Email" readonly value="{{ $this->user->email }}" />
    </div>
    <div class="mb-3">
        <x-input label="DOB *" placeholder="Select birthday" readonly value="{{ $this->user->birthday }}" />
    </div>
    {{-- roles setup --}}
    <div class="mb-3">
        <x-input label="Roles ({{ count($this->roles) }}) *" readonly>
            <x-slot:prefix button>
                @foreach ($this->roles as $role)
                    <x-badge :text="$role['name']" x-data :x-tooltip="$role['description']" />
                @endforeach
            </x-slot:prefix>
        </x-input>
    </div>
    <div class="mb-3">
        <x-textarea label="Notes" placeholder="Notes for user account" readonly value="{{ $this->user->notes }}" />
    </div>

    <x-list label="Permissions ({{ count($this->permissions) }})" skelton searchable>
        @foreach ($this->permissions as $permission)
            <x-list.items name="{{ $permission['name'] }}" caption="{{ $permission['description'] }}" />
        @endforeach
    </x-list>
</div>
