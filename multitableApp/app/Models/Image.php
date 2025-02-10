<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Model
{
    use HasFactory;

    public $timestamps = false; // Deshabilita los timestamps

    protected $fillable = ['sale_id', 'route', 'is_main'];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
