<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Menu\CreateFormRequest;
use App\Http\Services\Menu\MenuService;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class MenuController extends Controller
{
    protected $menuService;
    //Khởi tạo thắng menuservice
    public function __construct(MenuService $menuService)
    {
    $this->menuService = $menuService;
    }

    public function create(){
        $user = User::get();
        return view('admin.menu.add',[
           'title' => 'Thêm danh mục mới',
            'menus' =>$this->menuService->getParent(),
            'user'=>$user
        ]);
    }

    public function store(CreateFormRequest $request){
        //gọi đến pthuc create trong thang MenuService
        $result = $this ->menuService->create($request);
//        dd($result);
        return redirect()->back();
    }

    // update danh mục
    public function update(Menu $menu, CreateFormRequest $request){
    //goi đến update trong MenuService
        $this->menuService->update($request, $menu);
        return redirect('/admin/menus/list')
;    }

    //danh dách danh mục
    public function index(){
        $user = User::get();

        return view('admin.menu.list',[
            'title'=>'Danh sách danh mục mới nhất',
            'menus'=>$this->menuService->getAll(),
            'user'=>$user
        ]);
    }


    public function destroy(Request $request): JsonResponse
    {
        $result = $this->menuService->destroy($request);
        if ($result) {
            return response()->json([
                'error' => false,
                'message' => 'Xóa thành công danh mục'
            ]);
        }

        return response()->json([
            'error' => true
        ]);
    }

    public function show(Menu $menu){
        $user = User::get();

        $menus=$this->menuService->getParent();
//        dd($menus[0]);
        return view('admin.menu.edit',[
           'title'=> 'Chỉnh sửa danh mục'.$menu->name,
            'menu'=>$menu,
            'menus'=>$menus,
            'user'=>$user

        ]);
    }



}
