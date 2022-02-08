<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Dashboard Permission
        Permission::create(['name' => 'dashboard']);

        // User Permissions
        Permission::create(['name' => 'users.index']);
        Permission::create(['name' => 'users.create']);
        Permission::create(['name' => 'users.show']);
        Permission::create(['name' => 'users.edit']);
        Permission::create(['name' => 'users.delete']);
        Permission::create(['name' => 'users.active']);
        Permission::create(['name' => 'users.deactive']);

        // Customer Permissions
        Permission::create(['name' => 'customers.index']);
        Permission::create(['name' => 'customers.create']);
        Permission::create(['name' => 'customers.show']);
        Permission::create(['name' => 'customers.edit']);
        Permission::create(['name' => 'customers.delete']);
        Permission::create(['name' => 'customers.active']);
        Permission::create(['name' => 'customers.deactive']);

        // Company Permissions
        Permission::create(['name' => 'companies.index']);
        Permission::create(['name' => 'companies.create']);
        Permission::create(['name' => 'companies.show']);
        Permission::create(['name' => 'companies.edit']);
        Permission::create(['name' => 'companies.delete']);
        Permission::create(['name' => 'companies.active']);
        Permission::create(['name' => 'companies.deactive']);

    	$superadmin=Role::create(['name' => 'Super Admin']);
        $superadmin->givePermissionTo(Permission::all());
        
        $admin=Role::create(['name' => 'Administrador']);
    	$admin->givePermissionTo(Permission::all());

        $customer=Role::create(['name' => 'Cliente']);

    	$user=User::find(1);
    	$user->assignRole('Super Admin');

        $customers=User::where('id', '!=', '1')->get();
        foreach ($customers as $customer) {
            $customer->assignRole('Cliente');
        }
    }
}
