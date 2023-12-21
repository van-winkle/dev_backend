<?php

namespace App\Models\Phones;

use Illuminate\Database\Eloquent\Model;
use App\Models\Phones\IncidentsAttaches;
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
        'paymentDifference',
        'percentage',
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
        return $this->belongsTo(Phone::class,'pho_phone_id');
    }
    public function incidentCat (){
        return $this->belongsTo(IncidentsCategory::class, 'pho_phone_incident_category_id');
    }


}
