<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class Superadminseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
       public function run(): void
    {
        $data = [
            'name' => 'Cursor Soft',
            'username' => 'Cursorsoft4252',
            'email' => 'cursorsoft@gmail.com',
            'password' => Hash::make('cursorsoft'),
            'position' => 'superadmin',
            'role' => 'Super-admin',
            'status' => '1',
        ];

        $superadmin = User::create($data);
        $superadmin->assignRole('Super-admin');
    }
}
