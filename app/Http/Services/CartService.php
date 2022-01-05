<?php


namespace App\Http\Services;



use App\Jobs\SendMail;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use function PHPUnit\Framework\isNull;

class CartService
{
    public function create($request){
        // số lượng và id sản phẩm
        $qty = (int)$request ->input('num-product');
        $product_id = (int)$request ->input('product_id');
        if ($qty<=0 || $product_id<=0){
            Session::flash('error','Số lượng hoặc sản phẩm không chính xác');
            return false;
        }
        $carts = Session::get('carts');
        if(is_null($carts)){
            Session::put('carts',[
                $product_id => $qty
            ]);
            return true;
        }
        //kiểm tra xem key có trong mảng không
        $exists = Arr::exists($carts, $product_id);
        if ($exists){
            $carts[$product_id] += $qty;
            Session::put('carts',$carts);
            return true;
        }
        $carts[$product_id] = $qty;
        Session::put('carts', $carts);
        return true;
    }

    public function getProduct(){
        $carts = Session::get('carts');
        if(is_null($carts)) return [];
        $productId = array_keys($carts);
//        dd($productId);
        $product = Product::select('id', 'name', 'price', 'price_sale', 'thumb')
            ->where('active', 1)
            ->whereIn('id', $productId)
            ->get();
//        dd($product);
        return $product;
    }

    public function update($request){

        Session::put('carts', $request->input('num_product'));
        return true;

    }

    public function remove($id){
        $carts = Session::get('carts');
        unset($carts[$id]);
        Session::put('carts', $carts);
        return true;
    }

    public function addCart($request){
        try {
            DB::beginTransaction();
            //lấy thông tin carts
            $carts = Session::get('carts');
            if(is_null($carts))
                return false;
            $customer = Customer::create([
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
                'email' => $request->input('email'),
                'content' => $request->input('content')
            ]);
        $this->infoProductCart($carts, $customer->id);

            DB::commit();
            Session::flash('success', 'Đặt Hàng Thành Công');
            #Queues khi thanh công thì gọi vào thằng sendmail
            SendMail::dispatch($request->input('email'))->delay(now()->addSeconds(2));

            Session::forget('carts');
        }catch (\Exception $err){
            DB::rollBack();
            Session::flash('error', 'Đặt Hàng Lỗi, Vui lòng thử lại sau');
            return false;
        }
        return true;
    }

    protected function infoProductCart($carts,$customer_id){
        //lây thông tin sp
        $productId = array_keys($carts);

        $products = Product::select('id', 'name', 'price', 'price_sale', 'thumb')
            ->where('active', 1)
            ->whereIn('id', $productId)
            ->get();
        $data = [];
        foreach ($products as $key=>$product){
            $data[] =[
                'customer_id' => $customer_id,
                'product_id' => $product->id,
                'pty'   => $carts[$product->id],
                'price' => $product->price_sale != 0 ? $product->price_sale : $product->price
            ];
        }
        return Cart::insert($data);

    }
    public function getCustomer(){
        return Customer::orderByDesc('id')->paginate(10);
    }
    public function getProductForCart($customer)
    {
        return $customer->carts()->with(['product' => function ($query) {
            $query->select('id', 'name', 'thumb');
        }])->get();
    }
}
