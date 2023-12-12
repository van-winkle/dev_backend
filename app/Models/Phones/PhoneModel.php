<?php

namespace App\Models\Phones;

use App\Models\Phones\PhoneBrand;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PhoneModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'pho_phone_models';

    protected $primaryKey = 'id';

    protected $keyType = true;

    public $fillable = [
    'name',
    'active',
    'pho_phone_brand_id'
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

    public function brand ()
    {
        return $this->belongsTo(PhoneBrand::class, 'pho_phone_brand_id');
    }
}
