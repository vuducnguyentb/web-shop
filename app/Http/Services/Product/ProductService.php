<?php


namespace App\Http\Services\Product;


use App\Models\Menu;
use App\Models\Product;

class ProductService
{
    //lấy ra danh sách menu active
    public function getMenu(){
        return Menu::where('active', 1)->get();
    }

    //kiểm tra có giá ko và giá giảm phả nhỏ hơn giá gốc
    protected function isValidPrice($request)
    {
        if ($request->input('price') != 0 && $request->input('price_sale') != 0
            && $request->input('price_sale') >= $request->input('price')
        ) {
            Session::flash('error', 'Giá giảm phải nhỏ hơn giá gốc');
            return false;
        }

        if ($request->input('price_sale') != 0 && (int)$request->input('price') == 0) {
            Session::flash('error', 'Vui lòng nhập giá gốc');
            return false;
        }

        return  true;
    }
    const LIMIT = 16;
    public function get($page = null)
    {
        $products= Product::select('id','name','price','price_sale','thumb')
            ->orderByDesc('id')
            ->when($page != null,function ($query) use ($page){
                $query->offset($page * self::LIMIT);
            })
            ->limit(self::LIMIT)
            ->get();
//        dd($products);
        return $products;
    }

    //thêm sản phẩm
    public function insert($request)
    {
        $isValidPrice = $this->isValidPrice($request);
        if ($isValidPrice === false) return false;

        try {
            //bỏ thang tokken
            $request->except('_token');
            Product::create($request->all());

            Session::flash('success', 'Thêm Sản phẩm thành công');
        } catch (\Exception $err) {
            Session::flash('error', 'Thêm Sản phẩm lỗi');
            \Log::info($err->getMessage());
            return  false;
        }

        return  true;
    }
    public function update($request, $product)
    {
        $isValidPrice = $this->isValidPrice($request);
        if ($isValidPrice === false) return false;

        try {
            $product->fill($request->input());
            $product->save();
            Session::flash('success', 'Cập nhật thành công');
        } catch (\Exception $err) {
            Session::flash('error', 'Có lỗi vui lòng thử lại');
            \Log::info($err->getMessage());
            return false;
        }
        return true;
    }

    public function delete($request)
    {
        $product = Product::where('id', $request->input('id'))->first();
        if ($product) {
            $product->delete();
            return true;
        }

        return false;
    }

    public function show($id){
        $product = Product::where('id',$id)
            ->where('active',1)
            ->with('menu')
            ->firstOrFail();
//        dd($product);
        return $product;
    }

    public function more($id){
        $product = Product::select('id', 'name', 'price', 'price_sale', 'thumb')
            ->where('active', 1)
            ->where('id', '!=', $id)
            ->orderByDesc('id')
            ->limit(4)
            ->get();
        return $product;
    }
}
