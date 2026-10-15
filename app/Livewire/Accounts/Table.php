<?php

namespace App\Livewire\Accounts;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use TallStackUi\Traits\Interactions;

#[Title("Users Accounts")]
class Table extends Component
{
    use WithPagination, Interactions;

    /**
     * rows per page in table pagination
     * @var int
     */
    public int $quantity = 10;
    /**
     * search text
     * @var string|null
     */
    public string|null $search = null;
    /**
     * selected roles for roles filter
     * @var null|array
     */
    public null|array $roles = [];
    /**
     * define softDeleted state
     * @var string
     */
    public string $recordState = "withoutTrashed";
    public array $recordStates = [
        ['value' => 'withoutTrashed', 'label' => "Except Deleted"],
        ['value' => 'trashed', 'label' => "Only Deleted"],
        ['value' => 'withTrashed', 'label' => "All (with deleted)"],
    ];

    /**
     * users list table headers define
     * @var array
     */
    public array $headers = [
        ["index" => "name", "label" => "Name"],
        ["index" => "email", "label" => "Email"],
        ["index" => "roles", "label" => "Roles"],
        ["index" => "action"],
    ];

    /**
     * roles from db
     * @var array
     */
    public array $rolesData;

    /**
     * construct
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function __construct()
    {
        // assign roles
        $this->rolesData = Role::orderBy('name')->get(['id', 'name'])->toArray();
    }

    /**
     * delete user, confirm dialog
     * @param int $id
     * @return void
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function delete(int $id)
    {
        $this->authorize('DELETE_USER');

        $user = User::find($id, ['id', 'name']);
        if (!$user) {
            $this->dialog()->error("User Not Found!")->send();
        }
        $this->dialog()
            ->question("Delete", "Confirm delete user:\n {$user->name}")
            ->confirm("Delete", "confirmedDelete", $user->id)
            ->cancel("Cancel")
            ->send();
    }

    /**
     * confirmed delete, init from delete -> confirm
     * @param int $id User->id
     * @return void
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function confirmedDelete(int $id)
    {
        $this->authorize('DELETE_USER');

        $user = User::find($id, ['id', 'name']);
        if (!$user) {
            $this->dialog()->error("User Not Found!")->send();
        }
        if ($user->delete()) {
            $this->dialog()->success("Deleted!", "User deleted!")->send();
        } else {
            $this->dialog()->error("Failed!", "User delete failed!")->send();
        }
    }

    /**
     * restore user, confirm dialog
     * @param int $id
     * @return void
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function restore(int $id)
    {
        $this->authorize('DELETE_USER');

        $user = User::withTrashed()->find($id, ['id', 'name']);
        if (!$user) {
            $this->dialog()->error("User Not Found!")->send();
        }
        $this->dialog()
            ->question("Restore", "Confirm restore user:\n {$user->name}")
            ->confirm("Restore", "confirmedRestore", $user->id)
            ->cancel("Cancel")
            ->send();
    }

    /**
     * confirmed restore, init from restore -> confirm
     * @param int $id User->id
     * @return void
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function confirmedRestore(int $id)
    {
        $this->authorize('DELETE_USER');
        
        $user = User::withTrashed()->find($id, ['id', 'name']);
        if (!$user) {
            $this->dialog()->error("User Not Found!")->send();
        }
        if ($user->restore()) {
            $this->dialog()->success("Restored!", "User restored!")->send();
        } else {
            $this->dialog()->error("Failed!", "User restore failed!")->send();
        }
    }

    /**
     * render
     * @return \Illuminate\Contracts\View\View
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function render()
    {
        $rows = match ($this->recordState) {
            "withoutTrashed" => User::withoutTrashed(),
            "trashed" => User::onlyTrashed(),
            "withTrashed" => User::withTrashed(),
        };
        $rows = $rows->when($this->search, function ($query) {
            $query->where("name", "like", (string) "%" . $this->search . "%");
            $query->orWhere("email", "like", (string) "%" . $this->search . "%");
            // $query->whereFullText(['name', 'email'], $this->search); // sql-lite not support
        })
            ->with('roles:id,name')
            ->when($this->roles, function ($query) {
                $query->whereHas('roles', fn($q) => $q->whereIn('id', $this->roles));
            })
            ->except(Auth::user())
            ->paginate($this->quantity)
            ->withQueryString();
        return view('livewire.accounts.table', compact('rows'));
    }

}
