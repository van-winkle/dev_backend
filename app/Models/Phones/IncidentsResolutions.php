<?php

namespace App\Models\Phones;

use App\Models\Phones\PhoneIncident;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncidentsResolutions extends Model
{
    use HasFactory,
        SoftDeletes;

    protected $table = 'pho_phone_resolutions';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public $incrementing = true;

    public $fillable = [
        'title',
        'reply',
        'date_response',
        'pho_phone_incident_id',
        'adm_employee_id'
    ];


    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $cast = [];

    protected static $recordEvents = [
        'created',
        'updated',
        'deleted'
    ];

    public function incident()
    {
        return $this->belongsTo(PhoneIncident::class, 'pho_phone_incident_id');
    }

    public function employee()
    {
        return $this->belongsTo(AdminEmployee::class, 'adm_employee_id');
    }

    public function attaches()
    {
        return $this->hasMany(IncidentsResolutionsAttaches::class, 'pho_phone_resolution_id');
    }
}
