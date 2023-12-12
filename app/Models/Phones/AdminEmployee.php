<?php

namespace App\Models\Phones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminEmployee extends Model
{
    use HasFactory;
    protected $table = 'adm_employees';
    protected $fillable = [
        'name',
        'deleted_at'
    ];
}
