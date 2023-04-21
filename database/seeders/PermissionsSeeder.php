<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
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
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info("Creating Roles");

        $developerAdmin = Role::create(['name' => 'developer-admin']);
        $superAdmin = Role::create(['name' => 'super-admin']);
        $storeAdmin = Role::create(['name' => 'store-admin']);
        $marketingManager = Role::create(['name' => 'marketing-manager']);
        $collectionManager = Role::create(['name' => 'collection-manager']);
        $branchManager = Role::create(['name' => 'branch-manager']);
        $marketingExecutive = Role::create(['name' => 'marketing-executive']);
        $collectionExectuive = Role::create(['name' => 'collection-executive']);
        $officeAdmin = Role::create(['name' => 'office-administrator']);
        $customer = Role::create(['name' => 'customer']);

        $this->command->info("Creating Permissions");
        $commonPermissions = [
            'can_delete',
            'can_create',
            'can_view',
            'can_edit',
            'can_show',
        ];
        foreach ($commonPermissions as $commonPermission) {
            Permission::create(['name' => $commonPermission]);
        }
        $this->command->info("Creating Developer Admin User");
        $userDA = User::create([
            'name' => 'Developer Admin',
            'username' => 'developer',
            'email' => 'developer@app.com',
            'password' => Hash::make('123'),
            'mobile' => '9876543210',
            'is_admin' => 1,
            'status' => 1,
        ]);
        $userDA->assignRole($developerAdmin);
        $developerAdmin->givePermissionTo([$commonPermissions]);      
        $this->command->info("Creating Super Admin User");
        $userSA = User::create([
            'name' => 'Super Admin',
            'username' => 'super_admin',
            'email' => '9876543211',
            'password' => Hash::make('123'),
            'mobile' => '9876543211',
            'is_admin' => 1,
            'status' => 1,
        ]);
        $userSA->assignRole($superAdmin);
        $superAdmin->givePermissionTo([$commonPermissions]);
        $this->command->info("Creating Warehouse Admin");
        $userSA = User::create([
            'name' => 'Warehouse Admin',
            'username' => 'warehouse_admin',
            'email' => '9876543212',
            'password' => Hash::make('123'),
            'mobile' => '9876543212',
            'is_admin' => 1,
            'status' => 1,
        ]);
        $userSA->assignRole($storeAdmin);
        $storeAdmin->givePermissionTo( 'can_view');
    }

}
