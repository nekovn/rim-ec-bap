<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="robots" content="noindex">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, shrink-to-fit=no">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{config('app.name')}} - {{$screenName}}</title>

<link rel="shortcut icon" href="{{asset('images/favicon.ico')}}">

<script src="{{asset('vendor/jquery/jquery.min.js')}}" ></script>

<!-- <link href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet"> -->
<script src="{{asset('vendor/bootstrap/js/bootstrap.min.js')}}" defer></script>

<link href="{{asset('vendor/jquery-ui/jquery-ui.min.css')}}" rel="stylesheet">
<script src="{{asset('vendor/jquery-ui/jquery-ui.min.js')}}" defer></script>

<link href="{{asset('vendor/jquery-toast/jquery.toast.min.css')}}" rel="stylesheet">
<script src="{{asset('vendor/jquery-toast/jquery.toast.min.js')}}" defer></script>

<script src="{{asset('vendor/lodash/lodash.js')}}" ></script>
<script src="{{asset('vendor/libphonenumber/libphonenumber-js.min.js')}}" defer></script>

{{-- 好きなテーマを選ぶ https://flatpickr.js.org/themes/--}}
<!-- <link href="{{asset('vendor/flatpickr/css/material_green.css')}}" rel="stylesheet"> -->
<!-- <script src="{{asset('vendor/flatpickr/js/flatpickr.min.js')}}" defer></script> -->
<!-- <script src="{{asset('vendor/flatpickr/js/rangePlugin.js')}}" defer></script> -->
<!-- <link href="{{asset('vendor/flatpickr/css/monthSelect.css')}}" rel="stylesheet" /> -->
<!-- <script src="{{asset('vendor/flatpickr/js/monthSelect.js')}}" defer></script> -->
<!-- <script src="{{asset('vendor/flatpickr/js/ja.js')}}" defer></script> -->

<!-- <script src="{{asset('vendor/moment/moment.min.js')}}" defer></script> -->
<!-- <script src="{{asset('vendor/moment/locale/ja.js')}}" defer></script> -->

<link href="{{asset('vendor/featherlight/featherlight.min.css')}}" rel="stylesheet" />
<script src="{{asset('vendor/featherlight/featherlight.min.js')}}" defer></script>

<script src="{{asset('vendor/superagent/superagent.min.js')}}" defer></script>

<script src="{{asset('vendor/parsley/parsley.min.js')}}" defer></script>
<script src="{{asset('vendor/parsley/i18n/ja.js')}}" defer></script>
<script src="{{asset('vendor/parsley/i18n/ja.extra.js')}}" defer></script>

<!-- <script src="{{asset('vendor/tooltipster-master/js/tooltipster.bundle.js')}}" defer></script> -->
<!-- <link href="{{asset('vendor/tooltipster-master/css/tooltipster.bundle.min.css')}}" rel="stylesheet" /> -->
<!-- <link href="{{asset('vendor/tooltipster-master/css/plugins/tooltipster/sideTip/themes/tooltipster-sideTip-borderless.min.css')}}" rel="stylesheet" /> -->

{{-- promise ie11対応 --}}
<script>window.Promise || document.write('<script src="//www.promisejs.org/polyfills/promise-7.0.4.min.js"><\/script>');</script>

@php
$appConfig = SystemHelper::getAppSettingsName();
$appConfig = "js/{$appConfig}.js";
@endphp
<script src="{{asset($appConfig)}}" defer></script>

<!-- <link href="{{asset('css/member/modalmessage.css')}}" rel="stylesheet"> -->
<link href="{{asset('css/member/notifications.css')}}" rel="stylesheet">
<link href="{{asset('css/member/validator.css')}}" rel="stylesheet">

<link href="{{asset('vendor/slick/slick.css')}}" rel="stylesheet" />
<link href="{{asset('css/member/slick-theme.css')}}" rel="stylesheet" />
<script src="{{asset('vendor/slick/slick.js')}}" defer></script>

<script src="{{mix('js/member/app.js')}}" defer></script>
<link href="{{asset('css/member/app.css')}}" rel="stylesheet">

<link href="{{asset('css/member/style.css')}}" rel="stylesheet">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

<script src="{{asset('js/member/common.js')}}" defer></script>

@stack('head-style')
@stack('head-script')
