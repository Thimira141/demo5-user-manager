<?php

namespace App\Livewire\Accounts;

use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class Form extends Component
{
    use Interactions;

    // variables
    public ?string $name;

    public ?string $email;

    public ?string $password;

    public ?string $birthday;

    public ?string $notes;

    public ?int $userId = null;

    public ?int $update_password = 1;

    public ?User $user;

    /** @var array<array-key, mixed>|null */
    public ?array $roles;

    /** @var array<array-key, mixed>|null */
    public ?array $rolesData;

    /**
     * construct
     *
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function __construct()
    {
        // assign roles data
        $this->rolesData = Role::orderBy('name')->get(['id', 'name', 'description'])->toArray();
    }

    /**
     * mount data from model
     *
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function mount(?User $user = null): void
    {
        $this->user = $user;
        $this->userId = $user?->id;
        $this->name = $user?->name;
        $this->email = $user?->email;
        $this->birthday = $user?->birthday;
        $this->notes = $user?->notes;
        $this->roles = $user?->roles()->pluck('id')->all() ?? [];
    }

    /**
     * create/update user account
     *
     *
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function save(): void
    {
        // validate
        $validated = $this->validate($this->rules());

        // encrypt password if has
        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        if (! $this->userId) {
            // create
            $this->authorize('CREATE_USER');
            $this->create_user($validated);
        } else {
            // update
            $this->authorize('UPDATE_USER');
            if (! $this->user) {
                throw new \LogicException('The account being updated could not be loaded.');
            }

            $this->update_user($this->user, $validated);
        }
    }

    /**
     * create new user
     *
     *
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    /** @param array<string, mixed> $validated */
    protected function create_user(array $validated): void
    {
        $user = User::create(Arr::except($validated, ['roles']));
        $this->syncRoles($user, $this->validatedRoles($validated));
        $this->dialog()->success('Success!', 'User account created!')->send();
        $this->reset();
        $this->mount($user);
    }

    /**
     * update user
     *
     *
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    /** @param array<string, mixed> $validated */
    protected function update_user(User $user, array $validated): void
    {
        if ($user->update(Arr::except($validated, ['roles']))) {
            $this->dialog()->success('Success!', 'User account updated!')->send();
            // sync user roles
            $this->syncRoles($user, $this->validatedRoles($validated));
        } else {
            $this->dialog()->error('Failed!', 'User account update failed!')->send();
        }
    }

    /**
     * sync roles with user
     *
     *
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    /**
     * @param  array<array-key, mixed>  $roles
     */
    private function syncRoles(User $user, array $roles): void
    {
        $user->roles()->sync($roles);
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<array-key, mixed>
     */
    private function validatedRoles(array $validated): array
    {
        $roles = $validated['roles'] ?? null;
        if (! is_array($roles)) {
            throw new \LogicException('Validated account data did not include role IDs.');
        }

        return $roles;
    }

    /**
     * rules set
     *
     * @return array<string, array<int, mixed>>
     *
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    private function rules(): array
    {
        $password_rule = $this->update_password ? ['password' => ['required', 'string', 'max:50', 'min:8']] : [];

        return [
            'name' => [
                'required',
                'string',
                'max:200',
                Rule::unique('users', 'name')->ignore($this->user),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->user),
            ],
            'birthday' => [
                'required',
                Rule::date()->format('Y-m-d'),
            ],
            'notes' => [
                'nullable',
                'string',
                'max:200',
            ],
            'roles' => [
                'required',
                'array',
                Rule::exists('roles', 'id'),
                'min:1',
            ],
            ...$password_rule,
        ];
    }

    /**
     * render
     *
     * @return View
     *
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function render()
    {
        return view('livewire.accounts.form')
            // set layout title
            ->title($this->userId ? 'Update User [#'.Str::padLeft((string) $this->userId, 6).']' : 'New Account');
    }
}
