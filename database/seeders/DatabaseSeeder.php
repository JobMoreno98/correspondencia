<?php

namespace Database\Seeders;


use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $s_admin = User::create([
            'name' => 'Test Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
        ]);
        // Recursos Filament (nombres de modelos en minúsculas/pluralizados)
        $resources = [
            'oficios',
            'destinatario',
            'users',
            'roles',
        ];

        // Acciones que genera Shield
        $actions = [
            'view_any',
            'view',
            'create',
            'update',
            'delete',
            'restore',
            'force_delete',
        ];

        $permissions = [];

        // Crear permisos con el formato que usa Filament Shield
        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                $permissions[] = "{$action}_{$resource}";
            }
        }

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        Role::create(['name' => 'Super Admin', 'guard_name' => 'web']);
        // Crear roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $editor = Role::firstOrCreate(['name' => 'editor']);
        $revisor = Role::firstOrCreate(['name' => 'revisor']);
        // Asignar todos los permisos al admin
        $admin->syncPermissions($permissions);

        // Permisos básicos para el editor (crear, editar, ver)
        $editor->syncPermissions([
            'view_any_oficios',
            'view_oficios',
            'create_oficios',
            'update_oficios',
            'view_any_destinatario',
            'view_destinatario',
            'create_destinatario',
            'update_destinatario',
        ]);

        // Revisor solo puede ver
        $revisor->syncPermissions([
            'view_any_oficios',
            'view_oficios',
            'view_any_destinatario',
            'view_destinatario',
        ]);

        $s_admin->assignRole('Super Admin');
        $s_admin->update();

        User::create([
            'name' => 'Test Editor',
            'email' => 'editor@gmail.com',
            'password' => Hash::make('password'),
        ])->assignRole('editor')->update();

        User::create([
            'name' => 'Test Revisor',
            'email' => 'revisor@gmail.com',
            'password' => Hash::make('password'),
        ])->assignRole('revisor')->update();
    }
}
