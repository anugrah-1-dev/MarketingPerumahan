<?php

namespace App\Http\Controllers;

use App\Models\WaClick;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WablasWebhookController extends Controller
{
    /**
     * Terima webhook pesan masuk dari Wablas.
     *
     * Wablas mengirim POST ke URL webhook setiap kali ada pesan masuk ke nomor WA bisnis.
     * Format payload Wablas:
     *   { "data": { "phone": "628xxx", "name": "...", "message": "...", "is_group": false }, "token": "..." }
     * atau versi flat:
     *   { "phone": "628xxx", "name": "...", "message": "...", "token": "..." }
     *
     * Cara setup di Wablas:
     *   Dashboard Wablas → Device → Setting → Webhook URL → masukkan:
     *   https://bukitshangrillaasri2.com/webhook/wablas?secret=ISI_WABLAS_WEBHOOK_SECRET
     */
    public function handle(Request $request)
    {
        // ── 1. Verifikasi secret ──────────────────────────────────────────────
        $configuredSecret = config('services.wablas.webhook_secret');
        if ($configuredSecret) {
            $incomingSecret = $request->query('secret')
                           ?? $request->input('secret')
                           ?? $request->header('X-Wablas-Secret');

            if ($incomingSecret !== $configuredSecret) {
                Log::warning('Wablas webhook: secret tidak valid', ['ip' => $request->ip()]);
                return response()->json(['ok' => false, 'message' => 'Unauthorized'], 401);
            }
        }

        // ── 2. Parse payload (dukung format nested data.xxx dan flat xxx) ─────
        $payload     = $request->all();
        $data        = $payload['data'] ?? $payload;
        $phone       = $data['phone']   ?? null;
        $name        = $data['name']    ?? null;
        $message     = $data['message'] ?? null;
        $isGroup     = (bool) ($data['is_group'] ?? false);

        // Abaikan pesan dari grup WhatsApp
        if ($isGroup) {
            return response()->json(['ok' => true, 'skipped' => 'group']);
        }

        // Nomor HP wajib ada
        if (empty($phone)) {
            Log::warning('Wablas webhook: payload tanpa phone', $payload);
            return response()->json(['ok' => false, 'message' => 'Missing phone'], 422);
        }

        // ── 3. Normalisasi nomor HP → format 62xxx ────────────────────────────
        $phone = $this->normalizePhone($phone);

        // ── 4. Upsert ke wa_clicks ────────────────────────────────────────────
        // Cari lead yang sudah ada berdasarkan nomor HP
        $existing = WaClick::where('sender_phone', $phone)->latest()->first();

        if ($existing) {
            // Update lead yang sudah ada: nama, pesan terbaru, bump status jika masih 'new'
            $existing->update([
                'sender_name'  => $name ?? $existing->sender_name,
                'last_message' => $message,
                'status'       => $existing->status === 'new' ? 'follow-up' : $existing->status,
            ]);

            Log::info("Wablas webhook: pesan masuk diperbarui untuk lead #{$existing->id}", [
                'phone' => $phone,
                'name'  => $name,
            ]);
        } else {
            // Buat lead baru dari pesan masuk
            WaClick::create([
                'sender_phone' => $phone,
                'sender_name'  => $name,
                'last_message' => $message,
                'source'       => 'wablas',
                'device'       => $this->guessDevice($request->userAgent() ?? ''),
                'ip_address'   => $request->ip(),
                'status'       => 'new',
            ]);

            Log::info("Wablas webhook: lead baru dibuat dari pesan masuk", [
                'phone' => $phone,
                'name'  => $name,
            ]);
        }

        return response()->json(['ok' => true]);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function normalizePhone(string $phone): string
    {
        // Hapus karakter non-digit
        $phone = preg_replace('/\D/', '', $phone);

        // Ganti awalan 0 dengan 62
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // Tambahkan 62 jika belum ada
        if (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }

    private function guessDevice(string $ua): string
    {
        return preg_match('/Mobile|Android|iPhone|iPad/i', $ua) ? 'Mobile' : 'Desktop';
    }
}
