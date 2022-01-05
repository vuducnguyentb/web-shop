<?php

namespace App\Http\Controllers;

use App\Http\Services\Product\ProductService;
use App\Http\Services\Slider\SliderService;
use Illuminate\Http\Request;

class MainController extends Controller
{
    protected $sliders;
    protected $products;

    public function __construct(SliderService $sliders, ProductService $products)
    {
        $this->sliders = $sliders;
        $this->products = $products;
    }

    public function index(){
        return view('home',[
            'title'=>'Shop bán hàng',
            'sliders'=>$this->sliders->show(),
                'products'=>$this->products->get()
                ]
        );
    }

    public function loadProduct(Request $request){
        $page = $request ->input('page',0);
        $result = $this ->products->get($page);

        if(count($result) != 0){
            $html = view('products.list',['products'=>$result])->render();
            return response()->json(['html'=>$html]);
        }
        return response()->json(['html' => '' ]);
    }
}
