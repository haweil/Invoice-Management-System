<?php

namespace App\Models;

use App\Models\sections;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class products extends Model
{
    use HasFactory;

    protected $fillable = [
        'Product_name',
        'description',
        'section_id'
    ];

    protected $table = 'products';

    public function section()
    {
        return $this->belongsTo(sections::class, 'section_id');
    }
}