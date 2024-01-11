<?php

namespace App\Models\Phones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpParser\Node\Expr\FuncCall;

class TypePhone extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pho_phone_type_phones';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public $incrementing = true;

    public $fillable = [
        'name',
        'active'
    ];

    public $hidden =[
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $cast = [];

    protected static $recordEvents = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function planes (){
        return $this->hasMany(PhonePlan::class, 'pho_phone_type_phone_id');
    }
}
