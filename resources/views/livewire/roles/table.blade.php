<div>
    <x-table :headers="$this->headers" :rows="$rows" id="roles" paginate skelton filter :quantity="[1, 10, 50, 100]" >
        <x-slot:header>
            <x-accordion chevron="left">
                <x-accordion.items title="Filters" icon="funnel" id="filters-accordion">
                    {{-- deleted, all records filter --}}
                    <x-select.styled label="Show" wire:model.live='recordState' :options="$this->recordStates" required />
                </x-accordion.items>
            </x-accordion>
            <div class="mb-3"></div>
        </x-slot>
        @interact('column_users', $row)
            {{ $row['users']->count() }}
        @endinteract
        @interact('column_permissions', $row)
            {{ $row['permissions']->count() }}
        @endinteract
        @interact('column_action', $row)
            @if ($row['deleted_at'])
                @can ('DELETE_ROLE')
                    <x-button.circle color="emerald" icon="arrow-path-rounded-square"
                        wire:click="restore('{{ $row['id'] }}')" wire:loading.attr="disabled" x-data x-tooltip="Restore"
                        data-tooltip-delay="slow">
                    </x-button.circle>
                @endcan
            @else
                @can ('DELETE_ROLE')
                    <x-button.circle color="red" icon="trash" wire:click="delete('{{ $row['id'] }}')"
                        wire:loading.attr="disabled" x-data x-tooltip="Delete" data-tooltip-delay="slow">
                    </x-button.circle>
                @endcan
                @can ('UPDATE_ROLE')
                    <x-button.circle color="blue" icon="pencil-square" wire:navigate wire:loading.attr="disabled"
                        href="{{ route('roles.update', $row['id']) }}" x-data x-tooltip="Edit" data-tooltip-delay="slow">
                    </x-button.circle>
                @endcan
                {{-- <x-button.circle color="violet" icon="information-circle" wire:navigate wire:loading.attr="disabled"
                    href="{{ route('roles.view', $row['id']) }}" x-data x-tooltip="View" data-tooltip-delay="slow">
                </x-button.circle> --}}
            @endif
        @endinteract
    </x-table>
</div>
