<?php

namespace App\Livewire\Auth\My;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;

#[Title('Edit My Profile')]
class EditForm extends Component
{
    use Interactions, WithFileUploads;

    public User $user;

    public ?string $name;

    public ?string $email;

    public ?string $password;

    public ?string $birthday;

    public ?string $notes;

    public ?int $update_password = null;

    public ?TemporaryUploadedFile $photo = null;

    public function mount(): void
    {
        $user = Auth::user();
        if (! $user instanceof User) {
            throw new \LogicException('An authenticated user is required to edit the profile.');
        }

        $this->user = $user;
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->birthday = $this->user->birthday;
        $this->notes = $this->user->notes;
    }

    public function save(): void
    {
        // validate
        $validated = $this->validate($this->rules());

        // encrypt password if has
        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }
        if ($this->user->update(Arr::except($validated, ['roles', 'photo']))) {
            // update photo if have
            if (! empty($validated['photo'])) {
                if (! $this->storeAvatar($validated['photo'])) {
                    $this->dialog()->error('Failed!', 'User photo upload failed!')->send();
                }
            }
            $this->dialog()->success('Success!', 'User account updated!')->send();
        } else {
            $this->dialog()->error('Failed!', 'User account update failed!')->send();
        }
    }

    protected function storeAvatar(UploadedFile $photo): bool
    {
        return Storage::disk('public')->putFileAs(
            'photos',
            $photo,
            $this->user->id.'.jpg',
        ) !== false;
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
            'photo' => [
                'nullable',
                'image',
                'max:2048',
            ],
            ...$password_rule,
        ];
    }

    public function render(): View
    {
        return view('livewire.auth.my.edit-form');
    }
}
