<?php

namespace App\Models\Phones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhoneAssignment extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'pho_phone_assignments';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public $incrementing = true;

    public $fillable = [
        'adm_employee_id',
        'pho_phone_id',
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

    public function manager()
    {
        return $this->belongsTo(AdminEmployee::class, 'adm_employee_id');
    }

    public function phone ()
    {
        return $this->belongsTo(Phone::class, 'pho_phone_id');
    }

}
