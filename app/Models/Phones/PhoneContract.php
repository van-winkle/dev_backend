<?php

namespace App\Models\Phones;

use App\Models\Phones\Phone;
use App\Models\Phones\PhonePlan;
use App\Models\Phones\PhoneContact;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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


    public function contact (){
        return $this->belongsTo(PhoneContact::class, 'dir_contact_id');
    }

    public function plans (){
        return $this->hasMany(PhonePlan::class, 'pho_phone_contract_id');
    }

    /*public function phones (){
        return $this->hasMany(Phone::class, 'id');
    }*/

}
