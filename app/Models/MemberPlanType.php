<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberPlanType extends Model
{
    protected $table = "member_plan_types";

    protected $fillable = [
        'name', 'adult_price', 'child_price', 'desc_first', 'desc_second', 'desc_third'
    ];
}
