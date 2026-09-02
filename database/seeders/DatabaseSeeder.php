<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Carga los datos de prueba de la aplicacion.
     */
    public function run(): void
    {
        $this->call([
            ProyectoSeeder::class,
        ]);
    }
}
