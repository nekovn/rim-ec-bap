<div class="side-menu">
  <ul>
    <li>
      <a href="#">Top</a>
    </li>
    <li>
      <a href="#input-text-any">任意</a>
    </li>
    <li>
      <a href="#input-text-fw">全角</a>
    </li>
    <li>
      <a href="#input-text-alphanum">半角英数</a>
    </li>
    <li>
      <a href="#input-text-digits">半角数値(前ゼロ可)</a>
    </li>
    <li>
      <a href="#input-text-digits-padzero">半角数値(ゼロ埋め)</a>
    </li>
    <li>
      <a href="#input-text-password">パスワード</a>
    </li>
    <li>
      <a href="#input-text-email">メールアドレス</a>
    </li>
    <li>
      <a href="#input-text-url">URL</a>
    </li>
    <li>
      <a href="#input-text-zip1">郵便番号:住所連携なし</a>
    </li>
    <li>
      <a href="#input-text-zip2">郵便番号:住所連携あり</a>
    </li>
    <li>
      <a href="#input-text-tel">電話番号</a>
    </li>
    <li>
      <a href="#input-text-birth">生年月日</a>
    </li>
  </ul>
</div>

<div class="card" id="input-text-any">
  <div class="card-header">
    <h4>画面設計書：文字列（任意）</h4>
  </div>
  <div class="card-body">
    <h5>
      文字列：任意の入力項目に対するフォームヘルパー
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/a450a9a8-ffe7-151e-fe74-e6943b8f863c.png" class="d-block mb-2">
    </h6>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      <table class="table">
        <thead>
          <th>引数</th>
          <th>説明</th>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>name属性</td>
          </tr>
          <tr>
            <td>2</td>
            <td>初期値（画面遷移が伴う場合、<code>old('name', 初期値)</code>とするとよい。）</td>
          </tr>
          <tr>
            <td>3</td>
            <td>
              inputタグの属性設定
              @include('admin.baseform.attr-sample')
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <pre class="copy-target">
      <code>&#123;&#123Form::textEx('attr-name', null, ['id'=>'attr-id-any', 'class'=>'attr-class'])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {{Form::textEx('attr-name', null, ['id'=>'attr-id-any', 'class'=>'attr-class'])}}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;input id=&quot;attr-id-any&quot; class=&quot;attr-class&quot; name=&quot;attr-name&quot; type=&quot;text&quot;&gt;
      </code>
    </pre>
  </div>
</div>
<div class="card" id="input-text-fw">
  <div class="card-header">
    <h4>画面設計書：文字列（全角）</h4>
  </div>
  <div class="card-body">
    <h5>
      文字列：全角の入力項目に対するフォームヘルパー
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/3c0c5751-dd4e-48a7-9cf5-aae898f15788.png" class="d-block mb-2">
    </h6>
    <hr>
    <h6>
      サンプルコード(共通部品未作成)
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      使用方法は、文字列（任意）と同様
    </div>
    <pre class="copy-target">
      <code>&#123;&#123Form::textEx('attr-name', null, ['id'=>'attr-id-fw', 'class'=>'attr-class'])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {{Form::textEx('attr-name', null, ['id'=>'attr-id-fw', 'class'=>'attr-class'])}}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;input id=&quot;attr-id-fw&quot; class=&quot;attr-class&quot; name=&quot;attr-name&quot; type=&quot;text&quot;&gt;
      </code>
    </pre>
  </div>
</div>
<div class="card" id="input-text-alphanum">
  <div class="card-header">
    <h4>画面設計書：文字列（半角英数）</h4>
  </div>
  <div class="card-body">
    <h5>
      文字列：半角英数の入力項目に対するフォームヘルパー
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/1e2fa345-a0fc-52f0-5492-2ec9d8c60ea4.png" class="d-block mb-2">
    </h6>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      使用方法は、文字列（任意）と同様
    </div>
    <pre class="copy-target">
      <code>&#123;&#123Form::textAlphanum('attr-name', null, ['id'=>'attr-id-alphanum', 'class'=>'attr-class'])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {{Form::textAlphanum('attr-name', null, ['id'=>'attr-id-alphanum', 'class'=>'attr-class'])}}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;input id=&quot;attr-id-alphanum&quot; class=&quot;attr-class&quot; data-parsley-type=&quot;alphanum&quot; name=&quot;attr-name&quot; type=&quot;text&quot; data-parsley-id=&quot;9&quot;&gt;
      </code>
    </pre>
    <strong>※data-parsley-id="x"は入力チェックライブラリ(parsley)によって自動付与される。<code>x</code>の数値は、画面毎に異なる。</strong>
  </div>
