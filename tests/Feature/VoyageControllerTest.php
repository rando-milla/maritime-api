<?php

namespace Tests\Feature;

use App\Models\Vessel;
use App\Models\Voyage;
use Carbon\Carbon;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoyageControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_voyage()
    {
        $vessel = Vessel::factory()->create();

        $response = $this->postJson('/api/voyages', [
            'vessel_id' => $vessel->id,
            'start' => now()->format('Y-m-d H:i:s'),
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'pending',
                'vessel_id' => $vessel->id,
            ]);
    }

    public function test_update_voyage()
    {
        $voyage = Voyage::factory()->create([
            'status' => 'pending',
            'start' => Carbon::now()->format('Y-m-d H:i:s'),
            'end' =>  Carbon::now()->addDays(7)->format('Y-m-d H:i:s'),
            'revenues' => 1000.00,
            'expenses' => 500.00,
        ]);

        $response = $this->putJson('/api/voyages/' . $voyage->id, [
            'status' => 'submitted',
            'end' => Carbon::now()->addDays(7)->format('Y-m-d H:i:s'),
            'revenues' => 1200.00,
            'expenses' => 600.00,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'submitted',
                'profit' => 600.00,
            ]);
    }

    public function test_cannot_edit_submitted_voyage()
    {
        $voyage = Voyage::factory()->create();

        $this->putJson('/api/voyages/' . $voyage->id, [
            'status' => 'submitted',
        ]);

        $response = $this->putJson('/api/voyages/' . $voyage->id, [
            'status' => 'pending',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'error' => 'Cannot edit a submitted voyage.',
            ]);
    }
}
