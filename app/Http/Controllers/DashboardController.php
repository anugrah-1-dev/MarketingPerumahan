<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Closing;
use App\Models\Unit;
use App\Models\WaClick;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function data(Request $request)
    {
        // ── Stats ──────────────────────────────────────────────────────────────
        $unitStats       = Unit::stats();
        $totalClicks    = WaClick::count();
        $totalClosing   = $unitStats['terjual'];
        $totalAgents    = Agent::count();
        // Komisi = total dari semua closing yang tercatat
        $totalCommission = Closing::sum('komisi_nominal');

        // ── Tren klik 7 hari terakhir ─────────────────────────────────────────
        $trendData = WaClick::selectRaw('DATE(created_at) as tgl, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('tgl')
            ->orderBy('tgl')
            ->pluck('total', 'tgl')
            ->toArray();

        $labels   = [];
        $trendArr = [];
        for ($i = 6; $i >= 0; $i--) {
            $date       = Carbon::today()->subDays($i);
            $labels[]   = $date->translatedFormat('D');
            $trendArr[] = $trendData[$date->toDateString()] ?? 0;
        }

        // ── Top 5 Agent ───────────────────────────────────────────────────────
        $topAgents = Agent::withCount(['waClicks as clicks_count'])
            ->withCount(['waClicks as closings_count' => function ($q) {
                $q->where('status', 'closed');
            }])
            ->aktif()
            ->orderByDesc('clicks_count')
            ->take(5)
            ->get()
            ->map(fn($a) => [
                'id'       => $a->id,
                'name'     => $a->nama,
                'clicks'   => $a->clicks_count,
                'closings' => $a->closings_count,
                'avatar'   => strtoupper(substr($a->nama, 0, 1)) . strtoupper(substr(strrchr($a->nama, ' ') ?: $a->nama, 1, 1)),
            ])->values()->toArray();

        // ── Aktivitas terbaru ─────────────────────────────────────────────────
        $recent = WaClick::with('agent')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($c) {
                $agentName = $c->agent?->nama ?? 'Umum';
                $activity  = $c->status === 'closed'
                    ? "Closing – {$agentName}"
                    : "Klik WA – {$agentName}";

                return [
                    'time'     => $c->created_at->diffForHumans(),
                    'agent'    => $agentName,
                    'activity' => $activity,
                    'status'   => $c->status,
                ];
            })->toArray();

        return response()->json([
            'stats' => [
                'totalClicks'     => $totalClicks,
                'totalClosing'    => $totalClosing,
                'totalCommission' => $totalCommission,
                'totalAgents'     => $totalAgents,
            ],
            'clickTrends' => [
                'labels' => $labels,
                'data'   => $trendArr,
            ],
            'topAgents'      => $topAgents,
            'recentActivity' => $recent,
        ]);
    }
}
