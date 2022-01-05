<?php

namespace App\Http\Controllers;

use App\Http\Services\Product\ProductService;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    protected $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index($id,$slug){
        $product = $this->productService->show($id);
        $productsMore = $this->productService->more($id);
        return view('products.content',[
            'title'=>$product->name,
            'product'=>$product,
            'products'=>$productsMore
        ]);
    }

    public function searchProduct(Request $request){
//       $productall = $this->productService->get();
        $key = $request->get('search');
//    dd($request->get('a'));
        $products =  Product::where('name','like','%'.$key.'%')->get();
        return view('products.search',[
            'title'=>'Tìm kiếm sản phẩm',
            'products'=>$products
        ]);
//        dd(count($products));
    }
}
