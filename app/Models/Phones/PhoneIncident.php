<?php

namespace App\Models\Phones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhoneIncident extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'pho_phone_incidents';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public $incrementing = true;

    public $fillable = [
        'percentage',
        'active',
        'pho_phone_id',
        'pho_phone_incident_category_id'];

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


}
