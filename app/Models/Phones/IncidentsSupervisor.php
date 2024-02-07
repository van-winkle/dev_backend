<?php

namespace App\Models\Phones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncidentsSupervisor extends Model
{
    use HasFactory,
        SoftDeletes;

    protected $table = 'pho_phone_incident_supervisor';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public $incrementing = true;

    public $fillable = [
        'adm_employee_id'
    ];
}
