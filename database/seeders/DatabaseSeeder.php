<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@pjudicial.gob.ar'],
            [
                'name'                 => 'Administrador',
                'password'             => Hash::make('Admin1234!'),
                'activo'               => true,
                'must_change_password' => false,
            ]
        );

        User::firstOrCreate(
            ['email' => 'jperez@pjudicial.gob.ar'],
            [
                'name'                 => 'Juan Pérez',
                'password'             => Hash::make('Admin1234!'),
                'activo'               => true,
                'must_change_password' => true,
            ]
        );
    }
}
