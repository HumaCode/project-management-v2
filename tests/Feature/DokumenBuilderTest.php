<?php

use App\Models\User;
use App\Models\Project;
use App\Models\Dokumen;
use App\Models\DokumenItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

test('unauthorized users cannot upload builder images', function () {
    $user = User::factory()->create(['is_active' => '1']);
    $project = Project::create([
        'name' => 'Test Project',
        'description' => 'Test Description',
        'start_date' => now(),
        'app_type' => 'website',
        'created_by' => $user->id,
    ]);

    $dokumen = Dokumen::create([
        'project_id' => $project->id,
        'nama' => 'Test Doc',
        'kategori' => 's',
        'versi' => '1.0',
        'tanggal_upload' => now(),
        'type' => 'article',
        'user_id' => $user->id,
    ]);

    $file = UploadedFile::fake()->image('test.jpg');

    $response = $this->postJson(route('dokumen.builder.upload', $dokumen->id), [
        'image' => $file
    ]);

    $response->assertStatus(401); // Redirects or unauthorized
});

test('authorized users can upload builder images successfully', function () {
    $user = User::factory()->create(['is_active' => '1']);
    // Assign role/permissions if necessary. Gate allows update to creator/authorized users.
    // By default, the policy might allow if uploaded_by is user. Let's see.
    $project = Project::create([
        'name' => 'Test Project',
        'description' => 'Test Description',
        'start_date' => now(),
        'app_type' => 'website',
        'created_by' => $user->id,
    ]);

    $dokumen = Dokumen::create([
        'project_id' => $project->id,
        'nama' => 'Test Doc',
        'kategori' => 's',
        'versi' => '1.0',
        'tanggal_upload' => now(),
        'type' => 'article',
        'user_id' => $user->id,
    ]);

    $file = UploadedFile::fake()->image('test.jpg');

    $response = $this->actingAs($user)->postJson(route('dokumen.builder.upload', $dokumen->id), [
        'image' => $file
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'url',
                'media_id',
                'name'
            ]
        ]);

    $mediaId = $response->json('data.media_id');
    $this->assertDatabaseHas('media', [
        'id' => $mediaId,
        'model_type' => Dokumen::class,
        'model_id' => $dokumen->id,
        'collection_name' => 'builder_temp_images',
    ]);
});

test('saving builder items links temporary images to new items and cleans up old items/media', function () {
    $user = User::factory()->create(['is_active' => '1']);
    $project = Project::create([
        'name' => 'Test Project',
        'description' => 'Test Description',
        'start_date' => now(),
        'app_type' => 'website',
        'created_by' => $user->id,
    ]);

    $dokumen = Dokumen::create([
        'project_id' => $project->id,
        'nama' => 'Test Doc',
        'kategori' => 's',
        'versi' => '1.0',
        'tanggal_upload' => now(),
        'type' => 'article',
        'user_id' => $user->id,
    ]);

    // 1. Upload an image
    $file = UploadedFile::fake()->image('photo.png');
    $uploadResponse = $this->actingAs($user)->postJson(route('dokumen.builder.upload', $dokumen->id), [
        'image' => $file
    ]);
    $mediaId = $uploadResponse->json('data.media_id');
    $url = $uploadResponse->json('data.url');

    // 2. Save items containing text, code, and image block
    $saveResponse = $this->actingAs($user)->postJson(route('dokumen.builder.save', $dokumen->id), [
        'items' => [
            [
                'type' => 'text',
                'content' => 'Paragraf Penjelasan',
                'metadata' => []
            ],
            [
                'type' => 'image',
                'content' => $url,
                'metadata' => [
                    'media_id' => $mediaId
                ]
            ],
            [
                'type' => 'code',
                'content' => 'console.log("hello");',
                'metadata' => [
                    'language' => 'javascript'
                ]
            ]
        ]
    ]);

    $saveResponse->assertStatus(200)
        ->assertJsonPath('success', true);

    // Verify items are saved
    $this->assertDatabaseHas('dokumen_items', [
        'dokumen_id' => $dokumen->id,
        'type' => 'text',
        'content' => 'Paragraf Penjelasan',
        'order' => 0
    ]);

    $this->assertDatabaseHas('dokumen_items', [
        'dokumen_id' => $dokumen->id,
        'type' => 'image',
        'order' => 1
    ]);

    $this->assertDatabaseHas('dokumen_items', [
        'dokumen_id' => $dokumen->id,
        'type' => 'code',
        'content' => 'console.log("hello");',
        'order' => 2
    ]);

    // Verify media was kept on Dokumen under builder_images
    $this->assertDatabaseHas('media', [
        'id' => $mediaId,
        'model_type' => Dokumen::class,
        'model_id' => $dokumen->id,
        'collection_name' => 'builder_images',
    ]);

    // 3. Save again but remove the image block, verify it cleans up the media from database/disk
    $this->actingAs($user)->postJson(route('dokumen.builder.save', $dokumen->id), [
        'items' => [
            [
                'type' => 'text',
                'content' => 'Hanya ada teks sekarang',
                'metadata' => []
            ]
        ]
    ])->assertStatus(200);

    // Verify media is deleted
    $this->assertDatabaseMissing('media', [
        'id' => $mediaId
    ]);
});
