<?php

namespace App\Models\Phones;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Phone extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pho_phones';

    protected $primaryKey= "id";

    protected $KeyType = "int";

    public $incrementing = true;

    protected $fillable = [
        'type',
        'number',
        'imei',
        'price',
        'active',
        'adm_employee_id',
        'pho_phone_plan_id',
        'pho_phone_contract_id',
        'pho_phone_model_id',
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [];

    protected static $recordEvents = [
        'created',
        'updated',
        'deleted',
    ];

    public function employee()
    {
        return $this->belongsTo(AdminEmployee::class, 'adm_employee_id');
    }

    public function plan()
    {
        return $this->belongsTo(PhonePlan::class, 'pho_phone_plan_id');
    }

    public function contract()
    {
        return $this->belongsTo(PhoneContract::class, 'pho_phone_contract_id');
    }

    public function model()
    {
        return $this->belongsTo(PhoneModel::class, 'pho_phone_model_id');
    }
}