</div>
<div class="card" id="input-text-digits">
  <div class="card-header">
    <h4>画面設計書：文字列（半角数値（前ゼロ可））</h4>
  </div>
  <div class="card-body">
    <h5>
      文字列：半角数値（前ゼロ可）の入力項目に対するフォームヘルパー
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/1a6f2676-da57-c6b1-dec5-d52879fb6a4e.png" class="d-block mb-2">
    </h6>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      使用方法は、文字列（任意）と同様
    </div>
    <pre class="copy-target">
      <code>&#123;&#123Form::textDigits('attr-name', null, ['id'=>'attr-id-digits', 'class'=>'attr-class'])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {{Form::textDigits('attr-name', null, ['id'=>'attr-id-digits', 'class'=>'attr-class'])}}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;input id=&quot;attr-id-digits&quot; class=&quot;attr-class&quot; data-parsley-type=&quot;digits&quot; name=&quot;attr-name&quot; type=&quot;text&quot; data-parsley-id=&quot;11&quot;&gt;
      </code>
    </pre>
    <strong>※data-parsley-id="x"は入力チェックライブラリ(parsley)によって自動付与される。<code>x</code>の数値は、画面毎に異なる。</strong>
  </div>
</div>
<div class="card" id="input-text-digits-padzero">
  <div class="card-header">
    <h4>画面設計書：文字列（半角数値（ゼロ埋め））</h4>
  </div>
  <div class="card-body">
    <h5>
      文字列：半角数値（ゼロ埋め）の入力項目に対するフォームヘルパー
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/775a2cc6-d88d-a51e-2219-ed57615c1ede.png" class="d-block mb-2">
    </h6>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      <table class="table">
        <thead>
          <th>引数</th>
          <th>説明</th>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>name属性</td>
          </tr>
          <tr>
            <td>2</td>
            <td>初期値（画面遷移が伴う場合、<code>old('name', 初期値)</code>とするとよい。）</td>
          </tr>
          <tr>
            <td>3</td>
            <td>
              inputタグの属性設定
              <code>
                ['id' => 'attr-id',
                 'class' => 'class1 class2 class3',
                 'maxlength'=>'5'
                 'data-attr' => 'data-attr',
                 ・・・
                ]
              </code><br>
              <strong>※maxlength属性必須</strong>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <pre class="copy-target">
      <code>&#123;&#123Form::textDigits('attr-name', null, ['id'=>'attr-id-digits-padzero', 'class'=>'attr-class', 'maxlength'=>'5'])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {{Form::textPaddingZero('attr-name', null, ['id'=>'attr-id-digits-padzero', 'class'=>'attr-class', 'maxlength'=>'5'])}}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;input id=&quot;attr-id-digits-padzero&quot; class=&quot;attr-class zero-padding parsley-success&quot; maxlength=&quot;5&quot; data-parsley-type=&quot;digits&quot; name=&quot;attr-name&quot; type=&quot;text&quot; data-parsley-id=&quot;13&quot;&gt;
      </code>
    </pre>
    <strong>※data-parsley-id="x"は入力チェックライブラリ(parsley)によって自動付与される。<code>x</code>の数値は、画面毎に異なる。</strong>
  </div>
