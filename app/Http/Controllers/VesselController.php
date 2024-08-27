<?php
namespace App\Http\Controllers;

use App\Http\Requests\Vessel\CreateVesselRequest;
use App\Http\Requests\Vessel\UpdateVesselRequest;
use App\Models\Vessel;
use Carbon\Carbon;

class VesselController extends Controller
{
    /**
     * Generate a financial report for a vessel.
     */
    public function financialReport($vesselId)
    {
        $vessel = Vessel::findOrFail($vesselId);

        $report = $vessel->voyages()->get()->map(function ($voyage) use ($vessel) {
            // Calculate profit
            $profit = $voyage->revenues - $voyage->expenses;

            // Calculate voyage duration in days (inclusive)
            $start = Carbon::parse($voyage->start)->startOfDay();
            $end = Carbon::parse($voyage->end)->endOfDay();
            $duration = $start->diffInDays($end) + 1;

            // Sum vessel operational expenses during the voyage
            $vesselExpensesTotal = $vessel->opex()
                ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
                ->sum('expenses');

            // Calculate daily average profit
            $voyageProfitDailyAverage = $duration > 0 ? $profit / $duration : 0;

            // Calculate net profit
            $netProfit = $profit - $vesselExpensesTotal;

            // Calculate daily average net profit
            $netProfitDailyAverage = $duration > 0 ? $netProfit / $duration : 0;

            return [
                'voyage_id' => $voyage->id,
                'start' => $voyage->start->toDateTimeString(),
                'end' => $voyage->end->toDateTimeString(),
                'voyage_revenues' => round($voyage->revenues, 2),
                'voyage_expenses' => round($voyage->expenses, 2),
                'voyage_profit' => round($profit, 2),
                'voyage_profit_daily_average' => round($voyageProfitDailyAverage, 2),
                'vessel_expenses_total' => round($vesselExpensesTotal, 2),
                'net_profit' => round($netProfit, 2),
                'net_profit_daily_average' => round($netProfitDailyAverage, 2),
            ];
        });

        return response()->json($report, 200);
    }

    /**
     * Update a vessel's details.
     */
    public function update(UpdateVesselRequest $request, Vessel $vessel)
    {
        $vessel->update($request->validated());
        $vessel->save();

        return response()->json($vessel, 200);
    }

    public function store(CreateVesselRequest $request)
    {
        $vessel = Vessel::create($request->validated());
        return response()->json($vessel, 201);
    }
}
