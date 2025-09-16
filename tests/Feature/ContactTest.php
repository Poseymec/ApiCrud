<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_contacts()
    {
        Contact::factory()->count(3)->create();

        $response = $this->getJson('/api/contacts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data'
            ])
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_can_create_a_contact()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'Hello, test message',
            'phone' => '699999999' // 🔥 ajouté
        ];

        $response = $this->postJson('/api/contacts', $data);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'message' => 'Hello, test message',
                'phone' => '699999999'
            ]);

        $this->assertDatabaseHas('contacts', $data);
    }

    /** @test */
    public function it_can_show_a_contact()
    {
        $contact = Contact::factory()->create();

        $response = $this->getJson("/api/contacts/{$contact->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $contact->id,
                'name' => $contact->name,
            ]);
    }

    /** @test */
    public function it_can_delete_a_contact()
    {
        $contact = Contact::factory()->create();

        $response = $this->deleteJson("/api/contacts/{$contact->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'information supprimée avec succes'
            ]);

        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }

    /** @test */
    public function it_can_toggle_contact_status()
    {
        $contact = Contact::factory()->create(['status' => 'unread']);

        $response = $this->patchJson("/api/contacts/{$contact->id}/toggle-status");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'status' => 'read',
            ]);

        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'status' => 'read',
        ]);
    }
}
