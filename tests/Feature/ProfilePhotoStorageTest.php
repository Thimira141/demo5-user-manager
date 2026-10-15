<?php

namespace Tests\Feature;

use App\Livewire\Auth\My\EditForm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProfilePhotoStorageTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function uploaded_profile_photo_is_stored_in_the_public_disk_and_served_by_url(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'birthday' => '1990-01-01',
            'notes' => 'hello',
        ]);

        $this->actingAs($user);

        Livewire::test(EditForm::class, ['user' => $user])
            ->set('name', 'Jane Doe Updated')
            ->set('email', 'jane.updated@example.com')
            ->set('birthday', '1990-01-01')
            ->set('notes', 'Updated note')
            ->set('photo', UploadedFile::fake()->image('avatar.jpg'))
            ->call('save', $user);

        $this->assertTrue(Storage::disk('public')->exists('photos/' . $user->id . '.jpg'));
        $this->assertStringContainsString('/storage/photos/' . $user->id . '.jpg', $user->fresh()->getAvatar());
    }
}
