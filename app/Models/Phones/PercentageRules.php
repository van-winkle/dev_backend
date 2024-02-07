<?php

namespace App\Models\Phones;

use App\Models\Phones\PhoneContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PercentageRules extends Model
{
    use HasFactory,
        SoftDeletes;

    protected $table = 'pho_phone_percentage_rules';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public $incrementing = true;

    protected $fillable = [
        'percentage_discount',
        'pho_phone_contract_id'
    ];
    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $cast = [];

    protected static $recordEvents = [
        'created',
        'updated',
        'deleted'
    ];

    public function contract()
    {
        return $this->belongsTo(PhoneContract::class, 'pho_phone_contract_id');
    }
}
