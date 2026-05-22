<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Administrador del sistema
        User::factory()->create([
            'name'                 => 'Administrador',
            'email'                => 'admin@pjudicial.gob.ar',
            'password'             => 'Admin1234!',
            'activo'               => true,
            'must_change_password' => false,
        ]);

        // Solicitante (magistrado)
        User::factory()->create([
            'name'                 => 'Juan Pérez',
            'email'                => 'jperez@pjudicial.gob.ar',
            'password'             => 'Admin1234!',
            'activo'               => true,
            'must_change_password' => true,
        ]);
    }
}
