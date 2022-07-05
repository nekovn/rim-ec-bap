<div class="side-menu">
  <ul>
    <li>
      <a href="#">Top</a>
    </li>
    <li>
      <a href="#input-checkbox">チェックボックス</a>
    </li>
    <li>
      <a href="#input-radio">ラジオボタン</a>
    </li>
    <li>
      <a href="#select">プルダウン</a>
    </li>
    <li>
      <a href="#select-group">プルダウン(グループ)</a>
    </li>
    <li>
      <a href="#select-filter">プルダウン(フィルター)</a>
    </li>
  </ul>
</div>
<div class="card" id="input-checkbox">
  <div class="card-header">
    <h4>チェックボックス</h4>
  </div>
  <div class="card-body">
    <h5>
      チェックボックスの入力項目に対するフォームヘルパー
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/3a40c5ff-b046-4144-acfe-8d5798060c69.png" class="d-block mb-2">
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
            <td>選択肢のキー・バリューを持つ連想配列</td>
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
            <td>初期選択状態（画面遷移が伴う場合、<code>old('name', 初期値)</code>とするとよい。）</td>
          </tr>
        </tbody>
      </table>
    </div>
    <pre class="copy-target">
      <code>&#123;!!Form::checkboxes('attr-name', ['value' => 1, 'label' => 'checkbox1'], ['value' => 2, 'label' => 'checkbox2'], ['value' => 3, 'label' => 'checkbox3']], ['id'=>'attr-id-checkbox', 'class'=>'attr-class'], [2, 3])!!&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {!!Form::checkboxes('attr-name', [['value' => 1, 'label' => 'checkbox1'], ['value' => 2, 'label' => 'checkbox2'], ['value' => 3, 'label' => 'checkbox3']], ['id'=>'attr-id-checkbox', 'class'=>'attr-class'], [2, 3])!!}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre style="height: 150px;">
      <code>&lt;div class=&quot;form-group form-check form-check-inline&quot;&gt;
  &lt;input id=&quot;attr-id-checkbox-0&quot; class=&quot;form-check-input attr-class&quot; name=&quot;attr-name&quot; type=&quot;checkbox&quot; value=&quot;1&quot; data-parsley-multiple=&quot;attr-name&quot;&gt;
  &lt;label for=&quot;attr-id-checkbox-0&quot; class=&quot;form-check-label ml-2&quot;&gt;checkbox1&lt;/label&gt;
&lt;/div&gt;
      </code>
    </pre>
  </div>
</div>
<div class="card" id="input-radio">
  <div class="card-header">
    <h4>ラジオボタン</h4>
  </div>
  <div class="card-body">
    <h5>
      ラジオボタンの入力項目に対するフォームヘルパー
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/259cc58e-0733-32e7-c81b-ac0840721573.png" class="d-block mb-2">
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
            <td>選択肢のキー・バリューを持つ連想配列</td>
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
            <td>初期選択状態（画面遷移が伴う場合、<code>old('name', 初期値)</code>とするとよい。）</td>
          </tr>
        </tbody>
      </table>
    </div>
    <pre class="copy-target">
      <code>&#123;!!Form::radios('attr-name', [['value'=>'1', 'label'=>'radio1'], ['value'=>'2', 'label'=>'radio2'], ['value'=>'3', 'label'=>'radio3']], ['id'=>'attr-id-radio', 'class'=>'attr-class'], '1')!!&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {!!Form::radios('attr-name', [['value'=>'1', 'label'=>'radio1'], ['value'=>'2', 'label'=>'radio2'], ['value'=>'3', 'label'=>'radio3']], ['id'=>'attr-id-radio', 'class'=>'attr-class'], '1')!!}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre style="height: 290px;">
      <code class="code-sample">&lt;div class=&quot;form-group form-check form-check-inline&quot;&gt;
  &lt;input id=&quot;attr-id-radio-0&quot; class=&quot;form-check-input attr-class&quot; checked=&quot;checked&quot; name=&quot;attr-name&quot; type=&quot;radio&quot; value=&quot;1&quot; data-parsley-multiple=&quot;attr-name&quot;&gt;
  &lt;label for=&quot;attr-id-radio-0&quot; class=&quot;form-check-label ml-2&quot;&gt;radio1&lt;/label&gt;
