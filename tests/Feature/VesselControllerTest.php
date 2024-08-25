<?php

namespace Tests\Feature;

use App\Models\Vessel;
use App\Models\VesselOpex;
use App\Models\Voyage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VesselControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_financial_report()
    {
        $vessel = Vessel::factory()->create();

        $voyage = Voyage::factory()->create([
            'vessel_id' => $vessel->id,
            'start' => '2023-08-01',
            'end' => '2023-08-10',
            'revenues' => 5000.00,
            'expenses' => 2000.00,
        ]);

        VesselOpex::factory()->create([
            'vessel_id' => $vessel->id,
            'date' => '2023-08-01',
            'expenses' => 100.00,
        ]);

        VesselOpex::factory()->create([
            'vessel_id' => $vessel->id,
            'date' => '2023-08-02',
            'expenses' => 100.00,
        ]);

        $response = $this->getJson('/api/vessels/' . $vessel->id . '/financial-report');

        $response->assertStatus(200)
            ->assertJson([
                [
                    'voyage_id' => $voyage->id,
                    'voyage_profit' => 3000.00,
                    'net_profit' => 2800.00,
                    'voyage_profit_daily_average' => 300.00,
                    'net_profit_daily_average' => 280.00,
                ]
            ]);
    }
}
