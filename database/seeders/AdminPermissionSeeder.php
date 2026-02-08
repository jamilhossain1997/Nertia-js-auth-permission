<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class AdminPermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'permissions.index',
            'permissions.store',
            'permissions.destroy',
            'users.index',
            'users.store',
            'users.updateRole',
            'users.assignRole',
            'users.create',
            'roles.index',
            'roles.store',
            'roles.update',
            'roles.destroy',
            'roles.edit',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        $adminRole->syncPermissions(Permission::all());

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password123'), 
            ]
        );

        if (!$adminUser->hasRole('admin')) {
            $adminUser->assignRole($adminRole);
        }

        $this->command->info('✅ Admin user, role, and permissions seeded successfully!');
    }
}
