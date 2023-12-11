<?php

namespace App\Models\Phones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhoneModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'pho_phone_models';
    protected $fillable = ['name','active','pho_phone_brand_id','deleted_at'];
}
