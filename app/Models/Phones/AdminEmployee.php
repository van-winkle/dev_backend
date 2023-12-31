<?php

namespace App\Models\Phones;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminEmployee extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'adm_employees';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public $incrementing = true;

    public $fillable = [
        'name',
        'active',
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [];

    protected static $recordEvents = [
        'created',
        'updated',
        'deleted',
    ];

    public function employees ()
    {
        return $this->hasMany(Phone::class,'adm_employee_id');
    }

}
