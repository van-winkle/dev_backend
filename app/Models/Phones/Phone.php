<?php

namespace App\Models\Phones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    use HasFactory;
    protected $table = 'pho_phones';

    protected $fillable = [
        'number',
        'type',
        'imei',
        'price',
        'active',
        'adm_employees_id',
        'pho_phone_plan_id',
        'pho_phone_contract_id',
        'pho_phone_model_id',
        'deleted_at'
    ];
}