</div>
<div class="card" id="input-text-password">
  <div class="card-header">
    <h4>画面設計書：文字列（パスワード）</h4>
  </div>
  <div class="card-body">
    <h5>
      文字列：パスワードの入力項目に対するフォームヘルパー
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/35d9b2ee-0d58-93ee-ab31-722aafde1cd1.png" class="d-block mb-2">
    </h6>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      <table class="table">
        <thead>
          <th>引数</th>
          <th>説明</th>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>name属性</td>
          </tr>
          <tr>
            <td>2</td>
            <td>
              inputタグの属性設定
              @include('admin.baseform.attr-sample')
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <pre class="copy-target">
      <code>&#123;&#123Form::passwordEx('attr-name', ['id'=>'attr-id-password', 'class'=>'attr-class'])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {{Form::passwordEx('attr-name', ['id'=>'attr-id-password', 'class'=>'attr-class'])}}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;input id=&quot;attr-id-password&quot; name=&quot;attr-name&quot; type=&quot;password&quot; value=&quot;&quot; data-parsley-id=&quot;15&quot;&gt;
      </code>
    </pre>
    <strong>※data-parsley-id="x"は入力チェックライブラリ(parsley)によって自動付与される。<code>x</code>の数値は、画面毎に異なる。</strong>
  </div>
</div>
<div class="card" id="input-text-email">
  <div class="card-header">
    <h4>画面設計書：文字列（メールアドレス）</h4>
  </div>
  <div class="card-body">
    <h5>
      文字列：メールアドレスの入力項目に対するフォームヘルパー
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/4e4cadb8-46b3-4ef6-29a3-e4fd8c9d87e9.png" class="d-block mb-2">
    </h6>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      使用方法は、文字列（任意）と同様
    </div>
    <pre class="copy-target">
      <code>&#123;&#123Form::emailEx('attr-name', null, ['id'=>'attr-id-email', 'class'=>'attr-class'])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {{Form::emailEx('attr-name', null, ['id'=>'attr-id-email', 'class'=>'attr-class'])}}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;input id=&quot;attr-id-email&quot; class=&quot;form-control&quot; maxlength=&quot;255&quot; name=&quot;attr-email&quot; type=&quot;email&quot;&gt;
      </code>
    </pre>
    <strong>※data-parsley-id="x"は入力チェックライブラリ(parsley)によって自動付与される。<code>x</code>の数値は、画面毎に異なる。</strong>
  </div>
</div>
<div class="card" id="input-text-url">
  <div class="card-header">
    <h4>画面設計書：文字列（URL）</h4>
  </div>
  <div class="card-body">
    <h5>
      文字列：URLの入力項目に対するフォームヘルパー
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/83c17c3f-352c-39fe-cc95-f0960f3257e1.png" class="d-block mb-2">
    </h6>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      使用方法は、文字列（任意）と同様
    </div>
    <pre class="copy-target">
      <code>&#123;&#123Form::urlEx('attr-name', null, ['id'=>'attr-id-url', 'class'=>'attr-class'])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {{Form::urlEx('attr-name', null, ['id'=>'attr-id-url', 'class'=>'attr-class'])}}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;input id=&quot;attr-id-url&quot; class=&quot;attr-class&quot; name=&quot;attr-name&quot; type=&quot;url&quot; data-parsley-id=&quot;17&quot;&gt;
      </code>
    </pre>
    <strong>※data-parsley-id="x"は入力チェックライブラリ(parsley)によって自動付与される。<code>x</code>の数値は、画面毎に異なる。</strong>
  </div>
