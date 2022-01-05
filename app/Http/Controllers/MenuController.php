<?php

namespace App\Http\Controllers;

use App\Http\Services\Menu\MenuService;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    protected $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService= $menuService;
    }

    public function index(Request $request,$id,$slug ){
        $menu = $this->menuService->getId($id);
        $products = $this->menuService->getProduct($menu,$request);
//        dd($products[0]->name);
        return view('menu',[
            'title'=>$menu->name,
            'products'=>$products,
            'menu'=>$menu
        ]);
    }
}
