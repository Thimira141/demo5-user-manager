<div>
    <form wire:submit='save'>
        <div class="mb-3">
            <x-input label="Name *" wire:model.live='name' placeholder="Account Name" />
        </div>
        <div class="mb-3">
            <x-input label="Email *" wire:model.live='email' placeholder="Account Email" />
        </div>
        <div class="mb-3">
            @if (!$this->userId)
                {{-- new account --}}
                <input type="hidden" name="update_password" wire:model.live='update_password' value="1">
            @else
                {{-- for old account --}}
                <div class="mb-2 {{ !$this->userId ? 'invisible' : null }}">
                    <x-checkbox label="Update password" wire:model.live='update_password' value="1" />
                </div>
            @endif
            @if ($this->update_password || !$this->userId)
                <x-password label="Password *" wire:model.live='password' placeholder="Password" />
            @endif
        </div>
        <div class="mb-3">
            <x-date label="DOB *" wire:model.live='birthday' placeholder="Select birthday" format="YYYY-MM-DD" />
        </div>
        {{-- roles setup --}}
        <div class="mb-3">
            <x-select.styled label="Roles *" placeholder="Please select role(s)" wire:model.live='roles'
                :options="$this->rolesData" select="label:name|value:id|description:description" lazy="10" multiple
                searchable />
        </div>
        <div class="mb-3">
            <x-textarea label="Notes" wire:model.live='notes' placeholder="Notes for user account" />
        </div>
        <x-button submit text="Save" x-data x-tooltip="Save Data" wire:loading.attr="disabled" />
        <div wire:loading>
            Saving account...
        </div>
    </form>
</div>