&lt;/div&gt;
&lt;div class=&quot;form-group form-check form-check-inline&quot;&gt;
  &lt;input id=&quot;attr-id-radio-1&quot; class=&quot;form-check-input attr-class&quot; name=&quot;attr-name&quot; type=&quot;radio&quot; value=&quot;2&quot; data-parsley-multiple=&quot;attr-name&quot;&gt;
  &lt;label for=&quot;attr-id-radio-1&quot; class=&quot;form-check-label ml-2&quot;&gt;radio2&lt;/label&gt;
&lt;/div&gt;
&lt;div class=&quot;form-group form-check form-check-inline&quot;&gt;
  &lt;input id=&quot;attr-id-radio-2&quot; class=&quot;form-check-input attr-class&quot; name=&quot;attr-name&quot; type=&quot;radio&quot; value=&quot;3&quot; data-parsley-multiple=&quot;attr-name&quot;&gt;
  &lt;label for=&quot;attr-id-radio-2&quot; class=&quot;form-check-label ml-2&quot;&gt;radio3&lt;/label&gt;
&lt;/div&gt;
      </code>
    </pre>
  </div>
</div>
<div class="card" id="select">
  <div class="card-header">
    <h4>プルダウン</h4>
  </div>
  <div class="card-body">
    <h5>
      プルダウンの入力項目に対するフォームヘルパー
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/f7ea3200-af6a-fe6f-73ca-f881439f9925.png" class="d-block mb-2">
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
            <td>選択肢のキー・バリューを持つ連想配列</td>
          </tr>
          <tr>
            <td>3</td>
            <td>初期選択状態</td>
          </tr>
          <tr>
            <td>4</td>
            <td>
              inputタグの属性設定
              @include('admin.baseform.attr-sample')
            </td>
          </tr>
          <tr>
            <td>5</td>
            <td>
              設定任意
              <table class="table">
                <thead>
                  <tr>
                    <th></th>
                    <th>key</th>
                    <th>value</th>
                    <th>備考</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>先頭行に空行挿入</td>
                    <td>insert-empty</td>
                    <td>true: する、false: しない</td>
                    <td>初期値：false</td>
                  </tr>
                  <tr>
                    <td>空行の表示文字列</td>
                    <td>empty-label</td>
                    <td>表示名</td>
                    <td>未設定の場合、<code>選択してください</code>を表示する</td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <pre class="copy-target">
      <code>&#123;&#123;Form::dropdown('attr-name', ['1'=>'option1', '2'=>'option2', '3'=>'option3'], 2, ['id'=>'attr-id-select', 'class'=>'attr-class'], ['insert-empty' => true, 'empty-label' => '選んで'])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {{Form::dropdown('attr-name', ['1'=>'option1', '2'=>'option2', '3'=>'option3'], '2', ['id'=>'attr-id-select', 'class'=>'attr-class'], ['insert-empty' => true, 'empty-label' => '選んで'])}}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre style="height: 290px;">
      <code>&lt;select id=&quot;attr-id-select&quot; class=&quot;form-control attr-class&quot; name=&quot;attr-name&quot; data-parsley-id=&quot;52&quot;&gt;
  &lt;option value=&quot;&quot;&gt;選んで&lt;/option&gt;
  &lt;option value=&quot;1&quot;&gt;option1&lt;/option&gt;
  &lt;option value=&quot;2&quot; selected=&quot;selected&quot;&gt;option2&lt;/option&gt;
  &lt;option value=&quot;3&quot;&gt;option3&lt;/option&gt;
&lt;/select&gt;
      </code>
    </pre>
  </div>
