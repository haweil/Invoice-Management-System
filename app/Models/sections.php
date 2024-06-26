<?php

namespace App\Models;

use App\Models\products;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class sections extends Model
{
    use HasFactory;

    protected $table = 'sections';

    protected $fillable = ['section_name','description','Created_by'];

    public function products()
    {
        return $this->hasMany(products::class, 'section_id');
    }
}