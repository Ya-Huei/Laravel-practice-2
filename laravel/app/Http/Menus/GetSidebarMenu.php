<?php
namespace App\Http\Menus;

use App\MenuBuilder\MenuBuilder;
use Illuminate\Support\Facades\DB;
use App\Models\Menus;
use Spatie\Permission\Models\Role;
use App\MenuBuilder\RenderFromDatabaseData;

class GetSidebarMenu implements MenuInterface
{
    private $menu;

    public function __construct()
    {
        $this->mb = new MenuBuilder();
    }

    private function getMenuFromDB($rolesId)
    {
        $this->menu = Menus::join('menu_role', 'menus.id', '=', 'menu_role.menu_id')
            ->select('menus.*')
            ->whereIn('menu_role.role_id', $rolesId)
            ->groupBy('menus.id')
            ->orderBy('menus.sequence', 'asc')->get();
    }

    public function get($roles, $menuName = 'sidebar menu')
    {
        if ($menuName == 'top_menu') {
            return;
        }
        
        $rolesId = Role::select("id")->whereIn("name", $roles)->get();
        $this->getMenuFromDB($rolesId);
        $rfd = new RenderFromDatabaseData;
        return $rfd->render($this->menu);
    }

    public function getAll($menuId = 1)
    {
        $this->menu = Menus::select('menus.*')
            ->where('menus.menu_id', '=', $menuId)
            ->orderBy('menus.sequence', 'asc')->get();
        $rfd = new RenderFromDatabaseData;
        return $rfd->render($this->menu);
    }

    public function getAllExceptFormat()
    {
        $this->menu = Menus::select('menus.*')
            ->orderBy('menus.sequence', 'asc')->get();
        return $this->menu;
    }
}
