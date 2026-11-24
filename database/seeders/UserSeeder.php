<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Magdiel',
            'second_name' => 'Mendoza',
            'email' => 'mag1@gmail.com',
            'cargo' => 'Administrador',
            'direccion' => 'Calle 123',
            'password' => Hash::make('1234'),
        ]);
    }
}
