<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Categoria extends Eloquent
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $fillable = [
        'user_id',
        'nombre',
        'descripcion',
        'codigo'
    ];

    public function producto(){
        return $this->hasMany(Producto::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
