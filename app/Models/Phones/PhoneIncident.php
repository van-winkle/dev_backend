<?php

namespace App\Models\Phones;

use App\Models\Phones\Phone;
use App\Models\Phones\AdminEmployee;
use Illuminate\Database\Eloquent\Model;
use App\Models\Phones\IncidentsAttaches;
use App\Models\Phones\IncidentsCategory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PhoneIncident extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'pho_phone_incidents';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public $incrementing = true;

    public $fillable = [
        'description',
        'paymentDifference',
        'percentage',
        'date_incident',
        'state',
        'adm_employee_id',
        'pho_phone_id',
        'pho_phone_incident_category_id'
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

    public function attaches ()
    {
        return $this->hasMany(IncidentsAttaches::class,'pho_phone_incident_id');
    }
    public function phone ()
    {
        return $this->belongsTo(Phone::class,'pho_phone_id')->withTrashed();
    }
    public function incidentCat (){
        return $this->belongsTo(IncidentsCategory::class, 'pho_phone_incident_category_id');
    }
    public function employee (){
        return $this->belongsTo(AdminEmployee::class, 'adm_employee_id');
    }
    public function resolutions (){
        return $this->hasMany(IncidentsResolutions::class, 'pho_phone_incident_id');
    }

}
