<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  @include('admin.layouts.header-tag')
  @stack('app-style')
</head>

<body class="c-app {{!app()->isProduction() ? config('app.env') : ''}}">
  <div class="c-sidebar c-sidebar-info c-sidebar-fixed {{config('app-settings-admin.page.sidebar.c-sidebar')}} {{!app()->isProduction() ? config('app.env') : ''}}" id="sidebar">
    @include('admin.layouts.nav-builder')
  </div>
  <div class="c-wrapper c-fixed-components" id="app">
    @include('admin.layouts.header')
    <div class="c-body">
      <main class="c-main">
        @yield('content')
      </main>
      @include('admin.layouts.footer')
    </div>
  </div>
  {{-- プログレスバー --}}
  @include('parts.progress-spinner')
  {{-- メッセージボックス --}}
  @include('parts.modalmessage')
  {{-- 共通モジュールより後にloadしたい --}}
  @stack('app-script')

  <script>
    $(function(){
      let isSidebarShow ='<?php echo isset($isSidebarShow) ? $isSidebarShow : false ?>';
      if (isSidebarShow) {
        $('.c-header-toggler').trigger('click');
      }
    });
    </script>
</body>
</html>
