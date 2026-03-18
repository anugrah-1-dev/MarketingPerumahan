<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\User;
use App\Models\WaClick;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    // ──────────────────────────────────────────────────────────────────────────
    // API: Catat klik WA dari landing page (publik, tanpa auth)
    // POST /wa-click
    // ──────────────────────────────────────────────────────────────────────────
    public function record(Request $request)
    {
        $ua     = $request->userAgent() ?? '';
        $isMob  = preg_match('/Mobile|Android|iPhone|iPad/i', $ua);
        $device = $isMob ? 'Mobile' : 'Desktop';

        // Deteksi browser kasar
        $browser = 'Other';
        if (str_contains($ua, 'Edg'))         $browser = 'Edge';
        elseif (str_contains($ua, 'OPR'))     $browser = 'Opera';
        elseif (str_contains($ua, 'Chrome'))  $browser = 'Chrome' . ($isMob ? ' Mobile' : '');
        elseif (str_contains($ua, 'Firefox')) $browser = 'Firefox';
        elseif (str_contains($ua, 'Safari'))  $browser = 'Safari';

        // ── Resolusi Agent (dari slug lama) ──
        $slug  = $request->input('slug');
        $agent = $slug ? Agent::where('slug', $slug)->where('aktif', true)->first() : null;

        // ── Resolusi Referral Code (prioritas: request body → session → cookie) ──
        $refCode = $request->input('referral_code')
                ?? $request->session()->get('affiliate_ref_code')
                ?? $request->cookie('affiliate_ref_code');

        $affiliateUserId = null;
        if ($refCode) {
            // Cari affiliate user berdasarkan kode
            $affiliateUser   = User::where('referral_code', strtoupper($refCode))
                                   ->where('role', 'affiliate')
                                   ->first();
            $affiliateUserId = $affiliateUser?->id;
        } else {
            // Fallback: coba dari session
            $affiliateUserId = $request->session()->get('affiliate_user_id')
                             ?? $request->cookie('affiliate_user_id');
        }

        WaClick::create([
            'agent_id'          => $agent?->id,
            'agent_slug'        => $slug,
            'referral_code'     => $refCode ? strtoupper($refCode) : null,
            'affiliate_user_id' => $affiliateUserId,
            'ip_address'        => $request->ip(),
            'device'            => $device,
            'browser'           => $browser,
            'page_url'          => $request->input('page_url'),
            'sender_name'       => $request->input('sender_name'),
            'sender_phone'      => $request->input('sender_phone'),
            'source'            => 'website',
            'status'            => ($request->filled('sender_name') && $request->filled('sender_phone')) ? 'follow-up' : 'new',
        ]);

        return response()->json(['ok' => true]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // API: Ambil semua data klik (admin)
    // GET /admin/tracking/data
    // ──────────────────────────────────────────────────────────────────────────
    public function data(Request $request)
    {
        $query = WaClick::with(['agent', 'affiliateUser'])->latest();

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
        $agentParam = $request->query('agent');
        if ($agentParam && $agentParam !== 'all') {
            $query->where('agent_id', $agentParam);
        }

        // Filter affiliate (by referral_code)
        if ($ref = $request->query('ref_code')) {
            $query->where('referral_code', $ref);
        }

        // Filter status
        $statusParam = $request->query('status');
        if ($statusParam && $statusParam !== 'all') {
            $query->where('status', $statusParam);
        }

        // Search
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%$search%")
                  ->orWhere('agent_slug', 'like', "%$search%")
                  ->orWhere('referral_code', 'like', "%$search%")
                  ->orWhere('sender_phone', 'like', "%$search%")
                  ->orWhere('sender_name', 'like', "%$search%")
                  ->orWhereHas('agent', fn($q2) => $q2->where('nama', 'like', "%$search%"))
                  ->orWhereHas('affiliateUser', fn($q2) => $q2->where('name', 'like', "%$search%"));
            });
        }

        $clicks = $query->get()->map(function ($c) {
            return [
                'id'                => $c->id,
                'timestamp'         => $c->created_at->format('Y-m-d H:i:s'),
                'agentId'           => $c->agent_id,
                'agentName'         => $c->agent?->nama ?? '(Umum)',
                'agentSlug'         => $c->agent_slug ?? '-',
                'referralCode'      => $c->referral_code ?? '-',
                'affiliateName'     => $c->affiliateUser?->name ?? '-',
                'ipAddress'         => $c->ip_address ?? '-',
                'device'            => $c->device ?? '-',
                'browser'           => $c->browser ?? '-',
                'pageUrl'           => $c->page_url ?? '-',
                'status'            => $c->status,
                'notes'             => $c->notes ?? '',
                'followUpDate'      => $c->follow_up_date?->format('Y-m-d H:i:s'),
                'senderPhone'       => $c->sender_phone ?? '',
                'senderName'        => $c->sender_name ?? '',
                'lastMessage'       => $c->last_message ?? '',
                'source'            => $c->source ?? 'website',
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
        $agents    = Agent::aktif()->get(['id', 'nama']);
        // List affiliate users untuk filter
        $affiliates = User::where('role', 'affiliate')->get(['id', 'name', 'referral_code']);

        return response()->json(compact('clicks', 'stats', 'agents', 'affiliates'));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // API: Update status lead
    // PATCH /admin/tracking/{id}/status
    // ──────────────────────────────────────────────────────────────────────────
    public function updateStatus(Request $request, $id)
    {
        $click = WaClick::findOrFail($id);

        $request->validate([
            'status'         => 'required|in:new,follow-up,interested,not-interested,closed',
            'notes'          => 'nullable|string|max:1000',
            'follow_up_date' => 'nullable|date',
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
        $user = auth()->user();
        $panel = $user && $user->isAdmin() ? 'manager' : 'admin';

        return view("{$panel}.tracking");
    }
}
