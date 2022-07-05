<div class="side-menu">
  <ul>
    <li>
      <a href="#">Top</a>
    </li>
    <li>
      <a href="#input-number-number">数値：整数</a>
    </li>
    <li>
      <a href="#input-number-positive">数値：正の整数</a>
    </li>
    <li>
      <a href="#input-number-decimal">数値：小数</a>
    </li>
  </ul>
</div>
<div class="card" id="input-number-number">
  <div class="card-header">
    <h4>画面設計書：数値：整数</h4>
  </div>
  <div class="card-body">
    <h5>
      数値：整数の入力項目に対するフォームヘルパー
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/456b91be-d7b4-aa00-714b-b5eeedd0bfd3.png" class="d-block mb-2">
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
      <code>&#123;&#123Form::integer('attr-name', null, ['id'=>'attr-id-integer', 'class'=>'attr-class'])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {{Form::integer('attr-name', null, ['id'=>'attr-id-integer', 'class'=>'attr-class'])}}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;input id=&quot;attr-id-integer&quot; class=&quot;attr-class&quot; pattern=&quot;[+-]?\d*&quot; step=&quot;1&quot; data-pattern-message=&quot;整数で入力してください。&quot; name=&quot;attr-name&quot; type=&quot;number&quot; data-parsley-id=&quot;23&quot;&gt;
      </code>
    </pre>
  </div>
</div>
<div class="card" id="input-number-positive">
  <div class="card-header">
    <h4>画面設計書：数値：正の整数</h4>
  </div>
  <div class="card-body">
    <h5>
      数値：正の整数の入力項目に対するフォームヘルパー
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/e0d0f0d6-9f19-8773-cdd1-a74bc34f37af.png" class="d-block mb-2" style="width: 80%;">
    </h6>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      使用方法は、整数と同様
    </div>
    <pre class="copy-target">
      <code>&#123;&#123Form::positiveInteger('attr-name', null, ['id'=>'attr-id-positive', 'class'=>'attr-class'])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
        {{Form::positiveInteger('attr-name', null, ['id'=>'attr-id-positive', 'class'=>'attr-class'])}}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>
        &lt;input id=&quot;attr-id-positive&quot; class=&quot;attr-class&quot; min=&quot;1&quot; step=&quot;1&quot; pattern=&quot;\d*&quot; data-pattern-message=&quot;正の整数で入力してください。&quot; name=&quot;attr-name&quot; type=&quot;number&quot; data-parsley-id=&quot;25&quot;&gt;
      </code>
    </pre>
  </div>
</div>
<div class="card" id="input-number-decimal">
  <div class="card-header">
    <h4>画面設計書：数値：小数</h4>
  </div>
  <div class="card-body">
    <h5>
      数値：整数の入力項目に対するフォームヘルパー
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/e30449f3-b915-0df4-c0e9-0c615e92389a.png" class="d-block mb-2">
    </h6>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      使用方法は、整数と同様
    </div>
    <pre class="copy-target">
      <code>&#123;&#123Form::numberEx('attr-name', null, ['id'=>'attr-id-decimal', 'class'=>'attr-class'])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {{Form::numberEx('attr-name', null, ['id'=>'attr-id-decimal', 'class'=>'attr-class'])}}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code class="code-sample">&lt;input id=&quot;attr-id-decimal&quot; class=&quot;attr-class&quot; name=&quot;attr-name&quot; type=&quot;number&quot; data-parsley-id=&quot;27&quot;&gt;
      </code>
    </pre>
  </div>
</div>