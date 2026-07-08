<?php

use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests are redirected to login when accessing permohonan-aplikasi', function () {
    $response = $this->get(route('project-request.create'));

    $response->assertRedirect(route('login'));
});

test('logged in users can render the permohonan-aplikasi form', function () {
    $user = User::factory()->create([
        'is_active' => '1',
    ]);

    $response = $this->actingAs($user)->get(route('project-request.create'));

    $response->assertStatus(200)
        ->assertSee('Permohonan Aplikasi Baru')
        ->assertSee('Form Pengajuan Permohonan Aplikasi');
});

test('users can submit project request successfully', function () {
    $user = User::factory()->create([
        'is_active' => '1',
    ]);

    $response = $this->actingAs($user)->postJson(route('project-request.store'), [
        'name' => 'Aplikasi Request Baru',
        'description' => 'Ini deskripsi project request',
        'priority' => 'high',
        'start_date' => '08-07-2026',
        'deadline' => '15-07-2026',
        'color' => '#ff0000',
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('success', true);

    $this->assertDatabaseHas('projects', [
        'name' => 'Aplikasi Request Baru',
        'priority' => 'high',
        'status' => 'to_do',
        'progress' => 0,
        'team_id' => null,
        'source' => 'request',
        'created_by' => $user->id,
    ]);
});
