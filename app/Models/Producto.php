<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Producto extends Eloquent
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $fillable = [
        'user_id',
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'codigo',
        'fecha_ingreso',
        'categoria_id'
    ];

    protected $casts = [
        'fecha_ingreso' => 'datetime',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function categoria(){
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

}
