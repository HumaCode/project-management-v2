<?php

use App\Models\User;

test('profile page is displayed', function () {
    $user = User::factory()->create([
        'username' => 'testuser',
        'is_active' => '1',
    ]);

    $response = $this
        ->actingAs($user)
        ->get('/profil');

    $response->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create([
        'username' => 'testuser',
        'is_active' => '1',
    ]);

    $response = $this
        ->actingAs($user)
        ->put("/profil/{$user->id}", [
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertJson([
            'success' => true,
            'message' => 'Profil berhasil diperbarui!',
        ]);

    $user->refresh();

    $this->assertSame('Test User', $user->name);
    $this->assertSame('test@example.com', $user->email);
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create([
        'username' => 'testuser',
        'is_active' => '1',
    ]);

    $response = $this
        ->actingAs($user)
        ->put("/profil/{$user->id}", [
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertJson([
            'success' => true,
        ]);

    $this->assertNotNull($user->refresh()->email_verified_at);
});

test('user can delete their account', function () {
    $user = User::factory()->create([
        'username' => 'testuser',
        'is_active' => '1',
    ]);

    $response = $this
        ->actingAs($user)
        ->post('/profil/delete-account');

    $response
        ->assertSessionHasNoErrors()
        ->assertJson([
            'success' => true,
            'redirect' => route('register'),
        ]);

    $this->assertGuest();
    $this->assertNull($user->fresh());
});
