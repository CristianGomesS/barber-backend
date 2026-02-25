<?php

namespace Database\Seeders;

use App\Models\Ability;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleAbilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $roleAbilities = [
            'employee' => [
                'list_services', 'create_services', 'update_services', 'delete_services'    ,
                ],
            'customer' => [
                'list_services', 
                ],
            ];

        $adminRole = Role::where('slug', 'admin')->first();

        if($adminRole){
            $allAbility = Ability::pluck('id')->toArray();
            $adminRole->abilities()->sync($allAbility);
        }
        foreach ($roleAbilities as $role => $abilities) {
            $role = Role::where('slug', $role)->first();
            if($role){
                $abilityIds = Ability::whereIn('slug', $abilities)->pluck('id')->toArray();
                $role->abilities()->sync($abilityIds);
            }
        }
    }
}
