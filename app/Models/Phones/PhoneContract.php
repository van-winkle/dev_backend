<?php

namespace App\Models\Phones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhoneContract extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'pho_phone_contracts';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;

    public $fillable = [
    'code',
    'start_date',
    'expiry_date',
    'active',
    'dir_contact_id'];


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


    public function contacts (){
        return $this->belongsTo(PhoneContact::class, 'id');
    }
}
