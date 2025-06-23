<?php
namespace Database\Seeders;

use App\Enums\AdminRole;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RoleHasPermission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Route;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = $this->getAdminRoutes();

        foreach ($permissions as $permissionName) {

            $title = str_replace('admin.', '', $permissionName);
            $title = ucwords(str_replace('.', ' > ', $title));

            $permission = Permission::query()->firstOrCreate([
                'name'  => $permissionName,
                'title' => $title,
            ]);

            $superAdmin = Role::where('name', AdminRole::SUPER_ADMIN->value)->first();
            if ($superAdmin) {
                RoleHasPermission::query()->firstOrCreate(
                    [
                        'role_id'       => $superAdmin->id,
                        'permission_id' => $permission->id,
                    ]
                );
            }
        }

    }
    private function getAdminRoutes(): array
    {
        $routes = [];
        foreach (Route::getRoutes() as $route) {
            $routeName = $route->getName();
            if ($routeName != null && str_starts_with($routeName, 'admin.')) {
                $routes[] = $routeName;
            }
        }

        sort($routes);

        return $routes;
    }
}
