@php
  use App\Helpers\Util\SystemHelper;
@endphp
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="robots" content="noindex">
<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{config('app.name')}} - {{$screenName}}</title>

<link rel="shortcut icon" href="{{asset('images/favicon.ico')}}">

<script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>

{{-- CoreUI and necessary plugins --}}
<script src="{{asset('vendor/coreui/js/coreui.bundle.js')}}" defer></script>

<link href="{{asset('vendor/jquery-ui/jquery-ui.min.css')}}" rel="stylesheet">
<script src="{{asset('vendor/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{asset('vendor/jquery-ui/jquery.simpleDialog.js')}}"></script>

<link href="{{asset('vendor/jquery-toast/jquery.toast.min.css')}}" rel="stylesheet">
<script src="{{asset('vendor/jquery-toast/jquery.toast.min.js')}}" defer></script>

<script src="{{asset('vendor/twbs-pagination/jquery.twbsPagination.min.js')}}" defer></script>

<script src="{{asset('vendor/lodash/lodash.js')}}"></script>
<script src="{{asset('vendor/libphonenumber/libphonenumber-js.min.js')}}" defer></script>

{{-- 好きなテーマを選ぶ https://flatpickr.js.org/themes/--}}
<link href="{{asset('vendor/flatpickr/css/material_blue.css')}}" rel="stylesheet">
<script src="{{asset('vendor/flatpickr/js/flatpickr.min.js')}}" defer></script>
<script src="{{asset('vendor/flatpickr/js/rangePlugin.js')}}" defer></script>
<link href="{{asset('vendor/flatpickr/css/monthSelect.css')}}" rel="stylesheet" />
<script src="{{asset('vendor/flatpickr/js/monthSelect.js')}}" defer></script>
<script src="{{asset('vendor/flatpickr/js/ja.js')}}" defer></script>

<script src="{{asset('vendor/moment/moment.min.js')}}" defer></script>
<script src="{{asset('vendor/moment/locale/ja.js')}}" defer></script>

<script src="{{asset('vendor/superagent/superagent.min.js')}}" defer></script>

<script src="{{asset('vendor/parsley/parsley.min.js')}}" defer></script>
<script src="{{asset('vendor/parsley/i18n/ja.js')}}" defer></script>
<script src="{{asset('vendor/parsley/i18n/ja.extra.js')}}" defer></script>

<script src="{{asset('vendor/tooltipster-master/js/tooltipster.bundle.js')}}" defer></script>
<link href="{{asset('vendor/tooltipster-master/css/tooltipster.bundle.min.css')}}" rel="stylesheet" />
<link href="{{asset('vendor/tooltipster-master/css/plugins/tooltipster/sideTip/themes/tooltipster-sideTip-borderless.min.css')}}" rel="stylesheet" />

{{-- select2 --}}
<link href="{{asset('vendor/select2/select2.min.css')}}" rel="stylesheet" />
<script src="{{asset('vendor/select2/select2.min.js')}}" defer></script>

@php
$appConfig = SystemHelper::getAppSettingsName();
$appConfig = "js/{$appConfig}.js";
@endphp
<script src="{{asset($appConfig)}}" defer></script>

<script src="{{mix('js/admin/app.js')}}" defer></script>
<link href="{{mix('css/admin/app.css')}}" rel="stylesheet">
<script src="{{mix('js/admin/sidebar.js')}}" defer></script>

<!--
<script>
  // ui-tooltipとbootstrapのtooltipが競合して動かなくなるのを防ぐため
  $.widget.bridge('uibutton', $.ui.button);
  $.widget.bridge('uitooltip', $.ui.tooltip);
  // tooltip
  $(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip({
        placement : 'right'
    });
  });
</script>
-->

@stack('head-style')
@stack('head-script')
