<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MediaApiTest extends TestCase
{
    use RefreshDatabase;

    private function makeMedia(array $overrides = []): Media
    {
        return Media::create(array_merge([
            'title' => 'Naruto',
            'type' => 'Anime',
            'genres' => ['Action', 'Adventure'],
            'rating' => 8.5,
            'year' => 2002,
            'synopsis' => 'Kisah ninja muda.',
        ], $overrides));
    }

    private function mediaPayload(string $title = 'Frieren'): array
    {
        return [
            'title' => $title,
            'type' => 'Anime',
            'genres' => ['Fantasy', 'Adventure'],
            'rating' => 9.2,
            'year' => 2023,
            'synopsis' => 'Petualangan fantasi.',
        ];
    }

    public function test_crud_media_memakai_status_code_yang_tepat(): void
    {
        $created = $this->postJson('/api/media', $this->mediaPayload());
        $created->assertCreated()->assertJsonPath('data.title', 'Frieren');
        $id = $created->json('data.id');
        $this->assertDatabaseHas('media', ['id' => $id, 'type' => 'Anime']);

        $this->getJson('/api/media')->assertOk()
            ->assertJsonPath('data.0.title', 'Frieren');
        $this->getJson('/api/media/' . $id)->assertOk()
            ->assertJsonPath('data.id', $id);

        $this->patchJson('/api/media/' . $id, ['rating' => 9.5])
            ->assertOk()->assertJsonPath('data.rating', 9.5);
        $this->assertDatabaseHas('media', ['id' => $id, 'rating' => 9.5]);

        $this->deleteJson('/api/media/' . $id)->assertNoContent();
        $this->getJson('/api/media/' . $id)->assertNotFound();
    }

    public function test_form_request_menolak_judul_kosong_atau_duplikat(): void
    {
        $this->makeMedia();

        $this->postJson('/api/media', $this->mediaPayload('  '))
            ->assertUnprocessable()->assertJsonValidationErrors('title');
        $this->postJson('/api/media', $this->mediaPayload('Naruto'))
            ->assertUnprocessable()->assertJsonValidationErrors('title');
        $this->patchJson('/api/media/1', ['title' => ''])
            ->assertUnprocessable()->assertJsonValidationErrors('title');
    }

    public function test_patch_sebagian_boleh_tanpa_mengirim_semua_field(): void
    {
        $media = $this->makeMedia();
        $this->patchJson('/api/media/' . $media->id, ['title' => 'Naruto Shippuden'])
            ->assertOk()->assertJsonPath('data.title', 'Naruto Shippuden')
            ->assertJsonPath('data.type', 'Anime');
    }

    public function test_tipe_dan_rating_tidak_valid_ditolak(): void
    {
        $this->postJson('/api/media', array_merge($this->mediaPayload(), [
            'type' => 'Buku', 'rating' => 12,
        ]))->assertUnprocessable()->assertJsonValidationErrors(['type', 'rating']);
    }

    public function test_index_mendukung_search_type_dan_aura_dari_modul_3(): void
    {
        $this->makeMedia();
        $this->makeMedia([
            'title' => 'Death Note', 'type' => 'Manga',
            'genres' => ['Mystery', 'Psychological', 'Thriller'],
        ]);

        $this->getJson('/api/media?search=naruto')
            ->assertOk()->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Naruto');
        $this->getJson('/api/media?type=Anime&aura=High%20Energy')
            ->assertOk()->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Naruto');
        $this->getJson('/api/media?aura=Mind%20Bender')
            ->assertOk()->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Death Note');
        $this->getJson('/api/media?type=Game')
            ->assertUnprocessable()->assertJsonValidationErrors('type');
    }

    public function test_media_resource_menampilkan_pengguna_yang_mengoleksi(): void
    {
        $media = $this->makeMedia();
        $user = User::factory()->create();
        $user->media()->attach($media->id);

        $this->getJson('/api/media/' . $media->id)->assertOk()
            ->assertJsonPath('data.users.0.id', $user->id)
            ->assertJsonPath('data.users.0.name', $user->name);
    }

    public function test_custom_action_attach_detach_dan_koleksi_resource(): void
    {
        $media = $this->makeMedia();
        $user = User::factory()->create();

        $this->postJson('/api/users/' . $user->id . '/collection', [
            'media_id' => $media->id,
        ])->assertCreated()->assertJsonPath('data.collection.0.id', $media->id);
        $this->assertDatabaseHas('media_user', ['user_id' => $user->id, 'media_id' => $media->id]);

        $this->getJson('/api/users/' . $user->id . '/collection')
            ->assertOk()->assertJsonPath('data.collection.0.title', 'Naruto');
        $this->postJson('/api/users/' . $user->id . '/collection', [
            'media_id' => $media->id,
        ])->assertStatus(409);
        $this->assertDatabaseCount('media_user', 1);

        $this->deleteJson('/api/users/' . $user->id . '/collection/' . $media->id)
            ->assertNoContent();
        $this->getJson('/api/users/' . $user->id . '/collection')
            ->assertOk()->assertJsonPath('data.collection', []);
        $this->assertDatabaseMissing('media_user', ['user_id' => $user->id, 'media_id' => $media->id]);

        // Menghapus hubungan yang sudah tidak ada harus ditolak.
        $this->deleteJson('/api/users/' . $user->id . '/collection/' . $media->id)
            ->assertStatus(409);
    }

    public function test_custom_action_validasi_input_dan_model_binding(): void
    {
        $user = User::factory()->create();
        $this->postJson('/api/users/' . $user->id . '/collection', [])
            ->assertUnprocessable()->assertJsonValidationErrors('media_id');
        $this->postJson('/api/users/' . $user->id . '/collection', ['media_id' => 999999])
            ->assertUnprocessable()->assertJsonValidationErrors('media_id');
        $this->getJson('/api/users/999999/collection')->assertNotFound();
    }

    public function test_pivot_terhapus_otomatis_setelah_media_dihapus(): void
    {
        $media = $this->makeMedia();
        $user = User::factory()->create();
        $user->media()->attach($media->id);
        $this->deleteJson('/api/media/' . $media->id)->assertNoContent();
        $this->assertDatabaseMissing('media_user', ['media_id' => $media->id]);
    }

    public function test_seeder_bisa_dijalankan_ulang_tanpa_data_duplikat(): void
    {
        $this->artisan('db:seed')->assertSuccessful();
        $this->artisan('db:seed')->assertSuccessful();
        $this->assertDatabaseCount('media', 6);
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('media_user', 2);
    }
}
