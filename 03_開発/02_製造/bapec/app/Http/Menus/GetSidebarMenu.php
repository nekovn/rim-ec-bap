<?php
/*
*   MenusMenu.php
*   表示するメニューデータを取得する
*/
namespace App\Http\Menus;

use App\Helpers\Util\SystemHelper;
use App\MenuBuilder\MenuBuilder;
use App\Models\Menu;
use App\MenuBuilder\RenderFromDatabaseData;
use App\Enums\FlagDefine;

class GetSidebarMenu implements MenuInterface
{
    private $mb; //menu builder
    private $menu;

    public function __construct()
    {
        $this->mb = new MenuBuilder();
    }

    /**
     * メニューを取得
     */
    private function getMenuFromDB(array $programCds)
    {
        $query = null;

        //権限Mを使用するか
        if (SystemHelper::getAppSettingValue('use-auth-control')) {
            $subQuery = Menu::distinct()->select('title_id')
                ->whereIn('program_cd', $programCds)
                ->where('slug', '=', 'link')
                ->whereNotNull('title_id');
            $query1 = Menu::select('menus.*')
                ->JoinSub($subQuery, 'm2', 'menus.id', 'm2.title_id') 
                ->where('menus.slug', '=',  'title');

            $subQuery2 = Menu::distinct()->select('parent_id')
                ->whereIn('program_cd', $programCds)
                ->where('slug', '=', 'link')
                ->whereNotNull('parent_id');
            $query2 = Menu::select('menus.*')
                ->JoinSub($subQuery2, 'm2', 'menus.id', 'm2.parent_id')
                ->whereIn('menus.slug', ['dropdown','title-dropdown']);


            $query3 = Menu::select('menus.*')
                ->whereIn('menus.program_cd', $programCds)
                ->where('menus.slug', '=',  'link');

            $query = $query1->unionAll($query2)->unionAll($query3);

        } else {
            $query = Menu::select('menus.*');
        }
        $this->menu = $query->orderBy('sequence', 'asc')->get();
    }

    public function get($menuIds=[])
    {
        $this->getMenuFromDB($menuIds);
        $rfd = new RenderFromDatabaseData;
        return $rfd->render($this->menu);
    }

    public function getAll($menuId)
    {
        $this->menu = Menu::select('menus.*')
            ->where('menus.program_cd', '=', $menuId)
            ->orderBy('menus.sequence', 'asc')->get();
        $rfd = new RenderFromDatabaseData;
        return $rfd->render($this->menu);
    }
}
