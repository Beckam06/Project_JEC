<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Verificar si el usuario ya existe
        $existingUser = User::where('email', 'admin@jec.com')->first();
        
        if (!$existingUser) {
            User::create([
                'name' => 'Administrador',
                'email' => 'admin@jec',
                'password' => Hash::make('JEC123'),
            ]);
            $this->command->info('Usuario administrador creado exitosamente.');
        } else {
            $this->command->info('El usuario administrador ya existe.');
        }
    }
}