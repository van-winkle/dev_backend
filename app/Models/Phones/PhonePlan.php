<?php

namespace App\Models\Phones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Phones\PhoneContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhonePlan extends Model
{
    use HasFactory, SoftDeletes;
    
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
        'active',
        'type',
        'pho_phone_contract_id',
        'deleted_at'
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [];

    protected static $recordEvents= [
        'created',
        'updated',
        'deleted'
    ];

    public function contract(){
        return $this->belongsTo(PhoneContract::class, 'pho_phone_contract_id');
    }

}
