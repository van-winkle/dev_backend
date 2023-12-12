<?php

namespace App\Models\Phones;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Phone extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'pho_phones';

    protected $fillable = [
        'number',
        'type',
        'imei',
        'price',
        'active',
        'adm_employee_id',
        'pho_phone_plan_id',
        'pho_phone_contract_id',
        'pho_phone_model_id',
        'deleted_at'
    ];
}
