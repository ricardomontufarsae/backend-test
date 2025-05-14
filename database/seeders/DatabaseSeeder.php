<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */

    public function run()
    {
        $user = User::create([
            'name' => 'usuario',
            'email' => 'usuario@example.com',
            'password' => Hash::make('123456'),
        ]);

        $deporte = Categoria::create([
            'user_id' => $user->id,
            'nombre' => 'Deporte',
            'descripcion' => 'Artículos relacionados con actividad física y ejercicio.',
            'codigo' => 'DEP001',
        ]);

        Producto::create([
            'user_id' => $user->id,
            'categoria_id' => $deporte->id,
            'nombre' => 'Balón de fútbol',
            'descripcion' => 'Balón profesional tamaño 5, ideal para partidos.',
            'precio' => 29.99,
            'stock' => 15,
            'codigo' => 'DEP-PROD1',
            'fecha_ingreso' => now()->subDays(5),
        ]);

        Producto::create([
            'user_id' => $user->id,
            'categoria_id' => $deporte->id,
            'nombre' => 'Pesas de 10kg',
            'descripcion' => 'Par de mancuernas de goma con agarre antideslizante.',
            'precio' => 45.00,
            'stock' => 10,
            'codigo' => 'DEP-PROD2',
            'fecha_ingreso' => now()->subDays(12),
        ]);

        Producto::create([
            'user_id' => $user->id,
            'categoria_id' => $deporte->id,
            'nombre' => 'Cuerda para saltar',
            'descripcion' => 'Cuerda ajustable para entrenamiento cardiovascular.',
            'precio' => 9.99,
            'stock' => 25,
            'codigo' => 'DEP-PROD3',
            'fecha_ingreso' => now()->subDays(2),
        ]);

        $hogar = Categoria::create([
            'user_id' => $user->id,
            'nombre' => 'Hogar',
            'descripcion' => 'Productos para el mantenimiento y comodidad del hogar.',
            'codigo' => 'HOG001',
        ]);

        Producto::create([
            'user_id' => $user->id,
            'categoria_id' => $hogar->id,
            'nombre' => 'Lámpara de mesa',
            'descripcion' => 'Lámpara LED moderna con intensidad regulable.',
            'precio' => 34.50,
            'stock' => 8,
            'codigo' => 'HOG-PROD1',
            'fecha_ingreso' => now()->subDays(7),
        ]);

        Producto::create([
            'user_id' => $user->id,
            'categoria_id' => $hogar->id,
            'nombre' => 'Sábanas de algodón',
            'descripcion' => 'Juego de sábanas 100% algodón, tamaño queen.',
            'precio' => 59.90,
            'stock' => 12,
            'codigo' => 'HOG-PROD2',
            'fecha_ingreso' => now()->subDays(20),
        ]);

        Producto::create([
            'user_id' => $user->id,
            'categoria_id' => $hogar->id,
            'nombre' => 'Tostadora eléctrica',
            'descripcion' => 'Tostadora con capacidad para 4 rebanadas y función descongelar.',
            'precio' => 27.99,
            'stock' => 5,
            'codigo' => 'HOG-PROD3',
            'fecha_ingreso' => now()->subDays(1),
        ]);

        $maquillaje = Categoria::create([
            'user_id' => $user->id,
            'nombre' => 'Maquillaje',
            'descripcion' => 'Productos de belleza y cosmética.',
            'codigo' => 'MAQ001',
        ]);

        Producto::create([
            'user_id' => $user->id,
            'categoria_id' => $maquillaje->id,
            'nombre' => 'Base líquida',
            'descripcion' => 'Base de maquillaje de larga duración, tono natural.',
            'precio' => 22.00,
            'stock' => 18,
            'codigo' => 'MAQ-PROD1',
            'fecha_ingreso' => now()->subDays(3),
        ]);

        Producto::create([
            'user_id' => $user->id,
            'categoria_id' => $maquillaje->id,
            'nombre' => 'Paleta de sombras',
            'descripcion' => '12 colores intensos con acabado mate y brillante.',
            'precio' => 35.00,
            'stock' => 10,
            'codigo' => 'MAQ-PROD2',
            'fecha_ingreso' => now()->subDays(15),
        ]);

        Producto::create([
            'user_id' => $user->id,
            'categoria_id' => $maquillaje->id,
            'nombre' => 'Labial rojo intenso',
            'descripcion' => 'Color duradero con acabado satinado.',
            'precio' => 12.75,
            'stock' => 20,
            'codigo' => 'MAQ-PROD3',
            'fecha_ingreso' => now()->subDays(6),
        ]);
    }

}
