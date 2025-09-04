<?php

namespace Tests\Feature;

use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProduitApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $categorie;

    protected function setUp(): void
    {
        parent::setUp();

        // Configuration du stockage pour les tests
        Storage::fake('public');

        // Créer une catégorie de test
        $this->categorie = Categorie::factory()->create([
            'name' => 'Catégorie Test'
        ]);
    }

    #[Test]
    public function it_can_create_a_produit_with_images()
    {
        // Créer des fichiers d'image factices
        $image1 = UploadedFile::fake()->image('photo1.jpg', 800, 600)->size(1000);
        $image2 = UploadedFile::fake()->image('photo2.jpg', 800, 600)->size(1200);

        $data = [
            'name' => 'Nouveau Produit',
            'categorie_id' => $this->categorie->id,
            'description1' => 'Description courte',
            'description2' => 'Description détaillée du produit',
            'prix' => 199.99,
            'status' => 'active',
            'images' => [$image1, $image2]
        ];

        $response = $this->postJson('/api/produits', $data);

        // Vérifier d'abord le statut et la structure générale
        $response->assertStatus(201);

        // Vérifier la structure selon ce que retourne vraiment votre contrôleur
        $responseData = $response->json();

        // Si votre contrôleur retourne directement une ProduitResource
        if (isset($responseData['id'])) {
            $response->assertJsonStructure([
                'id',
                'name',
                'categorie_id',
                'description1',
                'description2',
                'prix',
                'status',
                'images',
                'created_at',
                'updated_at'
            ]);
        } else {
            // Si votre contrôleur retourne avec 'message' et 'data'
            $response->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'name',
                    'categorie_id',
                    'description1',
                    'description2',
                    'prix',
                    'status',
                    'images',
                    'created_at',
                    'updated_at'
                ]
            ]);
        }

        // Vérifier les données
        $response->assertJsonFragment([
            'name' => 'Nouveau Produit',
            'prix' => 199.99,
            'status' => 'active'
        ]);

        // Vérifier que le produit a été créé en base
        $this->assertDatabaseHas('produits', [
            'name' => 'Nouveau Produit',
            'categorie_id' => $this->categorie->id,
            'prix' => 199.99
        ]);

        // Vérifier que les images ont été créées
        $produit = Produit::where('name', 'Nouveau Produit')->first();
        $this->assertCount(2, $produit->images);

        // Vérifier qu'au moins une image est marquée comme couverture
        $this->assertTrue($produit->images->where('is_cover', true)->count() >= 1);
    }

    #[Test]
    public function it_can_show_a_produit()
    {
        $produit = Produit::factory()->create([
            'name' => 'Produit Test',
            'categorie_id' => $this->categorie->id,
            'prix' => 150.00
        ]);

        $response = $this->getJson("/api/produits/{$produit->id}");

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'categorie_id',
                    'description1',
                    'description2',
                    'prix',
                    'status',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJsonFragment([
                'name' => 'Produit Test',
                'prix' => '150.00' // Le prix est retourné comme string dans votre Resource
            ]);
    }

    #[Test]
    public function it_can_update_a_produit()
    {
        $produit = Produit::factory()->create([
            'name' => 'Produit Original',
            'categorie_id' => $this->categorie->id,
            'prix' => 200.00,
            'status' => 'active'
        ]);

        $updateData = [
            'name' => 'Produit modifié',
            'categorie_id' => $this->categorie->id,
            'description1' => 'Description modifiée',
            'description2' => 'Description détaillée modifiée',
            'prix' => 250.00,
            'status' => 'inactive',
        ];

        $response = $this->putJson("/api/produits/{$produit->id}", $updateData);

        // Debug en cas d'erreur 500
        if ($response->status() === 500) {
            dd($response->json());
        }

        $response->assertOk();

        // Vérifier la structure selon ce que retourne vraiment votre contrôleur
        $responseData = $response->json();

        if (isset($responseData['message'])) {
            $response->assertJsonFragment([
                'message' => 'Produit mis à jour avec succès'
            ]);

            if (isset($responseData['data'])) {
                $response->assertJsonPath('data.name', 'Produit modifié');
                $response->assertJsonPath('data.status', 'inactive');
            }
        } else {
            $response->assertJsonFragment([
                'name' => 'Produit modifié',
                'status' => 'inactive'
            ]);
        }

        // Vérifier en base de données
        $this->assertDatabaseHas('produits', [
            'id' => $produit->id,
            'name' => 'Produit modifié',
            'prix' => 250.00,
            'status' => 'inactive'
        ]);
    }

    #[Test]
    public function it_can_delete_a_produit()
    {
        $produit = Produit::factory()->create([
            'name' => 'Produit à supprimer',
            'categorie_id' => $this->categorie->id
        ]);

        $response = $this->deleteJson("/api/produits/{$produit->id}");

        $response->assertOk()
            ->assertJsonFragment([
                'message' => 'Produit supprimé avec succès'
            ]);

        // Vérifier que le produit n'existe plus en base
        $this->assertDatabaseMissing('produits', [
            'id' => $produit->id
        ]);
    }

    #[Test]
    public function it_can_toggle_produit_status()
    {
        $produit = Produit::factory()->create([
            'name' => 'Produit Test Status',
            'categorie_id' => $this->categorie->id,
            'status' => 'active'
        ]);

        $response = $this->patchJson("/api/produits/{$produit->id}/toggle-status");

        $response->assertOk()
            ->assertJsonFragment([
                'message' => 'Statut du produit mis à jour avec succès'
            ]);

        // Vérifier que le statut a changé
        $produit->refresh();
        $this->assertEquals('inactive', $produit->status);
    }

    #[Test]
    public function it_validates_required_fields_when_creating_produit()
    {
        $response = $this->postJson('/api/produits', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'name',
                'categorie_id',
                'description2',
                'prix'
            ]);
    }

    #[Test]
    public function it_validates_unique_name_when_creating_produit()
    {
        $existingProduit = Produit::factory()->create([
            'name' => 'Produit Unique',
            'categorie_id' => $this->categorie->id
        ]);

        $response = $this->postJson('/api/produits', [
            'name' => 'Produit Unique',
            'categorie_id' => $this->categorie->id,
            'description2' => 'Description',
            'prix' => 100
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    #[Test]
    public function it_validates_category_exists_when_creating_produit()
    {
        $response = $this->postJson('/api/produits', [
            'name' => 'Produit Test',
            'categorie_id' => 999, // ID qui n'existe pas
            'description2' => 'Description',
            'prix' => 100
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['categorie_id']);
    }

    #[Test]
    public function it_validates_image_files_when_uploading()
    {
        $invalidFile = UploadedFile::fake()->create('document.pdf', 1000);

        $response = $this->postJson('/api/produits', [
            'name' => 'Produit avec fichier invalide',
            'categorie_id' => $this->categorie->id,
            'description2' => 'Description',
            'prix' => 100,
            'images' => [$invalidFile]
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['images.0']);
    }
}
