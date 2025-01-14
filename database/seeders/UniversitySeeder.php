<?php

namespace Database\Seeders;

use App\Models\University;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UniversitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        University::create([
            'name' => 'Universidad de la rioja',
            'address' => 'Rioja españa',
            'country' => 'Spain',
            'city' => 'Rioja',
            'province' => 'Madrid',
            'email' => 'rioja.uni@gmail.com',
            'cellphone' => 12345678,
            'user_id' => null,
            'domain' => '@rioja.edu.es'
        ]);
    }
}
