@extends('main')
@section('content')
    <div class="container p-t-80">
        <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
            <a href="/" class="stext-109 cl8 hov-cl1 trans-04">
                Home
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
            </a>



            <span class="stext-109 cl4">
				{{ $title }}
			</span>
        </div>
    </div>

    <section class="bg0 p-t-23 p-b-140">
        <div class="container">
            @if(count($products)>0)

            <div class="text-center">
                <h3> Bạn tìm thấy <span class="text-success">{{count($products)}}</span> sản phẩm </h3>
            </div>
            @endif
    <div id="loadProduct">

    @if(count($products)>0)
    @include('products.list')
        @else
            <h2 class="text-danger"> Không có sản phẩm bạn đang tìm</h2>
        @endif
    </div>
        </div>
    </section>
@endsection
