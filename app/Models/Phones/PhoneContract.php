<?php

namespace App\Models\Phones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneContract extends Model
{
    use HasFactory;
    protected $table = 'pho_phone_contracts';
    protected $fillable = ['code', 'start_date', 'expiry_date', 'active', 'dir_contact_id', 'deleted_at'];

    public function contacts()
    {
        return $this->hasMany(PhoneContact::class, 'id');
    }
}
