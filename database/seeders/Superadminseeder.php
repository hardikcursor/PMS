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
            'email' => 'cursorsoft@gmail.com',
            'password' => Hash::make('cursorsoft'),
        ];

        $superadmin = User::create($data);
        $superadmin->assignRole('Super-admin');
    }
}
