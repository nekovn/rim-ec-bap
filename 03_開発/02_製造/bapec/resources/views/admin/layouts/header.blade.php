<header class="c-header c-header-light c-header-fixed c-header-with-subheader {{!app()->isProduction() ? config('app.env') : ''}}">
  <button class="c-header-toggler c-class-toggler  mfe-auto" type="button" data-target="#sidebar"
    data-class="c-sidebar-show">
    <i class="fas fa-bars c-icon c-icon-lg"></i>
  </button>
  <ul class="c-header-nav ml-auto mr-4">
    <li>
      <span class="c-header-nav-link">
        <i class="fas fa-user c-icon c-icon-lg"></i> {{Auth::user()->name}}
      </span>
    </li>
    <li class="c-header-nav-item mx-2">
      <a class="c-header-nav-link" href="{{route('admin.logout')}}" alt="ログアウト" title="ログアウト">
        <i class="fas fa-sign-out-alt c-icon c-icon-lg"></i> ログアウト
      </a>
    </li>
  </ul>
</header>
