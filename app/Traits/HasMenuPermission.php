<?php


namespace App\Traits;

use App\Models\Konfigurasi\Menu;
use App\Models\Shield\Permission;

trait HasMenuPermission
{
    public function attachMenupermission(Menu $menu, array | null $permissions, array | null $roles)
    {
        if (!is_array($permissions)) {
            $permissions = ['menu', 'create', 'read', 'update', 'delete'];
        }

        foreach ($permissions as $item) {
            $permission = Permission::create(['name' =>  $item . " $menu->url"]);
            $permission->menus()->attach($menu);
            if ($roles) {
                $permission->assignRole($roles);
            }
        }
    }
}
