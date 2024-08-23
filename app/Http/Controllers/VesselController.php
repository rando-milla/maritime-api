<?php
namespace App\Http\Controllers;

use App\Models\Vessel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VesselController extends Controller
{
    public function financialReport($vesselId): \Illuminate\Http\JsonResponse
    {
        $vessel = Vessel::findOrFail($vesselId);

        $report = DB::table('voyages')
            ->leftJoin('vessel_opex', function ($join) {
                $join->on('voyages.vessel_id', '=', 'vessel_opex.vessel_id')
                    ->whereBetween('vessel_opex.date', [DB::raw('voyages.start'), DB::raw('voyages.end')]);
            })
            ->select(
                'voyages.id as voyage_id',
                'voyages.start',
                'voyages.end',
                'voyages.revenues as voyage_revenues',
                'voyages.expenses as voyage_expenses',
                DB::raw('voyages.revenues - voyages.expenses as voyage_profit'),
                DB::raw('(voyages.revenues - voyages.expenses) / DATEDIFF(voyages.end, voyages.start) as voyage_profit_daily_average'),
                DB::raw('SUM(vessel_opex.expenses) as vessel_expenses_total'),
                DB::raw('(voyages.revenues - voyages.expenses) - SUM(vessel_opex.expenses) as net_profit'),
                DB::raw('((voyages.revenues - voyages.expenses) - SUM(vessel_opex.expenses)) / DATEDIFF(voyages.end, voyages.start) as net_profit_daily_average')
            )
            ->where('voyages.vessel_id', $vesselId)
            ->groupBy('voyages.id')
            ->get();

        return response()->json($report, 200);
    }
}
