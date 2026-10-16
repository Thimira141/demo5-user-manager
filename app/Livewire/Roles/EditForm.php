<?php

namespace App\Livewire\Roles;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class EditForm extends Component
{
    use Interactions;

    public ?Role $role = null;

    public ?string $name;

    public ?string $description;

    /** @var array<array-key, mixed> */
    public array $permissions;

    /** @var array<array-key, mixed> */
    public array $permissionOptions;

    /**
     * __construct
     *
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function __construct()
    {
        // load permissions to select var
        $this->permissionOptions = Permission::orderBy('name')->get(['id', 'name', 'description'])->toArray();
    }

    /**
     * mount data
     *
     *
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function mount(Role $role): void
    {
        $this->role = $role;
        $this->name = $role->name;
        $this->description = $role->description;
        $this->permissions = $role->permissions()->pluck('id')->toArray();
    }

    /**
     * create/update role with sync permissions
     *
     *
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function save(): void
    {
        $validated = $this->validate($this->rules());

        if (! $this->role?->id) {
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
     *
     *
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    /** @param array<string, mixed> $validated */
    protected function create_role(array $validated): void
    {
        $role = Role::create(collect($validated)->except('permissions')->toArray());
        $this->syncPermissions($role, $this->validatedPermissions($validated));
        $this->dialog()->success('Success!', 'Role created!')->send();
        $this->reset();
        $this->mount($role);
    }

    /**
     * update role
     *
     *
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    /** @param array<string, mixed> $validated */
    protected function update_role(array $validated): void
    {
        if ($this->role->update(collect($validated)->except('permissions')->toArray())) {
            $this->dialog()->success('Success!', 'Role updated!')->send();
            // sync role permissions
            $this->syncPermissions($this->role, $this->validatedPermissions($validated));
        } else {
            $this->dialog()->error('Failed!', 'Role update failed!')->send();
        }
    }

    /**
     * sync role permissions
     *
     *
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    /**
     * @param  array<array-key, mixed>  $permissions
     */
    private function syncPermissions(Role $role, array $permissions): void
    {
        $role->permissions()->sync($permissions);
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<array-key, mixed>
     */
    private function validatedPermissions(array $validated): array
    {
        $permissions = $validated['permissions'] ?? null;
        if (! is_array($permissions)) {
            throw new \LogicException('Validated role data did not include permission IDs.');
        }

        return $permissions;
    }

    /**
     * validate rules
     *
     * @return array<string, array<int, mixed>>
     *
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    private function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:200',
                Rule::unique('roles', 'name')->ignore($this->role),
            ],
            'description' => [
                'nullable',
                'string',
                'max:200',
            ],
            'permissions' => [
                'required',
                'array',
                Rule::exists('permissions', 'id'),
                'min:1',
            ],
        ];
    }

    /**
     * render with custom title
     *
     * @return View
     *
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function render()
    {
        return view('livewire.roles.edit-form')
            ->title($this->role?->id ? 'Update Role [#'.Str::padLeft((string) $this->role->id, 6, '0').']' : 'New Role');
    }
}
