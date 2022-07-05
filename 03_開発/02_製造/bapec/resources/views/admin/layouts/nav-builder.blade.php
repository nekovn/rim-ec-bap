@php
if(!function_exists('renderDropdown')){
  function renderDropdown($data){
    if( array_key_exists('slug', $data) && ($data['slug'] === 'dropdown' || $data['slug'] === 'title-dropdown')) {
      echo '<li class="c-sidebar-nav-dropdown '. $data['slug'] . '">';
      echo '<a class="c-sidebar-nav-dropdown-toggle" href="#">';
      if ($data['hasIcon'] === true && $data['iconType'] === 'coreui') {
        echo '<i class="' . $data['icon'] . ' c-sidebar-nav-icon"></i>';
      }
      echo $data['name'];
      echo '</a>';
      echo '<ul class="c-sidebar-nav-dropdown-items">';
      renderDropdown( $data['elements'] );
      echo '</ul></li>';
    } else {
      for ($i = 0; $i < count($data); $i++) {
        if ($data[$i]['slug'] === 'link') {
          echo '<li class="c-sidebar-nav-item">';
          echo '<a class="c-sidebar-nav-link" href="' . url($data[$i]['href']) . '">';
          // echo '<span class="c-sidebar-nav-icon">';
          if ($data[$i]['hasIcon'] === true && $data[$i]['iconType'] === 'coreui') {
            echo '<i class="' . $data[$i]['icon'] . ' c-sidebar-nav-icon"></i>';
          }
          echo $data[$i]['name'];
          echo '</a>';
          // echo '</span>' . $data[$i]['name'] . '</a>';
        } elseif ($data[$i]['slug'] === 'dropdown') {
          renderDropdown( $data[$i] );
        }
      }
    }
  }
}
@endphp

<div class="c-sidebar-brand">
  <img class="c-sidebar-brand-full" src="{{asset('/images/logo_small.png')}}" style="height:28px;" alt="Logo">
  <img class="c-sidebar-brand-minimized d-none" src="{{asset('/images/logo.png')}}" width="46" height="46" alt="Logo">
</div>
<ul class="c-sidebar-nav" data-dropdown-accordion="false">
  @if(isset($appMenus))
    @foreach($appMenus as $menuel)
      @if($menuel['slug'] === 'link')
        <li class="c-sidebar-nav-item">
          <a class="c-sidebar-nav-link" href="{{ url($menuel['href']) }}">
            @if($menuel['hasIcon'] === true)
            @if($menuel['iconType'] === 'coreui')
            <i class="{{ $menuel['icon'] }} c-sidebar-nav-icon"></i>
            @endif
            @endif
            {{ $menuel['name'] }}
          </a>
        </li>
      @elseif($menuel['slug'] === 'dropdown')
        @php renderDropdown($menuel) @endphp

      @elseif($menuel['slug'] === 'title-dropdown')
        @php renderDropdown($menuel) @endphp

      @elseif($menuel['slug'] === 'title')
        <li class="c-sidebar-nav-title">
          @if($menuel['hasIcon'] === true)
            @if($menuel['iconType'] === 'coreui')
            <i class="{{ $menuel['icon'] }} c-sidebar-nav-icon"></i>
            @endif
          @endif
          {{ $menuel['name'] }}
        </li>
      @endif
    @endforeach
  @endif
</ul>
<!-- <button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent" data-class="c-sidebar-minimized"></button> -->