</div>
<div class="card" id="select-group">
  <div class="card-header">
    <h4>プルダウン（グループ）</h4>
  </div>
  <div class="card-body">
    <h5>
      プルダウン（グループ）の入力項目に対するフォームヘルパー
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/ee9b9b8b-0cb2-932f-cb15-7552b8525a51.png" class="d-block mb-2">
    </h6>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      使用方法は、プルダウンと同様
    </div>
    <pre class="copy-target">
      <code>&#123;&#123;Form::dropdown('attr-name', ['group1' => ['1'=>'option1-1', '2'=>'option1-2'], 'group2' => ['3'=>'option2-1', '4'=>'option2-2']], 2, ['id'=>'attr-id-select-grp', 'class'=>'attr-class'])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {{Form::dropdown('attr-name', ['group1' => ['1'=>'option1-1', '2'=>'option1-2'], 'group2' => ['3'=>'option2-1', '4'=>'option2-2']], 2, ['id'=>'attr-id-select-grp', 'class'=>'attr-class'])}}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;select id=&quot;attr-id-select-grp&quot; class=&quot;form-control attr-class&quot; name=&quot;attr-name&quot; data-parsley-id=&quot;54&quot;&gt;
  &lt;optgroup label=&quot;group1&quot;&gt;
    &lt;option value=&quot;1&quot;&gt;option1-1&lt;/option&gt;
    &lt;option value=&quot;2&quot; selected=&quot;selected&quot;&gt;option1-2&lt;/option&gt;
  &lt;/optgroup&gt;
  &lt;optgroup label=&quot;group2&quot;&gt;
    &lt;option value=&quot;3&quot;&gt;option2-1&lt;/option&gt;
    &lt;option value=&quot;4&quot;&gt;option2-2&lt;/option&gt;
  &lt;/optgroup&gt;
&lt;/select&gt;
      </code>
    </pre>
  </div>
</div>
<div class="card" id="select-filter">
  <div class="card-header">
    <h4>プルダウン（フィルター）</h4>
  </div>
  <div class="card-body">
    <h5>
      プルダウン（フィルター）の入力項目に対するフォームヘルパー
    </h5>
    <h6>
      画面項目定義（<code>要入れ替え</code>）
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/ee9b9b8b-0cb2-932f-cb15-7552b8525a51.png" class="d-block mb-2">
    </h6>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      使用方法は、プルダウンと同様<br>
      <pre style="height: 120px;">
        <code>&lt;script&gt;
  $(selector).select2();
&lt;/script&gt;
        </code>
      </pre>
    </div>
    <pre class="copy-target">
      <code>&#123;&#123;Form::dropdown('attr-name', ['1'=>'option1', '2'=>'option2', '3'=>'option3'], 2, ['id'=>'attr-id-select2', 'class'=>'attr-class'], ['insert-empty' => true, 'empty-label' => '選んで'])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {{Form::dropdown('attr-name', ['1'=>'option1', '2'=>'option2', '3'=>'option3'], '2', ['id'=>'attr-id-select2', 'class'=>'attr-class'], ['insert-empty' => true, 'empty-label' => '選んで'])}}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre style="height: 290px;">
      <code>&lt;select id=&quot;attr-id-select2&quot; class=&quot;form-control attr-class select2-hidden-accessible&quot; name=&quot;attr-name&quot; data-select2-id=&quot;select2-data-attr-id-select2&quot; tabindex=&quot;-1&quot; aria-hidden=&quot;true&quot;&gt;
  &lt;option value=&quot;&quot; data-select2-id=&quot;select2-data-8-yzdr&quot;&gt;選んで&lt;/option&gt;
  &lt;option value=&quot;1&quot; data-select2-id=&quot;select2-data-9-onwx&quot;&gt;option1&lt;/option&gt;
  &lt;option value=&quot;2&quot; selected=&quot;selected&quot; data-select2-id=&quot;select2-data-2-1w28&quot;&gt;option2&lt;/option&gt;
  &lt;option value=&quot;3&quot; data-select2-id=&quot;select2-data-10-a8u2&quot;&gt;option3&lt;/option&gt;
&lt;/select&gt;
      </code>
    </pre>
  </div>
</div>
@push('app-script')
<script>
  $(function() {
    $('#attr-id-select2').select2();
  });
</script>
@endpush