<?php

namespace App\Models\Phones;

use App\Models\Phones\PhonePlan;
use App\Models\Phones\PhoneModel;
use App\Models\Phones\AdminEmployee;
use App\Models\Phones\PhoneContract;
use App\Models\Phones\PhoneIncident;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Phone extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pho_phones';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public $incrementing = true;

    public $fillable = [
        'number',
        'imei',
        'price',
        'active',
        'pho_phone_type_phone_id',
        'adm_employee_id',
        'pho_phone_plan_id',
        'pho_phone_contract_id',
        'pho_phone_model_id',
        'adm_manager_id'
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

    public function type()
    {
        return $this->belongsTo(TypePhone::class, 'pho_phone_type_phone_id');
    }

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
    public function incidents ()
    {
        return $this->hasMany(PhoneIncident::class,'pho_phone_id');
    }
    public function manager()
    {
        return $this->belongsTo(AdminEmployee::class, 'adm_manager_id');
    }
}
