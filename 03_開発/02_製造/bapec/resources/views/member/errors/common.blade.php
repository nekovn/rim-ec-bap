@php
// フロントサイト側エラー画面
$functionId = 'error';
$screenName = 'エラー画面';
@endphp

@extends('member.layouts.base')

@section('breadcrumb')
@endsection

@section('content')
  <section class="sec01">
      <h2>ERROR</h2>
      <p class="jp">エラー</p>

    @if ($status_code == 404)
      <div>お探しのページは見つかりませんでした。</div>
    @else
      <div>{{ $message }}</div>
    @endif
    <div class="btn_area">
      <a class="button" href="{{ route('member.shopTop') }}">トップに戻る</a>
    </div>
  </section>
@endsection

@section('sidenav')
  @include('member.layouts.sidenav')
@endsection

@section('information')
  @include('member.layouts.information')
@endsection

@push('app-script')
@endpush
