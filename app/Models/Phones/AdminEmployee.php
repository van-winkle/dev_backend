<?php

namespace App\Models\Phones;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminEmployee extends Model
{
    use HasFactory;
    protected $table = 'adm_employees';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public $incrementing = true;

    protected $fillable = [
        'name',
    ];


    public function employees ()
    {
        return $this->hasMany(Phone::class,'id');
    }

}
