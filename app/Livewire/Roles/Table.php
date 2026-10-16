<?php

namespace App\Livewire\Roles;

use App\Models\Role;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use TallStackUi\Traits\Interactions;

#[Title('Role Roles')]
class Table extends Component
{
    use Interactions, WithPagination;

    /**
     * rows per page in table pagination
     */
    public int $quantity = 10;

    /**
     * search text
     */
    public ?string $search = null;

    /**
     * define softDeleted state
     */
    public string $recordState = 'withoutTrashed';

    /** @var list<array{value: string, label: string}> */
    public array $recordStates = [
        ['value' => 'withoutTrashed', 'label' => 'Except Deleted'],
        ['value' => 'trashed', 'label' => 'Only Deleted'],
        ['value' => 'withTrashed', 'label' => 'All (with deleted)'],
    ];

    /**
     * users list table headers define
     */
    /** @var list<array{index: string, label?: string}> */
    public array $headers = [
        ['index' => 'name', 'label' => 'Name'],
        ['index' => 'users', 'label' => 'Users'],
        ['index' => 'permissions', 'label' => 'Permissions'],
        ['index' => 'action'],
    ];

    /**
     * delete role, confirm dialog
     *
     * @return void
     *
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function delete(int $id)
    {
        $this->authorize('DELETE_ROLE');

        $role = Role::find($id, ['id', 'name']);
        if (! $role) {
            $this->dialog()->error('Role Not Found!')->send();
        }
        $this->dialog()
            ->question('Delete', "Confirm delete role:\n {$role->name}")
            ->confirm('Delete', 'confirmedDelete', $role->id)
            ->cancel('Cancel')
            ->send();
    }

    /**
     * confirmed delete, init from delete -> confirm
     *
     * @param  int  $id  role->id
     * @return void
     *
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function confirmedDelete(int $id)
    {
        $this->authorize('DELETE_ROLE');

        $role = Role::find($id, ['id', 'name']);
        if (! $role) {
            $this->dialog()->error('Role Not Found!')->send();
        }
        if ($role->delete()) {
            $this->dialog()->success('Deleted!', 'Role deleted!')->send();
        } else {
            $this->dialog()->error('Failed!', 'Role delete failed!')->send();
        }
    }

    /**
     * restore role, confirm dialog
     *
     * @return void
     *
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function restore(int $id)
    {
        $this->authorize('DELETE_ROLE');

        $role = Role::withTrashed()->find($id, ['id', 'name']);
        if (! $role) {
            $this->dialog()->error('Role Not Found!')->send();
        }
        $this->dialog()
            ->question('Restore', "Confirm restore role:\n {$role->name}")
            ->confirm('Restore', 'confirmedRestore', $role->id)
            ->cancel('Cancel')
            ->send();
    }

    /**
     * confirmed restore, init from restore -> confirm
     *
     * @param  int  $id  role->id
     * @return void
     *
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function confirmedRestore(int $id)
    {
        $this->authorize('DELETE_ROLE');

        $role = Role::withTrashed()->find($id, ['id', 'name']);
        if (! $role) {
            $this->dialog()->error('Role Not Found!')->send();
        }
        if ($role->restore()) {
            $this->dialog()->success('Restored!', 'Role restored!')->send();
        } else {
            $this->dialog()->error('Failed!', 'Role restore failed!')->send();
        }
    }

    public function render(): View
    {
        $rows = match ($this->recordState) {
            'withoutTrashed' => Role::withoutTrashed(),
            'trashed' => Role::onlyTrashed(),
            'withTrashed' => Role::withTrashed(),
            default => throw new \InvalidArgumentException("Unsupported role record state: {$this->recordState}"),
        };
        $rows = $rows->when($this->search, function ($query) {
            $query->where('name', 'like', (string) '%'.$this->search.'%');
            // $query->whereFullText("name", $this->search); // sql-lite not support
        })
            ->with(['users:id', 'permissions:id'])
            ->paginate($this->quantity)
            ->withQueryString();

        return view('livewire.roles.table', compact('rows'));
    }
}
