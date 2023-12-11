<?php

namespace App\Models\Phones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhonePlan extends Model
{
    use HasFactory;
    protected $table = 'pho_phone_plans';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    protected $incrementing = true;

    protected $fillable = [
        'name',
        'mobile_data',
        'roaming_data',
        'minutes',
        'roaming_minutes',
        'type',
        'active',
        'pho_phone_contract_id',
        'deleted_at'
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [];

}
