<?php

namespace App\Models\Phones;

use App\Models\Phones\PhoneContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContractAttaches extends Model
{
    use HasFactory,
    SoftDeletes;

    protected $table = 'pho_phone_contract_attaches';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public $incrementing = true;

    public $fillable = [
        'file_name_original',
        'name',
        'file_size',
        'file_extension',
        'file_mimetype',
        'file_location',
        'pho_phone_contract_id'
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

    public function contract()
    {
        return $this->belongsTo(PhoneContract::class, 'pho_phone_contract_id');
    }
}
