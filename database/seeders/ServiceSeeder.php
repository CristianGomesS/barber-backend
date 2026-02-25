<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Corte Degradê',
                'duration_minutes' => 45
            ],
            [
                'name' => 'Barba Terapia',
                'duration_minutes' => 30
            ],
            [
                'name' => 'Corte & Barba',
                'duration_minutes' => 75
            ],
            [
                'name' => 'Sobrancelha',
                'duration_minutes' => 15
            ],
            [
                'name' => 'Limpeza de Pele',
                'duration_minutes' => 40
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['slug' => Str::slug($service['name'],'_')],
                [
                    'name' => $service['name'],
                    'duration_minutes' => $service['duration_minutes']
                ]
            );
        }
    }
}
