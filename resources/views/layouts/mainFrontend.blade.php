<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Xojabuxoriy o'rta maxsus islom bilim yurti axborot resurs markazi
        @yield('title')
    </title>
    <meta name="description" content="Xojabuxoriy o'rta maxsus islom bilim yurti axborot resurs markazi">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <link rel="shortcut icon" href=""> --}}
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="{{asset('/assets-frontend/css/bootstrap.min.css')}}">
    <link type="text/css" rel="stylesheet" href="{{asset('/assets-frontend/css/slick.css')}}"/>
    <link type="text/css" rel="stylesheet" href="{{asset('/assets-frontend/css/slick-theme.css')}}"/>
    <link type="text/css" rel="stylesheet" href="{{asset('/assets-frontend/css/nouislider.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('/assets-frontend/css/font-awesome.min.css')}}">
    <link type="text/css" rel="stylesheet" href="{{asset('/assets-frontend/css/style.css')}}"/>
    <link type="text/css" rel="stylesheet" href="{{asset('/assets-frontend/css/custom.css')}}"/>
</head>

<body>

    @include('frontend.includes.header')

		<div class="main-content">
			@yield('content')
		</div>

    @include('frontend.includes.footer')

    <script src="{{asset('/assets-frontend/js/jquery.min.js')}}"></script>
    <script src="{{asset('/assets-frontend/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('/assets-frontend/js/slick.min.js')}}"></script>
    <script src="{{asset('/assets-frontend/js/nouislider.min.js')}}"></script>
    <script src="{{asset('/assets-frontend/js/jquery.zoom.min.js')}}"></script>
    <script src="{{asset('/assets-frontend/js/main.js')}}"></script>
    
</body>
</html>
