<?php

namespace App\Models\Phones;

use App\Models\Phones\PhoneContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PhoneContact extends Model
{
    use HasFactory;

    protected $table = 'dir_contacts';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public $incrementing = true;

    public $fillable = [
        'name'
    ];

    public function contract (){
        return $this->hasMany(PhoneContract::class, 'dir_contact_id');
    }
}
