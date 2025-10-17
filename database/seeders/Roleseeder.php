<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class Roleseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::create(['name' => 'Super-admin']);
        $role = Role::create(['name' => 'Company-admin']);
        $role = Role::create(['name' => 'User']);
        $role = Role::create(['name' => 'POS-user']);

    }
}
