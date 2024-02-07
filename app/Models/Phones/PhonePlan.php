<?php

namespace App\Models\Phones;

use App\Models\Phones\Phone;
use App\Models\Phones\PhoneContract;
use App\Models\Phones\TypePhone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PhonePlan extends Model
{
    use HasFactory,
        SoftDeletes;

    protected $table = 'pho_phone_plans';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public $incrementing = true;

    public $fillable = [
        'name',
        'mobile_data',
        'roaming_data',
        'minutes',
        'roaming_minutes',
        'active',
        'type',
        'pho_phone_type_phone_id',
        'pho_phone_contract_id'
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [];

    protected static $recordEvents = [
        'created',
        'updated',
        'deleted'
    ];

    public function contract()
    {
        return $this->belongsTo(PhoneContract::class, 'pho_phone_contract_id');
    }

    public function phones()
    {
        return $this->hasMany(Phone::class, 'pho_phone_plan_id');
    }

    public function typePhone()
    {
        return $this->belongsTo(TypePhone::class, 'pho_phone_type_phone_id');
    }
}
