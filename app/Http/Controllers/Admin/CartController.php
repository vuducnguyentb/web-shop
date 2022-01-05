<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\CartService;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cart;

    public function __construct(CartService $cart)
    {
        $this->cart = $cart;
    }
    public function index(){
        $user = User::get();
//dd($user);
        return view('admin.carts.customer',[
            'title'=>'Danh sách đơn đặt hàng',
            'customers' => $this ->cart ->getCustomer(),
            'user' => $user
        ]);
    }

    public function show(Customer $customer){
        $user = User::get();
        $carts = $this->cart->getProductForCart($customer);
        return view('admin/carts/detail',[
            'title'=>'Chi tiết đơn hàng'. $customer->name,
            'customer' => $customer,
            'carts' => $carts,
            'user' => $user
        ]);
    }
}
