<?php


namespace App\Http\Views\Composers;
use Illuminate\View\View;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartComposer
{
    protected $users;
    public function __construct()
    {

    }


    public function compose(View $view)
    {
        $carts = Session::get('carts');

        if(is_null($carts)) return [];
        $productId = array_keys($carts);
//        dd($productId);
        $products = Product::select('id', 'name', 'price', 'price_sale', 'thumb')
            ->where('active', 1)
            ->whereIn('id', $productId)
            ->get();
        $view->with('products',$products);
    }
}
