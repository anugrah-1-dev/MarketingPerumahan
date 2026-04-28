<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Auto-generate referral code unik saat user baru dibuat.
     */
    public function creating(User $user): void
    {
        if (empty($user->referral_code)) {
            $user->referral_code = User::generateReferralCode();
        }
    }
}
