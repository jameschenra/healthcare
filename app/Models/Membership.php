<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\User;
use App\Models\MemberPlanType;
use App\Enums\MembershipStatus;


class Membership extends Model
{
    protected $table = "memberships";

    protected $fillable = [
        'membership_number', 'owner_id', 'user_id', 'plan_type_id', 'status', 'notify_status', 'expires_in', 'total_price',
        'admin_update', 'primary_update', 'order', 'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
    ];

    /* public function getCreatedAtAttribute($value)
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('Y-m-d');
        return $date;
    } */

    public static function getOwnPlans($statuses, $ownerId) {
        return static::from('memberships as ms')
            ->leftJoin('member_plan_types as mpt', 'ms.plan_type_id', 'mpt.id')
            ->leftJoin('users as u', 'ms.user_id', 'u.id')
            ->leftJoin('user_infos as ui', 'ms.user_id', 'ui.user_id')
            ->where('owner_id', $ownerId)
            ->where(function($query) use ($statuses) {
                foreach ($statuses as $status) {
                    $query->orWhere('status', $status);
                }
            })->select(
                'ms.*',
                'u.is_adult',
                'u.relationship',
                'ui.first_name',
                'ui.last_name',
                'ui.birthday',
                'mpt.name as plan_name',
                'mpt.child_price',
                'mpt.adult_price'
            )->get();
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
