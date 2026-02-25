<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $rolesList = Role::all()->pluck('id', 'slug')->toArray();
        $users = [
            [
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'password' => '123456',
                'role_id' => $rolesList['admin']
            ],
            [
                'name' => 'employee',
                'email' => 'employee@employee.com',
                'password' =>'123456',
                'role_id' => $rolesList['employee']
            ],
            [
                'name' => 'customer',
                'email' => 'customer@customer.com',
                'password' => '123456',
                'role_id' => $rolesList['customer']
            ]
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']], // Se encontrar um User com este email...
                $user                      // ...ele mantém ou atualiza. Caso contrário, cria.
            );
        }
    }
}
