<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class DeliveryBoySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $this->command->info("Creating Delivery Boy role");
        $delieryBoy = Role::create(['name' => 'delivery-boy']);
        $permissions = Permission::get();
        $delieryBoy->givePermissionTo([$permissions]);
    }
}
