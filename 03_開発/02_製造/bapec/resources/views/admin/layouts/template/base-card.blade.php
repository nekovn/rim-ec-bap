<?php
/**
 * Cardのみtemplate
 * CardHeader:タイトル 
 */
?>
@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
  <div id="{{$functionId}}-area" >
    <div class="fade-in">
      <div class="card ">
        <div class="card-header d-flex align-items-center">
          @yield('card-header-back')
          <b>{{$screenName}}</b>
          
          @yield('card-header-items')

          <div class="card-header-actions ml-auto">
            @yield('card-header-actions')
          </div>
        </div>
        <div class="card-body pt-0">
          {{-- メインCard --}}
          @yield('main-area')

          @if (! isset($noFooter))
          <div class="btn-footer">
            @yield('btn-footer')
          </div>
          @endif
        </div>
      </div>

      @yield('main-after')
    </div>
  </div>
</div>

@yield('etcsection')

@endsection