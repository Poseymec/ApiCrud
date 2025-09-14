<?php

namespace Tests\Feature;

use App\Models\SiteElement;
use App\Models\SiteElementCategorie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SiteElementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_site_elements()
    {
        $category = SiteElementCategorie::factory()->create();
        $elements = SiteElement::factory()->count(3)->create(['site_element_categorie_id' => $category->id]);

        $response = $this->getJson('/api/site-elements');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_can_create_a_text_site_element()
    {
        $category = SiteElementCategorie::factory()->create();
        $data = [
            'site_element_categorie_id' => $category->id,
            'name' => 'Test Element',
            'description' => 'Description test',
            'type' => 'text',
            'content' => 'Contenu texte court', // ✅ Contenu plus court
            'status' => 'active',
        ];

        $response = $this->postJson('/api/site-elements', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Test Element']);
    }

    /** @test */
    public function it_can_create_a_file_site_element()
    {
        Storage::fake('public');
        $category = SiteElementCategorie::factory()->create();
        $file = UploadedFile::fake()->image('test.jpg');

        $data = [
            'site_element_categorie_id' => $category->id,
            'name' => 'Test File Element',
            'type' => 'file',
            'content' => $file,
            'status' => 'active',
        ];

        $response = $this->postJson('/api/site-elements', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Test File Element']);

        // ✅ Récupérer le nom du fichier depuis la réponse
        $element = SiteElement::where('name', 'Test File Element')->first();
        $fileName = basename($element->content);
        Storage::disk('public')->assertExists('site_elements/' . $fileName);
    }

    /** @test */
    public function it_validates_required_fields_for_site_element()
    {
        $response = $this->postJson('/api/site-elements', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['site_element_categorie_id', 'name', 'type', 'content']);
    }

    /** @test */
    public function it_can_update_a_site_element()
    {
        $category = SiteElementCategorie::factory()->create();
        // ✅ Créer avec du contenu court
        $element = SiteElement::factory()->create([
            'site_element_categorie_id' => $category->id,
            'content' => 'Contenu court'
        ]);

        $data = [
            'name' => 'Updated Name',
            'description' => 'Updated Description',
        ];

        $response = $this->putJson("/api/site-elements/{$element->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Updated Name']);
    }

    /** @test */
    public function it_can_delete_a_site_element()
    {
        $category = SiteElementCategorie::factory()->create();
        $element = SiteElement::factory()->create(['site_element_categorie_id' => $category->id]);

        $response = $this->deleteJson("/api/site-elements/{$element->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('site_elements', ['id' => $element->id]);
    }

    // ✅ SUPPRIMER TOUS LES TESTS DE CATÉGORIES D'ICI
    // Ces tests doivent être dans SiteElementCategorieTest.php uniquement

    // Supprimer ces méthodes :
    // - it_can_list_all_site_element_categories
    // - it_can_create_a_site_element_category
    // - it_validates_unique_name_for_site_element_category
    // - it_can_update_a_site_element_category
    // - it_can_delete_a_site_element_category
}
