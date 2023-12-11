<?php

namespace App\Models\Phones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhoneContact extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'dir_contacts';
    protected $primarykey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    
    protected $fillable = ['name'];

    public function contracts (){
        return $this->hasMany(PhoneContract::class, 'dir_contact_id');
    }
}
