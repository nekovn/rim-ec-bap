<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="robots" content="noindex">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="Content-Script-Type" content="text/javascript">
  <meta http-equiv="Content-Style-Type" content="text/css">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{$status_code}}</title>
  <meta name="keywords" content="">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}">
  <link href="{{asset('css/admin/app.css')}}" rel="stylesheet">
</head>

<body class="{{!app()->isProduction() ? config('app.env') : ''}}">
  <div class="error-page">
    <main class="error-page__container text-center">
      <img class="error-page__logo" src="{{asset('images/logo.png')}}" alt="">
      <div class="error-page__main">
        <div class="error-page__main__msg">{!!$message !!}</div>
      </div>
      <footer class="error-page__footer">
        <a class="button d-inline-block px-5" href="{{ route('member.shopTop') }}">トップに戻る</a>
      </footer>
    </main>
  </div>
</body>

</html>