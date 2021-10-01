<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

use App\Enums\MembershipStatus;
use App\Enums\ExpireType;

use App\Mail\ExpireMail;

use App\Models\Membership;

class CheckMembershipExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'membership:expire';

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
        $memberships = Membership::where('status', MembershipStatus::PURCHASE)
            ->select(
                '*',
                DB::raw('DATEDIFF(expires_in, NOW()) as remainingDays')
            )->get();

        $expireGroups = [
            ExpireType::EXPIRE_THIRTY_DAYS => [],
            ExpireType::EXPIRE_FOURTEEN_DAYS => [],
            ExpireType::EXPIRE_SEVEN_DAYS => [],
            ExpireType::EXPIRE_TWO_DAYS => [],
            ExpireType::EXPIRE_END => []
        ];

        foreach($memberships as $membership) {
            switch($membership->notify_status) {
                case ExpireType::EXPIRE_NORMAL:
                    if($membership->remainingDays <= 30) {
                        $expireGroups[ExpireType::EXPIRE_THIRTY_DAYS][] = $membership;
                    }
                    break;
                case ExpireType::EXPIRE_THIRTY_DAYS:
                    if($membership->remainingDays <= 14) {
                        $expireGroups[ExpireType::EXPIRE_FOURTEEN_DAYS][] = $membership;
                    }
                    break;
                case ExpireType::EXPIRE_FOURTEEN_DAYS:
                    if($membership->remainingDays <= 7) {
                        $expireGroups[ExpireType::EXPIRE_SEVEN_DAYS][] = $membership;
                    }
                    break;
                case ExpireType::EXPIRE_SEVEN_DAYS:
                    if($membership->remainingDays <= 2) {
                        $expireGroups[ExpireType::EXPIRE_TWO_DAYS][] = $membership;
                    }
                    break;
                case ExpireType::EXPIRE_TWO_DAYS:
                    if($membership->remainingDays <= 0) {
                        $expireGroups[ExpireType::EXPIRE_END][] = $membership;
                    }
                    break;
                default:
                    break;
            }
        }

        foreach($expireGroups as $key => $expireGroup) {
            foreach($expireGroup as $membership) {
                Mail::to($membership->owner->email)
                    ->send(new ExpireMail($membership, $key));
                $membership->notify_status = $key;
                if ($key == ExpireType::EXPIRE_END) {
                    $membership->status = MembershipStatus::EXPIRED;
                    $membership->primary_update = 0;
                    $membership->admin_update = 0;
                }
                $membership->save();
            }
        }
    }
}
