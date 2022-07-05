<div class="d-none">
@if (session('success'))
<div id="toast-message_success">{{ session('success') }}</div>
@endif
@if (session('warning'))
<div id="toast-message_warning">{{ session('warning') }}</div>
@endif
@if (session('danger'))
<div id="toast-message_danger">{{ session('danger') }}</div>
@endif
@if (session('info'))
<div id="toast-message_info">{{ session('info') }}</div>
@endif
</div>

@if (session('success') || session('warning') || session('danger') || session('info'))
<script src="{{mix('js/member/toaster.js')}}" defer></script>
@endif
