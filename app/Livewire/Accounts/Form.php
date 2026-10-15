<?php

namespace App\Livewire\Accounts;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class Form extends Component
{
    use Interactions;

    // variables
    public ?string $name, $email, $password, $birthday, $notes;
    public ?int $userId = null, $update_password = 1;
    public ?User $user;
    public ?array $roles, $rolesData;

    /**
     * construct
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function __construct()
    {
        // assign roles data
        $this->rolesData = Role::orderBy('name')->get(['id', 'name', 'description'])->toArray();
    }

    /**
     * mount data from model
     * @param mixed $user
     * @return void
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function mount(?User $user)
    {
        // if (!$user) {
        // todo: handle user not found,
        //     $this->dialog()->error("User not found!")->send();
        // }

        $this->user = $user;
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->birthday = $user->birthday;
        $this->notes = $user->notes;
        $this->roles = $user->roles()->pluck('id')->toArray();
    }

    /**
     * create/update user account
     * @return void
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function save()
    {
        // validate
        $validated = $this->validate($this->rules());

        // encrypt password if has
        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        if (!$this->userId) {
            // create
            $this->authorize('CREATE_USER');
            $this->create_user($validated);
        } else {
            // update
            $this->authorize('UPDATE_USER');
            $this->update_user($validated);
        }
    }

    /**
     * create new user
     * @param array $validated
     * @return void
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    protected function create_user(array $validated)
    {
        if ($user = User::create(collect($validated)->except('roles')->toArray())) {
            $this->dialog()->success("Success!", "User account created!")->send();
            // sync user roles
            $this->syncRoles($user, $validated['roles']);
            $this->reset();
            $this->mount($user);
        } else {
            $this->dialog()->error("Failed!", "User account create failed!")->send();
        }
    }

    /**
     * update user
     * @param array $validated
     * @return void
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    protected function update_user(array $validated)
    {
        if ($this->user->update(collect($validated)->except('roles')->toArray())) {
            $this->dialog()->success("Success!", "User account updated!")->send();
            // sync user roles
            $this->syncRoles($this->user, $validated['roles']);
        } else {
            $this->dialog()->error("Failed!", "User account update failed!")->send();
        }
    }

    /**
     * sync roles with user
     * @param User $user
     * @param array $roles
     * @return void
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    private function syncRoles(User $user, array $roles)
    {
        if (!$user->roles()->sync($roles)) {
            $this->dialog()->error('Sync Failed!', 'User roles sync failed!')->send();
        }
    }

    /**
     * rules set
     * @return array{birthday: string[], email: array<string|\Illuminate\Validation\Rules\Unique>, name: array<string|\Illuminate\Validation\Rules\Unique>, notes: string[], password: string[]}
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    private function rules()
    {
        $password_rule = $this->update_password ? ["password" => ["required", "string", "max:50", "min:8"]] : [];
        return [
            "name" => [
                "required",
                "string",
                "max:200",
                Rule::unique("users", "name")->ignore($this->user),
            ],
            "email" => [
                "required",
                "email",
                Rule::unique("users", "email")->ignore($this->user),
            ],
            "birthday" => [
                "required",
                Rule::date()->format("Y-m-d")
            ],
            "notes" => [
                "nullable",
                "string",
                "max:200",
            ],
            'roles' => [
                "required",
                "array",
                Rule::exists('roles', 'id'),
                "min:1"
            ],
            ...$password_rule
        ];
    }

    /**
     * render
     * @return \Illuminate\Contracts\View\View
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function render()
    {
        // padding needs be under 6 digits more than 6, let the original number be present
        return view('livewire.accounts.form')
            // set layout title
            ->title($this->userId ? "Update User [#" . Str::padLeft($this->userId, 6) . "]" : "New Account");
    }
}
