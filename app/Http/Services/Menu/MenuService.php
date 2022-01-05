<?php


namespace App\Http\Services\Menu;


use App\Models\Menu;
use Illuminate\Support\Facades\Session;

class MenuService
{
    public function getParent(){
        return Menu::where('parent_id',0)->get();
    }
    public function getAll(){
//        dd(Menu::orderbyDesc('id')->paginate(20));
        return Menu::orderbyDesc('id')->paginate(20);
    }
    public function create($request){
        try {
//            dd($request->input());
            Menu::create([
                'name' => (string)$request->input('name'),
                'parent_id' => (int)$request->input('parent_id'),
                'description' => (string)$request->input('description'),
                'content' => (string)$request->input('content'),
                'active' => (string)$request->input('active')
            ]);
        Session::flash('success','Tạo danh mục thành công');
        }catch (\Exception $err){
            Session::flash('error', $err->getMessage());
            return false;
        }
        return true;
    }

    public function destroy($request){
        $id = (int)$request->input('id');
        $menu = Menu::where('id', $id)->first();
        if ($menu) {
            //xóa thằng có id và những thằng con có id bằng thằng cha
            return Menu::where('id', $id)->orWhere('parent_id', $id)->delete();
        }

        return false;
    }

    public function update($request,$menu):bool
    {
//        $menu->fill($request->input());
//        $menu->save();
        if($request->input('parent_id')!= $menu->id){
            $menu->parent_id = (int)$request->input('parent_id');
        }
        $menu->name = (string)$request->input('name');
        $menu->description = (string)$request->input('description');
        $menu->content = (string)$request->input('content');
        $menu->active = (string)$request->input('active');
        $menu->save();
        Session::flash('success', 'Cập nhật thành công Danh mục');
        return true;
    }

    public function getId($id){
        return $menu = Menu::where('id',$id)->where('active',1)->firstOrFail();
    }

    public function getProduct($menu,$request){
        $query = $menu->products()
            ->select('id', 'name', 'price', 'price_sale', 'thumb')
            ->where('active', 1);
            if($request->input('price')){
                $query->orderBy('price', $request->input('price'));
            };
//        dd($products);
       return $query
           ->orderByDesc('id')
           ->paginate(12)
           ->withQueryString();
    }

}
