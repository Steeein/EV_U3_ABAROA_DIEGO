<?php

namespace Database\Seeders;

use App\Models\Proyecto;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProyectoSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario base para pruebas. La clave se cifra con bcrypt
        // (requisito de cifrado de la Unidad 2).
        $usuario = Usuario::firstOrCreate(
            ['correo' => 'admin@techsolutions.cl'],
            [
                'nombre' => 'Administrador Tech Solutions',
                'clave'  => Hash::make('desarrollo_software_1'),
            ]
        );

        $proyectos = [
            [
                'nombre'       => 'Migracion ERP Corporativo',
                'fecha_inicio' => '2026-03-10',
                'estado'       => 'En Proceso',
                'responsable'  => 'Camila Rojas',
                'monto'        => 18500000.00,
            ],
            [
                'nombre'       => 'Portal de Clientes v2',
                'fecha_inicio' => '2026-05-02',
                'estado'       => 'Pendiente',
                'responsable'  => 'Ignacio Fuentes',
                'monto'        => 7200000.50,
            ],
            [
                'nombre'       => 'Auditoria de Ciberseguridad',
                'fecha_inicio' => '2026-01-18',
                'estado'       => 'Finalizado',
                'responsable'  => 'Valentina Soto',
                'monto'        => 4300000.00,
            ],
        ];

        foreach ($proyectos as $proyecto) {
            Proyecto::create($proyecto + ['created_by' => $usuario->id]);
        }
    }
}
