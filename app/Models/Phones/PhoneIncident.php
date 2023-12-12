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

    protected $fillable = [
        'file_name',
        'file_name_original',
        'file_mimetype',
        'file_size',
        'file_path',
        'percentage',
        'pho_phone_id'];

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

    public function incident ()
    {
        return $this->hasMany(PhoneIncident::class,'pho_phone_id');
    }









}
