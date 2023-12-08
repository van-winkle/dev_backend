<?php

namespace App\Models\Phones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneBrand extends Model
{
    use HasFactory;
    // name the table
protected $table = 'pho_phone_brands';
// Table Fields
protected $fillable = [
'name','active','deleted_at',
];
}
