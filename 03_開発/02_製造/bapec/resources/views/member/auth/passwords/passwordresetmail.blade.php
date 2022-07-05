@component('mail::message')

@if (!empty($user->name))
    {{ $user->name }} さん
@endif

<span style="font-weight: bold;">以下の認証リンクをクリックしてください。</span>

@component('mail::button', ['url' => $url])
パスワードの再設定
@endcomponent

<br>

### ※もしこのメールに覚えが無い場合は破棄してください。

---

@if (!empty($url))
##### 「パスワードの再設定」ボタンをクリックできない場合は、下記のURLをコピーしてWebブラウザに貼り付けてください。
##### {{ $url }}
@endif

@endcomponent