@php
$screenName = "会員登録";
$functionId = 'member-register';

use App\Enums\GenderDefine;
@endphp
@extends('member.layouts.base')
@section('content')
<div id="registration">
    <section class="sec01">
      <h2>REGISTRATION</h2>
	    <p class="jp">{{ $screenName }}</p>
      @if ($errors->any())
        <div class="alert alert-danger">
          <i class="fas fa-exclamation-triangle"></i>
          {{ \Lang::get('messages.E.validation') }}
          @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
          @endforeach
        </div>
      @endif
      {{ Form::open(['route'=>'member.register', 'method'=>'POST', 'id'=>'form-'.$functionId, 'novalidate']) }}
        <dl>
          <dt class="required">氏名</dt>
          <dd>
            {{ Form::textEx('surname', old('surname'), ['class'=>'text01', 'maxlength'=>'30', 'required', 'tabindex'=>'1', 'placeholder' => '姓']) }}
            {{ Form::textEx('name', old('name'), ['class'=>'text01', 'maxlength'=>'30', 'required', 'tabindex'=>'2', 'placeholder' => '名']) }}
          </dd>
          <dt class="required">氏名（カナ）<span class="kome">※全角カナ</span></dt>
          <dd>
            {{ Form::textEx('surname_kana', old('surname_kana'), ['class'=>'text01', 'maxlength'=>'60', 'required', 'data-parsley-zenkana', 'tabindex'=>'3', 'placeholder' => 'セイ']) }}
            {{ Form::textEx('name_kana', old('name_kana'), ['class'=>'text01', 'maxlength'=>'60', 'required', 'data-parsley-zenkana', 'tabindex'=>'4', 'placeholder' => 'メイ']) }}
          </dd>
          <dt class="required">性別</dt>
          <dd>
            <ul class="radio">
              <li>
                {{ Form::radio('gender', GenderDefine::MAN, (old('gender')== GenderDefine::MAN ? true: false), ['id'=>'sex1', 'required', 'data-parsley-errors-container' => '#error_gender']) }}
                {{ Form::label('sex1', '男性', ['class'=>'radio_css', 'tabindex'=>'5']) }}
              </li>
              <li>
                {{ Form::radio('gender', GenderDefine::WOMAN, (old('gender')== GenderDefine::WOMAN ? true: false), ['id'=>'sex2']) }}
                {{ Form::label('sex2', '女性', ['class'=>'radio_css', 'tabindex'=>'6']) }}
              </li>
              <li>
                {{ Form::radio('gender', GenderDefine::OTHER, (old('gender')== GenderDefine::OTHER ? true: false), ['id'=>'sex3']) }}
                {{ Form::label('sex3', 'その他', ['class'=>'radio_css', 'tabindex'=>'7']) }}
              </li>
              <li>
                {{ Form::radio('gender', GenderDefine::NOANSWER, (old('gender')== GenderDefine::NOANSWER ? true: false), ['id'=>'sex4']) }}
                {{ Form::label('sex4', '無回答', ['class'=>'radio_css', 'tabindex'=>'8']) }}
              </li>
            </ul>
            <div id="error_gender"></div>
          </dd>
          <dt class="required">生年月日</dt>
          <dd>
            <?php
              $years = array_reverse(range(today()->year - 100, today()->year));
              array_unshift($years, "");
              $month = range(1, 12);
              array_unshift($month, "");
            ?>
                {{ Form::selectRange('birthday_year', today()->year, today()->year - 100, old('birthday_year'), ['class'=>'birthday-year sel02', 'data-parsley-date'=>'birthday', 'data-parsley-errors-container' => '#error_birthday_year', 'required', 'placeholder'=>'', 'tabindex'=>'9']) }}
                <span class="birth">年</span>

                {{ Form::selectRange('birthday_month', 1, 12, old('birthday_month'), ['class'=>'birthday-month sel03', 'data-parsley-date'=>'birthday', 'data-parsley-errors-container' => '#error_birthday_month', 'required', 'placeholder'=>'','tabindex'=>'10']) }}
                <span class="birth">月</span>

                {{Form::selectRange('birthday_day', 1, 31, old('birthday_day'), ['class'=>'birthday-day sel03', 'data-parsley-date'=>'birthday', 'data-parsley-errors-container' => '#error_birthday_day', 'required', 'placeholder'=>'','tabindex'=>'11'])}}
                <span class="birth">日</span>
                <div id="error_birthday_year"></div>
                <div id="error_birthday_month"></div>
                <div id="error_birthday_day"></div>
          </dd>
          <dt class="required">郵便番号</dt>
          <dd>
            {{Form::postCode('zip', null, ['id'=>'zip', 'class'=>'text02',
              'required',
              'data-parsley-type'=>'digits',
              'data-parsley-errors-container' => '#error_post_code',
              'minlength'=>'7',
              'maxlength'=>'7',
              'tabindex'=>'12'],
                          ['autocomplete'=>[
                            'selector-pref'=>'#prefcode',
                            'selector-city'=>'#addr_1',
                            'selector-town'=>'#addr_2'
                          ],
                          'trigger'=>'#postcode-search-btn'])}}

            {{Form::input('button', 'postcode-search-btn','住所検索', ['id'=>'postcode-search-btn', 'class'=>'search', 'tabindex'=>'13'])}}
            <div id="error_post_code"></div>
          </dd>
          <dt class="required">都道府県</dt>
          <dd>
            {{Form::pref('prefcode', old('prefcode'), ['id'=>'prefcode', 'class'=>'sel01', 'required', 'tabindex'=>'14'])}}
          </dd>
          <dt class="required">市区町村</dt>
          <dd>
            {{ Form::textEx('addr_1', old('addr_1'), ['id'=>'addr_1', 'class'=>'text03', 'maxlength'=>'35', 'required', 'tabindex'=>'15']) }}
          </dd>
          <dt class="required">番地</dt>
          <dd>
            {{ Form::textEx('addr_2', old('addr_2'), ['id'=>'addr_2', 'class'=>'text03', 'maxlength'=>'63', 'required', 'tabindex'=>'16']) }}
          </dd>
          <dt>建物名等</dt>
          <dd>
            {{ Form::textEx('addr_3', old('addr_3'), ['class'=>'text03', 'maxlength'=>'63', 'tabindex'=>'17']) }}
          </dd>
          <dt class="required">電話番号<span class="kome">※半角数値</span></dt>
          <dd>
            {{ Form::textEx('tel', old('tel'), ['class'=>'text04', 'maxlength'=>'15', 'required', 'data-parsley-errors-container' => '#error_tel', 'pattern'=>'\d{2,4}-?\d{2,4}-?\d{3,4}', 'tabindex'=>'18']) }}
            <div id="error_tel"></div>
          </dd>
          <dt class="required">メールアドレス<span class="kome">※半角英数</span></dt>
          <dd>
            {{ Form::textEx('email', old('email'), ['class'=>'text04', 'maxlength'=>'256', 'required', 'data-parsley-type'=>'email', 'data-parsley-errors-container' => '#error_email', 'tabindex'=>'19']) }}
            <div id="error_email"></div>
          </dd>
          <dt class="required">{{Form::labelEx('password', 'パスワード')}}</dt>
          <dd>
            {{Form::passwordEx('password', ['class'=>'text04', 'maxlength'=>'100', 'required', 'tabindex'=>'20'])}}
          </dd>
          <dt class="required">
            {{Form::labelEx('password_confirm', 'パスワード(確認用)')}}
          </dt>
          <dd>
            {{Form::passwordEx('password_confirm', ['class'=>'text04',
                                  'data-compare-name'=>'パスワード',
                                  'maxlength'=>'100',
                                  'data-html-only',
                                  'data-parsley-required'=>'true',
                                  'data-parsley-equalto'=>'#password',
                                  'tabindex'=>'21']
                                )}}
          </dd>
        </dl>
        <div class="kiyaku">
          <p class="link">会員登録には<a href="{{ url('/static/info/privacy') }}" target="_blank">プライバシーポリシー</a>への同意が必要です。</p>
          <p class="check">
            {{ Form::checkbox('agree', '', false, ['id'=>'agree']) }}
            {{ Form::labelEx('agree', '利用規約に同意する', ['class'=>'check_css', 'tabindex'=>'22']) }}
        </div>
        <div class="btn_area">
          {{ Form::submit('登録する', ['id'=>$functionId."-btn-store", 'class'=>'button', 'tabindex'=>'23']) }}
        </div>
      {{Form::close()}}
    </section>
</div>
@endsection

@push('app-script')
<script src="{{mix('js/member/page/member.register.page.js')}}" defer></script>
@endpush
