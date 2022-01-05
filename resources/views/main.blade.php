<!DOCTYPE html>
<html lang="en">
<head>
@include('head')
</head>
{{--<body class="animsition">--}}
<body >


<!-- Header -->
@include('header')
<!-- Cart -->
@include('cart')



@yield('content')


@include('footer')
</body>
</html>
