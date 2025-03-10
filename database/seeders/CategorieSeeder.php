<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Categorie::query()->create([
            'name' => 'PHONE',
        ]);

        Categorie::query()->create([
            'name' => 'laptop',
        ]);

        Categorie::query()->create([
            'name' => 'PC'
        ]);
    }
}
