<div>
    {{-- todo: ask ai to review the code --}}
    <form wire:submit='save'>
        <div class="mb-3">
            {{-- fix: photo upload and preview --}}
            @if ($this->photo || $this->user->getAvatar())
                <x-avatar color="primary" :image="$this->photo?->temporaryUrl()??$this->user->getAvatar()" borderless />
            @endif
            <x-upload label="Avatar" wire:model='photo' />
        </div>
        <div class="mb-3">
            <x-input label="Name *" wire:model.live='name' placeholder="Account Name" />
        </div>
        <div class="mb-3">
            <x-input label="Email *" wire:model.live='email' placeholder="Account Email" />
        </div>
        <div class="mb-3">
            <x-checkbox label="Update password" wire:model.live='update_password' value="1" />
            @if ($this->update_password)
                <x-password label="Password *" wire:model.live='password' placeholder="Password" />
            @endif
        </div>
        <div class="mb-3">
            <x-date label="DOB *" wire:model.live='birthday' placeholder="Select birthday" format="YYYY-MM-DD" />
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
