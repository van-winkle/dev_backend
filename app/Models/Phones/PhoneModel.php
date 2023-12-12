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
    protected $fillable = ['name', 'active', 'pho_phone_brand_id', 'deleted_at'];

    public function brands()
    {
        return $this->belongsTo(PhoneBrand::class, 'id');
    }
}
