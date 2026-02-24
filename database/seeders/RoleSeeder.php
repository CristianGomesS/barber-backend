<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
       $roles = [
            ['name' => 'admin'],
            ['name' => 'employee'],
            ['name' => 'customer'],
        ];

        foreach ($roles as $role) {
           Role::updateOrCreate(
                ['slug' => Str::slug($role['name'])], // Busca pelo slug (ex: administrador)
                ['name' => $role['name']]             // Atualiza o nome real
            );
        }
    }
}