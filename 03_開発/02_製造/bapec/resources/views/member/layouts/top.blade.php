<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  @include('member.layouts.header-tag')
  @stack('app-style')

  @if (app()->isProduction())
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-225191952-2"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-225191952-2');
  </script>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-E8YHM5B83R"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-E8YHM5B83R');
  </script>
  @endif
</head>

<body class="index-page sidebar-collapse {{!app()->isProduction() ? config('app.env') : ''}}">
@if (app()->isProduction())
<script language="JavaScript">
  <!--
      dd = new Date(); time = dd.setTime(dd.getTime());
      document.write("<img src=\"https://www.sipstool.jp/bap/dummy.gif?");
      document.write("time="+time+"&");
      document.write("REF="+document.referrer+"\" border=\"0\" width=\"1\" height=\"1\" style=\"display:none;\">");
  //-->
</script>
@endif
<div id="wrapper">
  @include('member.layouts.header')
  @yield('content')
  @include('member.layouts.footer')
  <div id="pageTop">
      <a href="#wrapper" class="alpha"><img src="{{asset('images/common/btn_pagetop.svg')}}" alt="ページの先頭へ戻る" /></a>
  </div>
</div>
  {{-- 共通モジュールより後にloadしたい --}}
  @stack('app-script')
</body>
</html>
