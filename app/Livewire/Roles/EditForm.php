<?php

namespace App\Livewire\Roles;

use App\Models\Permission;
use App\Models\Role;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use TallStackUi\Traits\Interactions;

class EditForm extends Component
{
    use Interactions;
    public ?Role $role = null;
    public ?string $name, $description;
    public array $permissions, $permissionOptions;

    /**
     * __construct
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function __construct()
    {
        // load permissions to select var
        $this->permissionOptions = Permission::orderBy('name')->get(['id', 'name', 'description'])->toArray();
    }

    /**
     * mount data
     * @param Role $role
     * @return void
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function mount(Role $role)
    {
        $this->role = $role;
        $this->name = $role->name;
        $this->description = $role->description;
        $this->permissions = $role->permissions()->pluck('id')->toArray();
    }

    /**
     * create/update role with sync permissions
     * @return void
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function save()
    {
        $validated = $this->validate($this->rules());

        if (!$this->role?->id) {
            // create role
            $this->authorize('CREATE_ROLE');
            $this->create_role($validated);

        } else {
            // update
            $this->authorize('UPDATE_ROLE');
            $this->update_role($validated);

        }
    }

    /**
     * create role
     * @param array $validated
     * @return void
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    protected function create_role(array $validated)
    {
        if ($role = Role::create(collect($validated)->except('permissions')->toArray())) {
            $this->dialog()->success("Success!", "Role created!")->send();
            // sync Role roles
            $this->syncPermissions($role, $validated['permissions']);
            $this->reset();
            $this->mount($role);
        } else {
            $this->dialog()->error("Failed!", "Role create failed!")->send();
        }
    }

    /**
     * update role
     * @param array $validated
     * @return void
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    protected function update_role(array $validated)
    {
        if ($this->role->update(collect($validated)->except('permissions')->toArray())) {
            $this->dialog()->success("Success!", "Role updated!")->send();
            // sync role permissions
            $this->syncPermissions($this->role, $validated['permissions']);
        } else {
            $this->dialog()->error("Failed!", "Role update failed!")->send();
        }
    }

    /**
     * sync role permissions
     * @param Role $role
     * @param array $permissions
     * @return void
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    private function syncPermissions(Role $role, array $permissions)
    {
        if (!$role->permissions()->sync($permissions)) {
            $this->dialog()->error('Sync Failed!', 'Role permissions sync failed!')->send();
        }
    }

    /**
     * validate rules
     * @return array{description: string[], name: array<string|\Illuminate\Validation\Rules\Unique>, permissions: array<string|\Illuminate\Validation\Rules\Exists>}
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    private function rules()
    {
        return [
            "name" => [
                "required",
                "string",
                "max:200",
                Rule::unique("roles", "name")->ignore($this->role),
            ],
            "description" => [
                "nullable",
                "string",
                "max:200",
            ],
            'permissions' => [
                "required",
                "array",
                Rule::exists('permissions', 'id'),
                "min:1"
            ],
        ];
    }

    /**
     * render with custom title
     * @return \Illuminate\Contracts\View\View
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function render()
    {
        return view('livewire.roles.edit-form')
            ->title($this->role?->id ? "Update Role [#" . Str::padLeft($this->role?->id, 6, '0') . "]" : "New Role");
    }
}
