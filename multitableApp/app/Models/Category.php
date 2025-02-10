<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    public $timestamps = false; // Deshabilita los timestamps

    protected $fillable = ['name'];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
