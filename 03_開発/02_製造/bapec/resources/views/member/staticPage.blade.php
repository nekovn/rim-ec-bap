@php
// 固定ページ表示用メイン

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

// bladeファイル存在チェック
if (!View::exists("member.${dir}.${page}")) {
    // bladeファイルが存在しない
    abort(404, 'Not Found');
}

// 各画面のfunctionId、screenNameを設定
// （注意）
//    画面が増える場合はここを指定すること
//    bladeファイルにはcontent部分のみ記述すること
$functionId = '';
$screenName = '';
switch ($page) {
    case 'privacy':
        $functionId = 'privacy';
        $screenName = 'プライバシーポリシー';
        break;
    case 'terms':
        $functionId = 'terms';
        $screenName = 'B-Crewアプリ利用規約';
        break;
    case 'tokusho':
        $functionId = 'tokusho';
        $screenName = '特定商取引法に基づく表記';
        break;
    case 'shipping':
        $functionId = 'shipping';
        $screenName = 'ご利用ガイド';
        break;
    default:
        abort(404, 'Not Found');
        break;
}
@endphp

@extends(Auth::check() ? 'member.layouts.app' : 'member.layouts.base')

@section('breadcrumb')
@endsection

@section('content')
  {{--  画面読み込み  --}}
  @include("member.${dir}.${page}")
@endsection

@section('sidenav')
  @include('member.layouts.sidenav')
@endsection

@section('information')
  @include('member.layouts.information')
@endsection

@push('app-script')
@endpush
