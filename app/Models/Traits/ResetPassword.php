<?php

namespace App\Models\Traits;

use Carbon\Carbon;
use DB;
use Hash;

trait ResetPassword
{
    public function canResetPassword(string $newPassword): bool
    {
        $usedPasswords = DB::table('password_resets')
            ->select()
            ->where([
                'user_id' => $this->id,
                'last_password' => Hash::make($newPassword),
            ])
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->get();

        return $usedPasswords->isEmpty();
    }

    public function addResetPassword(): void
    {
        DB::table('password_resets')
            ->insert([
                'user_id' => $this->id,
                'last_password' => $this->password,
                'created_at' => Carbon::now(),
            ]);
    }
}
