<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Factura extends Eloquent
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $fillable = [
        'emisor',
        'receptor',
        'numero_factura',
        'fecha_factura',
        'monto_factura',
        'user_id',
        'productos'
    ];

    protected $casts = [
        'fecha_factura' => 'datetime',
        'productos' => 'array'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

}
