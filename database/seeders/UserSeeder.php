<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('slug', 'admin')->first();
        $salesManagerRole = Role::where('slug', 'sales-manager')->first();
        $salesRepRole = Role::where('slug', 'sales-rep')->first();
        
        $users = [
            [
                'name' => 'John Administrator',
                'email' => 'admin@crm.com',
                'password' => 'password', // Will be automatically hashed by model's 'hashed' cast
                'role_id' => $adminRole?->id,
                'phone' => '+1-555-0101',
                'position' => 'System Administrator',
                'is_active' => true,
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@crm.com',
                'password' => 'password', // Will be automatically hashed by model's 'hashed' cast
                'role_id' => $salesManagerRole?->id,
                'phone' => '+1-555-0102',
                'position' => 'Sales Manager',
                'is_active' => true,
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'michael.chen@crm.com',
                'password' => 'password', // Will be automatically hashed by model's 'hashed' cast
                'role_id' => $salesRepRole?->id,
                'phone' => '+1-555-0103',
                'position' => 'Sales Representative',
                'is_active' => true,
            ],
            [
                'name' => 'Emily Davis',
                'email' => 'emily.davis@crm.com',
                'password' => 'password', // Will be automatically hashed by model's 'hashed' cast
                'role_id' => $salesRepRole?->id,
                'phone' => '+1-555-0104',
                'position' => 'Sales Representative',
                'is_active' => true,
            ],
            [
                'name' => 'David Wilson',
                'email' => 'david.wilson@crm.com',
                'password' => 'password', // Will be automatically hashed by model's 'hashed' cast
                'role_id' => $salesRepRole?->id,
                'phone' => '+1-555-0105',
                'position' => 'Sales Representative',
                'is_active' => true,
            ],
        ];
        
        foreach ($users as $user) {
            User::create($user);
        }
    }
}
