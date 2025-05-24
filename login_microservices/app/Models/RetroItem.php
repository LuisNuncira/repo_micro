<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RetroItem extends Model
{
   protected $fillable = ['sprint_id', 'categoria', 'descripcion', 'cumplida', 'fecha_revision'];

public function sprint()
{
    return $this->belongsTo(Sprint::class);
}
}
