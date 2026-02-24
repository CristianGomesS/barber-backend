<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $users = [
            [
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('123456'),
                'role_id' => 1
            ],
            [
                'name' => 'employee',
                'email' => 'employee@employee.com',
                'password' => bcrypt('1233456'),
                'role_id' => 2
            ],
            [
                'name' => 'customer',
                'email' => 'customer@customer.com',
                'password' => bcrypt('123456'),
                'role_id' => 3
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
