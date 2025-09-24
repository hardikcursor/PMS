<?php

namespace Database\Seeders;
use App\Models\Slot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           for ($i = 1; $i <= 24; $i++) {
            Slot::create([
                'slot_hours' => $i
            ]);
        }
    }
}
