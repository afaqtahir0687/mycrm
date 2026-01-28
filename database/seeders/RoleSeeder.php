<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Administrator', 'slug' => 'admin', 'description' => 'Full system access', 'is_active' => true],
            ['name' => 'Sales Manager', 'slug' => 'sales-manager', 'description' => 'Manages sales team and deals', 'is_active' => true],
            ['name' => 'Sales Representative', 'slug' => 'sales-rep', 'description' => 'Handles leads and opportunities', 'is_active' => true],
            ['name' => 'Support Manager', 'slug' => 'support-manager', 'description' => 'Manages support tickets', 'is_active' => true],
            ['name' => 'Support Agent', 'slug' => 'support-agent', 'description' => 'Handles customer support', 'is_active' => true],
        ];
        
        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
