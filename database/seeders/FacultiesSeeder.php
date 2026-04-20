<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faculty;

class FacultiesSeeder extends Seeder
{
    public function run(): void
    {
        $faculties = [
            [
                'name' => 'Архитектонски факултет',
                'code' => 'ARH',
                'email' => 'arh@ukim.edu.mk',
                'phone' => '02/3116-367',
            ],
            [
                'name' => 'Градежен факултет',
                'code' => 'GF',
                'email' => 'gf@ukim.edu.mk',
                'phone' => '02/3116-066',
            ],
            [
                'name' => 'Економски факултет',
                'code' => 'ECCF',
                'email' => 'eccf@ukim.edu.mk',
                'phone' => '02/3286-800',
            ],
            [
                'name' => 'ФИНКИ',
                'code' => 'FINKI',
                'email' => 'contact@finki.ukim.mk',
                'phone' => '02/3070-377',
            ],
            [
                'name' => 'ФЕИТ',
                'code' => 'FEIT',
                'email' => 'contact@feit.ukim.edu.mk',
                'phone' => '02/3062-224',
            ],
            [
                'name' => 'Медицински факултет',
                'code' => 'MED',
                'email' => 'med@ukim.edu.mk',
                'phone' => '02/3115-311',
            ],
            [
                'name' => 'Правен факултет',
                'code' => 'PF',
                'email' => 'pf@ukim.edu.mk',
                'phone' => '02/3117-244',
            ],
            [
                'name' => 'Филозофски факултет',
                'code' => 'FZF',
                'email' => 'fzf@ukim.edu.mk',
                'phone' => '02/3116-520',
            ],
            [
                'name' => 'Филолошки факултет',
                'code' => 'FLF',
                'email' => 'flf@ukim.edu.mk',
                'phone' => '02/3240-400',
            ],
            [
                'name' => 'ПМФ',
                'code' => 'PMF',
                'email' => 'pmf@ukim.edu.mk',
                'phone' => '02/3249-000',
            ],
        ];

        foreach ($faculties as $faculty) {
            Faculty::create($faculty);
        }
    }
}
