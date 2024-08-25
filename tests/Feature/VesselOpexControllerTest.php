<?php

namespace Tests\Feature;

use App\Models\Vessel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VesselOpexControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_vessel_opex()
    {
        $vessel = Vessel::factory()->create();

        $response = $this->postJson('/api/vessels/' . $vessel->id . '/vessel-opex', [
            'date' => '2023-08-01',
            'expenses' => 300.00,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'vessel_id' => $vessel->id,
                'expenses' => 300.00,
            ]);
    }

    public function test_cannot_create_duplicate_vessel_opex()
    {
        $vessel = Vessel::factory()->create();

        $this->postJson('/api/vessels/' . $vessel->id . '/vessel-opex', [
            'date' => '2023-08-01',
            'expenses' => 300.00,
        ]);

        $response = $this->postJson('/api/vessels/' . $vessel->id . '/vessel-opex', [
            'date' => '2023-08-01',
            'expenses' => 400.00,
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'error' => 'Opex for this date already exists.',
            ]);
    }
}
