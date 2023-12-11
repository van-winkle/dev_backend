<?php

namespace App\Models\Phones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhonePlan extends Model
{
    use HasFactory;
    protected $table = 'pho_phone_plans';
    protected $fillable = ['name','mobile_data','roaming_data','minutes','roaming_minutes',
    'type','active','pho_phone_contract_id','deleted_at'];

}
