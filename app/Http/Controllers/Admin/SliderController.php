<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Services\Slider\SliderService;

class SliderController extends Controller
{
    protected $slider;

    public function __construct(SliderService $slider)
    {
        $this->slider = $slider;
    }

    public function create()
    {
        $user = User::get();

        return view('admin.slider.add', [
            'title' => 'Thêm SLider mới',
            'user' =>$user
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'thumb' => 'required',
            'url'   => 'required'
        ]);

        $this->slider->insert($request);

        return redirect()->back();
    }

    public function index()
    {
        $user = User::get();
        return view('admin.slider.list', [
            'title' => 'Danh Sách Slider Mới Nhất',
            'sliders' => $this->slider->get(),
            'user' => $user
        ]);
    }

    public function show(Slider $slider)
    {
        $user = User::get();
        return view('admin.slider.edit', [
            'title' => 'Chỉnh Sửa Slider',
            'slider' => $slider,
            'user'=>$user
        ]);
    }

    public function update(Request $request, Slider $slider)
    {
        $this->validate($request, [
            'name' => 'required',
            'thumb' => 'required',
            'url'   => 'required'
        ]);

        $result = $this->slider->update($request, $slider);
        if ($result) {
            return redirect('/admin/sliders/list');
        }

        return redirect()->back();
    }

    public function destroy(Request $request)
    {
        $result = $this->slider->destroy($request);
        if ($result) {
            return response()->json([
                'error' => false,
                'message' => 'Xóa thành công Slider'
            ]);
        }

        return response()->json([ 'error' => true ]);
    }
}
