<?php

namespace App\Models\Phones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncidentsAttaches extends Model
{
    use HasFactory,
        SoftDeletes;

    protected $table = 'pho_phone_incident_attaches';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public $incrementing = true;

    public $fillable = [
        'file_name_original',
        'name',
        'file_size',
        'file_extension',
        'file_mimetype',
        'file_location',
        'pho_phone_incident_id'
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

    public function incident()
    {
        return $this->belongsTo(PhoneIncident::class, 'pho_phone_incident_id');
    }
}
