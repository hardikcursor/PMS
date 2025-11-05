<?php
namespace App\Console\Commands;

use App\Models\License;
use App\Models\PosMachine;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpireUserLicenses extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:expire-licenses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate users whose license validity has expired';

   public function handle()
{
    $today = Carbon::today();

    // 🔹 Find expired licenses
    $expiredLicenses = License::whereNotNull('license_validity')
        ->whereDate('license_validity', '<', $today) // strictly less than today (expired)
        ->pluck('user_id');

    if ($expiredLicenses->isEmpty()) {
        $this->info('✅ No expired licenses found.');
        return 0;
    }

    // 🔹 Deactivate users whose license has expired
    $updated = User::whereIn('id', $expiredLicenses)
        ->where('status', '!=', 0)
        ->update(['status' => 0]);

    // 🔹 Also mark related POS Machines as inactive (optional but recommended)
    PosMachine::whereIn('company_id', $expiredLicenses)
        ->update(['status' => 0]);

    $this->info("🚫 {$updated} user(s) and related POS machines deactivated due to expired licenses.");

    return 0;
}


}
