<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class CreateRolesAndPermissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-roles-and-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create roles and permissions for the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        //        Permission::create(['name' => 'edit articles']);
        //        Permission::create(['name' => 'delete articles']);
        //        Permission::create(['name' => 'publish articles']);
        //        Permission::create(['name' => 'create users']);
        //        Permission::create(['name' => 'manage settings']);

        // Create Roles and assign permissions
        //        $adminRole = Role::create(['name' => 'admin']);
        //        $adminRole->givePermissionTo(Permission::all()); // Assign all permissions
        //
        //        $editorRole = Role::create(['name' => 'editor']);
        //        $editorRole->givePermissionTo(['edit articles', 'publish articles']);

        Role::create(['name' => 'user']);
        Role::create(['name' => 'admin']);
    }
}
