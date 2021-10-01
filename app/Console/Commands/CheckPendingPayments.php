<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

use App\Enums\MembershipStatus;
use App\Enums\ExpirePendingType;

use App\Mail\ExpirePendingMail;

use App\Models\Membership;

class CheckPendingPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkpending:payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $memberships = Membership::where('status', MembershipStatus::PENDING)
            ->where('notify_status', '<>', ExpirePendingType::EXPIRE_END)
            ->select(
                '*',
                DB::raw('DATEDIFF(NOW(), created_at) as pastDays')
            )->get();

        $expireGroups = [
            ExpirePendingType::EXPIRE_TWO_DAYS => [],
            ExpirePendingType::EXPIRE_SEVEN_DAYS => [],
            ExpirePendingType::EXPIRE_FOURTEEN_DAYS => [],
            ExpirePendingType::EXPIRE_END => []
        ];

        foreach($memberships as $membership) {
            switch($membership->notify_status) {
                case ExpirePendingType::EXPIRE_NORMAL:
                    if($membership->pastDays >= 2) {
                        $expireGroups[ExpirePendingType::EXPIRE_TWO_DAYS][] = $membership;
                    }
                    break;
                case ExpirePendingType::EXPIRE_TWO_DAYS:
                    if($membership->pastDays >= 7) {
                        $expireGroups[ExpirePendingType::EXPIRE_SEVEN_DAYS][] = $membership;
                    }
                    break;
                case ExpirePendingType::EXPIRE_SEVEN_DAYS:
                    if($membership->pastDays >= 14) {
                        $expireGroups[ExpirePendingType::EXPIRE_FOURTEEN_DAYS][] = $membership;
                    }
                    break;
                case ExpirePendingType::EXPIRE_FOURTEEN_DAYS:
                    if($membership->pastDays >= 30) {
                        $expireGroups[ExpirePendingType::EXPIRE_END][] = $membership;
                    }
                    break;
                default:
                    break;
            }
        }

        foreach($expireGroups as $key => $expireGroup) {
            foreach($expireGroup as $membership) {
                Mail::to($membership->owner->email)
                    ->send(new ExpirePendingMail($membership, $key));
                $membership->notify_status = $key;
                if ($key == ExpirePendingType::EXPIRE_END) {
                    $membership->delete();
                } else {
                    $membership->save();
                }
            }
        }
    }
}
