<?php

namespace Database\Seeders;

use App\Models\Ability;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AbilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $abilities = [
            // USERS
            [
                'name' => 'Listar Usuários', 'slug' => 'list_users'
            ],
            [
                'name' => 'Criar Usuários','slug' => 'create_users'
            ],
            [
                'name' => 'Atualizar Usuários','slug' => 'update_users'
            ],
            [
                'name' => 'Deletar Usuários','slug' => 'delete_users'
            ],
            // ROLES
            [
                'name' => 'Listar Perfis','slug' => 'list_roles'
            ],
            [
                'name' => 'Criar Perfis','slug' => 'create_roles'
            ],
            [
                'name' => 'Atualizar Perfis','slug' => 'update_roles'
            ],
            [
                'name' => 'Deletar Perfis','slug' => 'delete_roles'
            ],
            // SERVICES
            [
                'name' => 'Listar Serviços','slug' => 'list_services'
            ],
            [
                'name' => 'Criar Serviços','slug' => 'create_services'
            ],
            [
                'name' => 'Atualizar Serviços','slug' => 'update_services'
            ],
            [
                'name' => 'Deletar Serviços','slug' => 'delete_services'
            ],
            // ABILITY
            [
                'name' => 'Listar Permissoes','slug' => 'list_ability'
            ],
            [
                'name' => 'Criar Permissoes','slug' => 'create_ability'
            ],
            [
                'name' => 'Atualizar Permissoes','slug' => 'update_ability'
            ],
            [
                'name' => 'Deletar Permissoes','slug' => 'delete_ability'
            ],
            // FORGOT PASSWORD
            [
                'name' => 'Forçar Reset de Senha','slug' => 'force_forgot_password'
            ],
            // APPOINTMENTS
            [
                'name' => 'listar agendamentos','slug' => 'list_appointments'
            ],
            [
                'name' => 'criar agendamentos','slug' => 'create_appointments'
            ],
        ];

        foreach ($abilities as $ability) {
            Ability::updateOrCreate(
                ['slug' => $ability['slug']],
                ['name' => $ability['name']]
            );
        }
    }
}
