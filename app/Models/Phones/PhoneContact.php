<?php

namespace App\Models\Phones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PhoneContact extends Model
{
    use HasFactory;

    protected $table = 'dir_contacts';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public $incrementing = true;

    protected $fillable = [
        'name'
    ];

    public function contract (){
        return $this->hasMany(PhoneContract::class, 'dir_contacts_id');
    }
}
