@extends('admin.main')



@section('content')
    <table class="table">
        <thead>
        <tr>
            <th style="width: 50px">ID</th>
            <th>Name</th>
            <th>Active</th>
            <th>Update at</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
{{--        $menus được truyển từ controller--}}
        {!! \App\Helpers\Helper::menu($menus)!!}
        </tbody>
    </table>
@endsection

