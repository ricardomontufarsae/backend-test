<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nombre',
        'descripcion',
        'codigo'
    ];

    public function productos(){
        return $this->hasMany(Producto::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
