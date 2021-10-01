<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use App\Enums\MembershipStatus;

use App\User;
use App\Models\MemberPlanType;
use App\Enums\PlanType;

class MembershipPending extends Model
{
    protected $table = "membership_pendings";

    protected $fillable = [
        'owner_id', 'user_id', 'plan_type_id', 'expires_in', 'total_price', 'status', 'notify_status'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
    ];

    public static function getMemberPlans($status, $ownerId=null) {
        $query = static::from('membership_pendings as mp')
            ->leftJoin('users as u', 'mp.user_id', 'u.id')
            ->leftJoin('user_infos as ui', 'mp.user_id', 'ui.user_id')
            ->leftJoin('member_plan_types as mpt', 'mp.plan_type_id', 'mpt.id');

        if ($ownerId) {
            $query = $query->where('owner_id', $ownerId);
        }
        
        $query = $query->where('status', $status)
            ->where('plan_type_id', '<>', PlanType::NOPLAN)
            ->select(
                'mp.id',
                'mp.id as pending_id',
                'mp.user_id',
                'mp.plan_type_id',
                'ui.first_name',
                'ui.last_name',
                'mpt.name as plan_name',
                'u.relationship',
                DB::raw('IF(u.is_adult=1, mpt.adult_price, mpt.child_price) as plan_price')
            );
        $memberPlans = $query->get();

        return $memberPlans;
    }

    public function planType() {
        return $this->belongsTo(MemberPlanType::class, 'plan_type_id', 'id');
    }

    public function owner() {
        return $this->belongsTo(
            User::class,
            'owner_id',
            'id'
        );
    }

    public function user() {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id'
        );
    }
}
