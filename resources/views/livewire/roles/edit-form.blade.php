<div>
    <form wire:submit='save'>
        <div class="mb-3">
            <x-input label="Name *" wire:model.live='name' placeholder="Role Name" />
        </div>
        <div class="mb-3">
            <x-select.styled label="Permissions *" placeholder="Please select permission(s)" wire:model.live='permissions'
                :options="$this->permissionOptions" select="label:name|value:id|description:description" lazy="10" multiple
                searchable />
        </div>
        <div class="mb-3">
            <x-textarea label="Description" wire:model.live='description' placeholder="Description" />
        </div>
        <x-button submit text="Save" x-data x-tooltip="Save Data" wire:loading.attr="disabled" />
        <div wire:loading>
            Saving account...
        </div>
    </form>
</div>
