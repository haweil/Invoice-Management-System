<?php

namespace App\Models;

use App\Models\invoices;
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

    public function invoices()
    {
        return $this->hasMany(invoices::class, 'section_id');
    }
}
