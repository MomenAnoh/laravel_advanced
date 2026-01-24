<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $permissions = [
            'view users',
            'create users',
            'edit users',
            'delete users',
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'view permissions',
            'create permissions',
            'edit permissions',
            'delete permissions',
            'assign users to groups',
            'remove users from groups',
            'view products',
            'create products',
            'edit products',
            'delete products',
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            'view orders',
            'create orders',
            'edit orders',
            'delete orders',
        ];
       foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'api'
            ]);
        }
        $admin=Role::Create(['name'=> 'Admin' ,'guard_name' => 'api']);
        $user=Role::Create(['name'=> 'User','guard_name' => 'api']);

        $admin->givePermissionTo($permissions);
        $user->givePermissionTo(['view users']);

    }
}
