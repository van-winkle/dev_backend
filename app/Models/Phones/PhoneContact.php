<?php

namespace App\Models\Phones;

use App\Models\Phones\PhoneContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhoneContact extends Model
{
    use HasFactory,
        SoftDeletes;

    protected $table = 'dir_contacts';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public $incrementing = true;

    public $fillable = [
        'name',
        'active',
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

    public function contract()
    {
        return $this->hasMany(PhoneContract::class, 'dir_contact_id');
    }
}
