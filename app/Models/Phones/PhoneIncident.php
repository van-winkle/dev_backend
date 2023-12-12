<?php

namespace App\Models\Phones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PhoneIncident extends Model
{
    use HasFactory;
    protected $table = 'pho_phone_incidents';
    
    protected $fillable = [
        'file_name', 'file_name_original', 'file_mimetype', 'file_size', 'file_path', 'price',
        'porcentage', 'pho_phone_id', 'deleted_at'
    ];
}
