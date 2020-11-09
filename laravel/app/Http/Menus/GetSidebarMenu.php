<?php
/*
*   07.11.2019
*   MenusMenu.php
*/
namespace App\Http\Menus;

use App\MenuBuilder\MenuBuilder;
use Illuminate\Support\Facades\DB;
use App\Models\Menus;
use App\MenuBuilder\RenderFromDatabaseData;
use Illuminate\Support\Facades\Log;

class GetSidebarMenu implements MenuInterface{

    private $menu;

    public function __construct(){
        $this->mb = new MenuBuilder();
    }

    private function getMenuFromDB($roles){
        $this->menu = Menus::join('menu_role', 'menus.id', '=', 'menu_role.menus_id')
            ->select('menus.*')
            ->whereIn('menu_role.role_name', $roles)
            ->groupBy('menus.id')
            ->orderBy('menus.sequence', 'asc')->get();       
    }

    public function get($roles, $menuName = 'sidebar menu'){
        $roles = explode(',', $roles);
        $this->getMenuFromDB($roles);
        $rfd = new RenderFromDatabaseData;
        return $rfd->render($this->menu);
    }

    public function getAll( $menuId = 1 ){
        $this->menu = Menus::select('menus.*')
            ->where('menus.menu_id', '=', $menuId)
            ->orderBy('menus.sequence', 'asc')->get();  
        $rfd = new RenderFromDatabaseData;
        return $rfd->render($this->menu);
    }

    public function getAllExceptFormat(){
        $this->menu = Menus::select('menus.*')
            ->orderBy('menus.sequence', 'asc')->get();  
        return $this->menu;
    }
}