<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ecom_customization extends Model
{
    use HasFactory;

    protected $fillable = [
        'div_name',
        'div_value',
        'font_color',
        'font_type',
        'font_size',
        'bg_color'
    ];
}
