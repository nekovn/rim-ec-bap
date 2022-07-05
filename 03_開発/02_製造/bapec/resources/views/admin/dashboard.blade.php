@php
    $screenName = "ダッシュボード";
    $isSidebarShow = true;
@endphp
@extends('admin.layouts.app')

@section('content')
<div class="fade-in">
    <div class="card">
      <div class="card-header">
        <b>{{$screenName}}</b>
      </div>
      <!-- <div class="card-body ml-10 mr-10">
          <div class="card">
              <div class="card-header">
                xxxxxx
              </div>
              <div class="card-body">
                xxxxxx
            </div>
        </div> -->
      </div>
    </div>
</div>
@endsection

@push('app-script')
<script src="{{mix('js/admin/page/dashboard.page.js')}}" defer></script>
@endpush
