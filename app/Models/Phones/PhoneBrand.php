<?php

namespace App\Models\Phones;

use App\Models\Phones\PhoneModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PhoneBrand extends Model
{
    use HasFactory;
    // name the table
    protected $table = 'pho_phone_brands';
    // Table Fields
    protected $fillable = [
        'name', 'active', 'deleted_at',
    ];

    public function brands()
    {
        return $this->hasMany(PhoneModel::class, 'id');
    }
}
