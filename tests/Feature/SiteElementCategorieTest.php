<?php

namespace Tests\Feature;

use App\Models\SiteElementCategorie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteElementCategorieTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_categories()
    {
        $categories = SiteElementCategorie::factory()->count(3)->create();

        $response = $this->getJson('/api/site-element-categories');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_can_create_a_category()
    {
        $data = ['name' => 'Nouvelle Catégorie'];

        $response = $this->postJson('/api/site-element-categories', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Nouvelle Catégorie']);
    }

    /** @test */
    public function it_validates_unique_name_for_category()
    {
        SiteElementCategorie::factory()->create(['name' => 'Catégorie Existante']);

        $response = $this->postJson('/api/site-element-categories', ['name' => 'Catégorie Existante']);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function it_can_update_a_category()
    {
        $category = SiteElementCategorie::factory()->create();
        $data = ['name' => 'Catégorie Mise à Jour'];

        $response = $this->putJson("/api/site-element-categories/{$category->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Catégorie Mise à Jour']);
    }

    /** @test */
    public function it_can_delete_a_category()
    {
        $category = SiteElementCategorie::factory()->create();

        $response = $this->deleteJson("/api/site-element-categories/{$category->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('site_element_categories', ['id' => $category->id]);
    }
}
