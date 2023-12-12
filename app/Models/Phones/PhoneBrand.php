<?php

namespace App\Models\Phones;

use App\Models\Phones\PhoneModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PhoneBrand extends Model
{
    use HasFactory;
    // name the table
    protected $table = 'pho_phone_brands';
    // Table Fields
    protected $fillable = [
        'name', 'active', 'deleted_at',
    ];

    public function brands()
    {
        return $this->hasMany(PhoneModel::class, 'id');
    }
}
<?php

namespace App\Models\Phones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhoneBrand extends Model
{
    use HasFactory, SoftDeletes;
    // name the table
    protected $table = 'pho_phone_brands';

    protected $primaryKey= "id";

    protected $KeyType = "int";

    public $incrementing = true;
    // Table Fields
    protected $fillable = [
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

    public function Brand ()
    {
        return $this->hasMany(PhoneBrand::class,'pho_phone_model_id');
    }


}
