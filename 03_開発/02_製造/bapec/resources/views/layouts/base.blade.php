<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="robots" content="noindex">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="Content-Script-Type" content="text/javascript">
  <meta http-equiv="Content-Style-Type" content="text/css">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{$screenName}}｜{{config('app.name')}}</title>
  <meta name="keywords" content="">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" href="{{asset('public/images/favicon.ico')}}">
  @stack('head-style')
  @stack('head-script')
</head>
<body class="{{!app()->isProduction() ? config('app.env') : ''}}">
<div id="wrapper">
  @yield('header')
  @yield('base-content')
  @yield('footer')
  {{-- 共通モジュールより後にloadしたい --}}
  @yield('app-style')
  @yield('app-script')
</div>
</body>
</html>
