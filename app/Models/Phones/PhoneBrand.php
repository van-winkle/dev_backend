<?php

namespace App\Models\Phones;

use App\Models\Phones\PhoneModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PhoneBrand extends Model
{
    use HasFactory,
        SoftDeletes;

    protected $table = 'pho_phone_brands';

    protected $primaryKey = "id";

    protected $KeyType = "int";

    public $incrementing = true;

    public $fillable = [
        'name',
        'active',
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

    public function models()
    {
        return $this->hasMany(PhoneModel::class, 'pho_phone_brand_id');
    }
}
