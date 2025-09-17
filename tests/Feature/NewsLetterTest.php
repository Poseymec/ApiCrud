<?php

namespace Tests\Feature;

use App\Models\NewsLetters;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsLetterTest extends TestCase
{
    use RefreshDatabase; // Rafraîchit la base de données après chaque test

    /** @test */
    public function it_can_list_newsletters()
    {
        // Créer quelques newsletters pour le test
        NewsLetters::factory()->create(['email' => 'test1@example.com', 'phone' => '1234567890']);
        NewsLetters::factory()->create(['email' => 'test2@example.com', 'phone' => '0987654321']);

        // Faire une requête GET à l'endpoint index
        $response = $this->getJson('/api/newsletters');

        // Vérifier que la réponse est réussie et contient les données
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => [
                        'id',
                        'email',
                        'phone',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_can_create_a_newsletter()
    {
        // Données pour créer une newsletter
        $data = [
            'email' => 'test@example.com',
            'phone' => '1234567890',
        ];

        // Faire une requête POST à l'endpoint store
        $response = $this->postJson('/api/newsletters', $data);

        // Vérifier que la réponse est réussie et contient les bonnes données
        $response->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('message', 'Newsletter créée avec succès')
            ->assertJsonPath('data.email', 'test@example.com')
            ->assertJsonPath('data.phone', '1234567890');

        // Vérifier que la newsletter a bien été enregistrée en base de données
        $this->assertDatabaseHas('news_letters', [
            'email' => 'test@example.com',
            'phone' => '1234567890',
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_a_newsletter()
    {
        // Faire une requête POST sans les champs requis
        $response = $this->postJson('/api/newsletters', []);

        // Vérifier que la réponse contient une erreur de validation
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_can_show_a_newsletter()
    {
        // Créer une newsletter pour le test
        $newsletter = NewsLetters::factory()->create(['email' => 'test@example.com']);

        // Faire une requête GET à l'endpoint show
        $response = $this->getJson("/api/newsletters/{$newsletter->id}");

        // Vérifier que la réponse est réussie et contient les données de la newsletter
        $response->assertStatus(200)
            ->assertJson([
                'id' => $newsletter->id,
                'email' => 'test@example.com',
                'phone' => $newsletter->phone,
            ]);
    }

    /** @test */
    public function it_can_delete_a_newsletter()
    {
        // Créer une newsletter pour le test
        $newsletter = NewsLetters::factory()->create(['email' => 'test@example.com']);

        // Faire une requête DELETE à l'endpoint destroy
        $response = $this->deleteJson("/api/newsletters/{$newsletter->id}");

        // Vérifier que la réponse est réussie
        $response->assertStatus(200)
            ->assertJson(['message' => 'Information supprimée avec succès']);

        // Vérifier que la newsletter a bien été supprimée de la base de données
        $this->assertDatabaseMissing('news_letters', [
            'id' => $newsletter->id,
        ]);
    }
}
