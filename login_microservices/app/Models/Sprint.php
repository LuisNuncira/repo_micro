<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sprint extends Model
{
protected $fillable = ['nombre', 'fecha_inicio', 'fecha_fin'];

public function retroItems()
{
    return $this->hasMany(RetroItem::class);
}
}
