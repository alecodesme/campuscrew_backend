<?php

namespace Database\Seeders;

use App\Models\Club;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClubsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Club::create([
            'name' => 'Club Deportivo A',
            'description' => 'Club de deportes con actividades diversas.',
            'university_id' => 4, // Asegúrate de que el university_id sea válido
            'email' => 'clubdeportivoa@universidad.com',
            'tags' => 'deportes, actividades, recreación',
            'is_active' => true,
        ]);

        Club::create([
            'name' => 'Club de Ciencia',
            'description' => 'Un club dedicado a la ciencia y la tecnología.',
            'university_id' => 4, // Asegúrate de que el university_id sea válido
            'email' => 'clubciencia@universidad.com',
            'tags' => 'ciencia, tecnología, investigación',
            'is_active' => true,
        ]);

        Club::create([
            'name' => 'Club de Música',
            'description' => 'Para amantes de la música y la composición.',
            'university_id' => 4, // Asegúrate de que el university_id sea válido
            'email' => 'clubmusica@universidad.com',
            'tags' => 'música, composición, arte',
            'is_active' => true,
        ]);

        Club::create([
            'name' => 'Club de Arte',
            'description' => 'Fomentando el arte y la creatividad.',
            'university_id' => 4, // Asegúrate de que el university_id sea válido
            'email' => 'clubarte@universidad.com',
            'tags' => 'arte, creatividad, pintura',
            'is_active' => true,
        ]);

        Club::create([
            'name' => 'Club de Lectura',
            'description' => 'Para los apasionados de la lectura y la literatura.',
            'university_id' => 4, // Asegúrate de que el university_id sea válido
            'email' => 'clublectura@universidad.com',
            'tags' => 'lectura, literatura, libros',
            'is_active' => true,
        ]);

        Club::create([
            'name' => 'Club de Programación',
            'description' => 'Un espacio para aprender y compartir sobre desarrollo de software.',
            'university_id' => 4, // Asegúrate de que el university_id sea válido
            'email' => 'clubprogramacion@universidad.com',
            'tags' => 'programación, tecnología, software',
            'is_active' => true,
        ]);
    }
}