</div>
<div class="card" id="input-text-zip1">
  <div class="card-header">
    <h4>画面設計書：文字列（郵便番号：住所連携なし）</h4>
  </div>
  <div class="card-body">
    <h5>
      文字列：郵便番号の入力項目に対するフォームヘルパー
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/1c739de2-226c-1da1-6e07-fa647e89569f.png" class="d-block mb-2">
      <strong>※郵便番号のハイフン区切りの編集はシステム設定で決定する。</strong>
    </h6>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      使用方法は、文字列（任意）と同様
    </div>
    <pre class="copy-target">
      <code>&#123;&#123Form::postCode('attr-name', null, ['id'=>'attr-id-postcode-8', 'class'=>'attr-class'])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {{Form::postCode('attr-name', null, ['id'=>'attr-id-postcode-8', 'class'=>'attr-class'])}}
    </div>
    <h6>
      出力されるhtml（ハイフン区切りあり）
    </h6>
    <pre>
      <code>&lt;input id=&quot;attr-id-postcode-8&quot; class=&quot;attr-class post-code&quot; pattern=&quot;\d{3}-?\d{4}&quot; data-parsley-length=&quot;[8, 8]&quot; data-format-hyphen=&quot;1&quot; name=&quot;attr-name&quot; type=&quot;text&quot;&gt;
      </code>
    </pre>
    <strong>※data-parsley-id="x"は入力チェックライブラリ(parsley)によって自動付与される。<code>x</code>の数値は、画面毎に異なる。</strong>
    <br>
    <h6>
      出力されるhtml（ハイフン区切りなし）
    </h6>
    <pre>
      <code>&lt;input id=&quot;attr-id-postcode-7&quot; class=&quot;attr-class post-code&quot; pattern=&quot;\d{7}&quot; data-parsley-length=&quot;[7, 7]&quot; data-format-hyphen=&quot;0&quot; name=&quot;attr-name&quot; type=&quot;text&quot;&gt;
      </code>
    </pre>
    <strong>※data-parsley-id="x"は入力チェックライブラリ(parsley)によって自動付与される。<code>x</code>の数値は、画面毎に異なる。</strong>
  </div>
</div>
<div class="card" id="input-text-zip2">
  <div class="card-header">
    <h4>画面設計書：文字列（郵便番号：住所連携あり）</h4>
  </div>
  <div class="card-body">
    <h5>
      文字列：郵便番号の入力項目に対するフォームヘルパー
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/1c739de2-226c-1da1-6e07-fa647e89569f.png" class="d-block mb-2">
      <strong>※郵便番号のハイフン区切りの編集はシステム設定で決定する。</strong>
    </h6>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      <table class="table">
        <thead>
          <th>引数</th>
          <th>説明</th>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>name属性</td>
          </tr>
          <tr>
            <td>2</td>
            <td>初期値（画面遷移が伴う場合、<code>old('name', 初期値)</code>とするとよい。）</td>
          </tr>
          <tr>
            <td>3</td>
            <td>
              inputタグの属性設定
              @include('admin.baseform.attr-sample')
            </td>
          </tr>
          <tr>
            <td>4</td>
            <td>
              追加設定
              <div>
                <code>
                  ['autocomplete' => [<br>
                  　　'selector-pref' => 'セレクター', // 都道府県を示すid属性<br>
                  　　'selector-city' => 'セレクター', // 市区町村を示すid属性<br>
                  　　'selector-town' => 'セレクター   // 番地を示すid属性<br>
                  　]<br>
                  ]
                </code>
              </div>
              <strong>指定がある要素に対して設定を行う。</strong>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <pre class="copy-target">
      <code>&#123;&#123Form::postCode('attr-name', null, ['id'=>'attr-id-postcode', 'class'=>'attr-class', ['autocomplete' => ['selector-pref' => 'pref-id', 'selector-city' => 'city-id', 'selector-town' => 'town-id']])&#125;&#125;
      </code>
    </pre>
    <pre class="mb-1">
      <code>import AddrService from '@js/service/addr';
AddrService.bind(selector, AddrService.url.ADDR);
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      <div>
        {{Form::postCode('attr-name', null,
                         ['id'=>'attr-id-postcode', 'class'=>'attr-class'],
                         ['autocomplete' => ['selector-pref' => 'pref-id', 'selector-city' => 'city-id', 'selector-town' => 'town-id']])}}
      </div>
      <div>
        {{Form::pref('attr-name', null, ['id'=>'pref-id', 'class'=>'attr-class'])}}
      </div>
      <div>
        {{Form::textEx('attr-name', null, ['id'=>'city-id', 'class'=>'attr-class'])}}
      </div>
      <div>
        {{Form::textEx('attr-name', null, ['id'=>'town-id', 'class'=>'attr-class'])}}
      </div>
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;input id=&quot;attr-id-postcode&quot; class=&quot;attr-class post-code&quot; pattern=&quot;\d{3}-?\d{4}&quot; data-parsley-length=&quot;[8, 8]&quot; data-format-hyphen=&quot;1&quot; data-addr-autocomplete data-selector-pref=&quot;pref-id&quot; data-selector-city=&quot;city-id&quot; data-selector-town=&quot;town-id&quot; name=&quot;attr-name&quot; type=&quot;text&quot;&gt;
      </code>
    </pre>
    <strong>※ハイフン区切りあり・なしの出力分けは「郵便番号：住所連携なし」と同様。</strong><br>
    <strong>※data-parsley-id="x"は入力チェックライブラリ(parsley)によって自動付与される。<code>x</code>の数値は、画面毎に異なる。</strong>
  </div>
