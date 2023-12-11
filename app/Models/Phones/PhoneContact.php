<?php

namespace App\Models\Phones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneContact extends Model
{
    use HasFactory;
    protected $table = 'dir_contacts';
    protected $fillable = ['name','deleted_at'];

    public function contracts (){
        return $this->hasMany(PhoneContract::class, 'id');
    }
}
