<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\WaClick;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    // ──────────────────────────────────────────────────────────────────────────
    // API: Catat klik WA dari landing page (publik, tanpa auth)
    // POST /api/wa-click
    // ──────────────────────────────────────────────────────────────────────────
    public function record(Request $request)
    {
        $ua     = $request->userAgent() ?? '';
        $isMob  = preg_match('/Mobile|Android|iPhone|iPad/i', $ua);
        $device = $isMob ? 'Mobile' : 'Desktop';

        // Deteksi browser kasar
        $browser = 'Other';
        if (str_contains($ua, 'Edg'))        $browser = 'Edge';
        elseif (str_contains($ua, 'OPR'))    $browser = 'Opera';
        elseif (str_contains($ua, 'Chrome')) $browser = 'Chrome' . ($isMob ? ' Mobile' : '');
        elseif (str_contains($ua, 'Firefox'))$browser = 'Firefox';
        elseif (str_contains($ua, 'Safari')) $browser = 'Safari';

        // Cari agent berdasarkan slug
        $slug  = $request->input('slug');
        $agent = $slug ? Agent::where('slug', $slug)->where('aktif', true)->first() : null;

        WaClick::create([
            'agent_id'   => $agent?->id,
            'agent_slug' => $slug,
            'ip_address' => $request->ip(),
            'device'     => $device,
            'browser'    => $browser,
            'page_url'   => $request->input('page_url'),
            'status'     => 'new',
        ]);

        return response()->json(['ok' => true]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // API: Ambil semua data klik (admin)
    // GET /admin/tracking/data
    // ──────────────────────────────────────────────────────────────────────────
    public function data(Request $request)
    {
        $query = WaClick::with('agent')->latest();

        // Filter tanggal
        $date = $request->query('date', 'all');
        match ($date) {
            'today'     => $query->whereDate('created_at', today()),
            'yesterday' => $query->whereDate('created_at', today()->subDay()),
            'week'      => $query->where('created_at', '>=', now()->subDays(7)),
            'month'     => $query->where('created_at', '>=', now()->subDays(30)),
            default     => null,
        };

        // Filter agent
        if ($agent = $request->query('agent')) {
            $query->where('agent_id', $agent);
        }

        // Filter status
        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        // Search
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%$search%")
                  ->orWhere('agent_slug', 'like', "%$search%")
                  ->orWhereHas('agent', fn($q2) => $q2->where('nama', 'like', "%$search%"));
            });
        }

        $clicks = $query->get()->map(function ($c) {
            return [
                'id'            => $c->id,
                'timestamp'     => $c->created_at->format('Y-m-d H:i:s'),
                'agentId'       => $c->agent_id,
                'agentName'     => $c->agent?->nama ?? '(Umum)',
                'agentSlug'     => $c->agent_slug ?? '-',
                'ipAddress'     => $c->ip_address ?? '-',
                'device'        => $c->device ?? '-',
                'browser'       => $c->browser ?? '-',
                'pageUrl'       => $c->page_url ?? '-',
                'status'        => $c->status,
                'notes'         => $c->notes ?? '',
                'followUpDate'  => $c->follow_up_date?->format('Y-m-d H:i:s'),
            ];
        });

        // Statistik
        $all    = WaClick::all();
        $stats  = [
            'today'      => WaClick::whereDate('created_at', today())->count(),
            'new'        => $all->where('status', 'new')->count(),
            'follow_up'  => $all->where('status', 'follow-up')->count(),
            'interested' => $all->where('status', 'interested')->count(),
        ];

        // List agents untuk dropdown filter
        $agents = Agent::aktif()->get(['id', 'nama']);

        return response()->json(compact('clicks', 'stats', 'agents'));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // API: Update status lead
    // PATCH /admin/tracking/{id}/status
    // ──────────────────────────────────────────────────────────────────────────
    public function updateStatus(Request $request, $id)
    {
        $click = WaClick::findOrFail($id);

        $request->validate([
            'status'        => 'required|in:new,follow-up,interested,not-interested,closed',
            'notes'         => 'nullable|string|max:1000',
            'follow_up_date'=> 'nullable|date',
        ]);

        $click->update([
            'status'         => $request->status,
            'notes'          => $request->notes,
            'follow_up_date' => $request->follow_up_date,
        ]);

        return response()->json(['ok' => true, 'click' => $click]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // View: Halaman tracking di admin
    // GET /admin/tracking
    // ──────────────────────────────────────────────────────────────────────────
    public function index()
    {
        return view('admin.tracking');
    }
}