</div>
<div class="card" id="input-text-tel">
  <div class="card-header">
    <h4>画面設計書：文字列（電話番号）</h4>
  </div>
  <div class="card-body">
    <h5>
      文字列：電話番号の入力項目に対するフォームヘルパー
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/bf518c67-c54f-3519-9d31-fa7b2843d6cf.png" class="d-block mb-2">
    </h6>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      使用方法は、文字列（任意）と同様
    </div>
    <pre class="copy-target">
      <code>&#123;&#123Form::textTel('attr-name', null, ['id'=>'attr-id-tel', 'class'=>'attr-class'])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {{Form::textTel('attr-name', null, ['id'=>'attr-id-tel', 'class'=>'attr-class'])}}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;input id=&quot;attr-id-tel&quot; class=&quot;phone-number attr-class&quot; maxlength=&quot;15&quot; data-format-hyphen=&quot;1&quot; data-parsley-phone-number name=&quot;attr-name&quot; type=&quot;tel&quot;&gt;
      </code>
    </pre>
    <strong>※data-parsley-id="x"は入力チェックライブラリ(parsley)によって自動付与される。<code>x</code>の数値は、画面毎に異なる。</strong>
  </div>
</div>
<div class="card" id="input-text-birth">
  <div class="card-header">
    <h4>画面設計書：文字列（生年月日）</h4>
  </div>
  <div class="card-body">
    <h5>
      文字列：生年月日の入力項目に対するフォームヘルパー
    </h5>
    <h6>
      画面項目定義<code>要調整</code>
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/bf518c67-c54f-3519-9d31-fa7b2843d6cf.png" class="d-block mb-2">
    </h6>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      使用方法は、文字列（任意）と同様
    </div>
    <pre class="copy-target">
      <code>&#123;&#123Form::birthday('attr-name', null, ['id'=>'attr-id-birthday', 'class'=>'attr-class'], '#age')&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {{Form::birthday('attr-name', null, ['id'=>'attr-id-birthday', 'class'=>'attr-class'], '#age')}}
      <span id="age"></span>歳
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;input id=&quot;attr-id-birthday&quot; class=&quot;phone-number attr-class&quot; maxlength=&quot;15&quot; data-age=&quot;#age&quot; data-format-hyphen=&quot;1&quot; data-parsley-phone-number name=&quot;attr-name&quot; type=&quot;tel&quot;&gt;
      </code>
    </pre>
    <strong>※data-parsley-id="x"は入力チェックライブラリ(parsley)によって自動付与される。<code>x</code>の数値は、画面毎に異なる。</strong>
  </div>
</div>
@push('app-script')
<script>
  $(function() {
    $('.post-code').autocomplete({
      source: (request, reseponse) => {
        const zipCode = encodeURIComponent(request.term);
        $.getJSON(`/api/zip/search/addr/${zipCode}`, (data) => {
          $('.post-code').data('zips', JSON.stringify(data));
          reseponse(data);
        });
      },
      response: (e, ui) => {
        if (ui.content.length === 1) {
          $('.post-code')
            .data('ui-autocomplete')
            ._trigger('select', 'autocompleteselect', {
              item: ui.content[0]
            });
        }
      },
      autoFocus: true,
      delay: 100,
      minLength: 7,
      select: (e, ui) => {
        if (ui.item) {
          const zips = JSON.parse($('.post-code').data('zips'));
          const zip = _.find(zips, (zip) => zip.label == ui.item.label);
          $('#pref-id').val(zip.local_cd.substr(0, 2));
          const townName = zip.town_name != '以下に掲載がない場合' ? zip.town_name : '';
          $('#city-id').val(zip.municipality_name);
          $('#town-id').val(townName);
        }
      }
    });
  });
</script>
@endpush