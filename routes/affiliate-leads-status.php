<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\WaClick;

Route::middleware(['auth', 'role:affiliate'])->prefix('affiliate')->name('affiliate.')->group(function () {
    // ...existing routes...
    Route::patch('/leads/{id}/status', function(Request $request, $id) {
        $user = auth()->user();
        $waClick = WaClick::where('id', $id)
            ->where('affiliate_user_id', $user->id)
            ->firstOrFail();

        $request->validate([
            'status'         => 'required|in:new,follow-up,interested,not-interested',
            'notes'          => 'nullable|string|max:1000',
            'follow_up_date' => 'nullable|date',
        ]);

        $waClick->update([
            'status'         => $request->status,
            'notes'          => $request->notes,
            'follow_up_date' => $request->follow_up_date,
        ]);

        return response()->json(['ok' => true, 'click' => $waClick]);
    })->name('leads.status');
});
