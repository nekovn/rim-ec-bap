<div class="side-menu">
  <ul>
    <li>
      <a href="#">Top</a>
    </li>
    <li>
      <a href="#label">ラベル</a>
    </li>
    <li>
      <a href="#label-required">ラベル（必須）</a>
    </li>
  </ul>
</div>

<div class="card" id="label">
  <div class="card-header">
    <h4>label（任意）</h4>
  </div>
  <div class="card-body">
    <h5>
      入力項目に対するラベルタグの作成を行うフォームヘルパー
    </h5>
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
            <td>for属性に当たる文字列（入力項目のid属性と一致する）</td>
          </tr>
          <tr>
            <td>2</td>
            <td>表示する文字列</td>
          </tr>
          <tr>
            <td>3</td>
            <td>
              labelタグの属性設定を配列で定義
              @include('admin.baseform.attr-sample')
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <pre class="copy-target">
      <code>&#123;&#123;Form::labelEx('attr-for', 'ラベル', ['class'=>'attr-class'])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {{Form::labelEx('attr-for', 'ラベル', ['class'=>'attr-class'])}}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;label for=&quot;attr-for&quot; class=&quot;attr-class&quot;&gt;ラベル&lt;/label&gt;
      </code>
    </pre>
  </div>
</div>
<div class="card" id="label-required">
  <div class="card-header">
    <h4>label（必須）</h4>
  </div>
  <div class="card-body">
    <h5>
      必須項目に対するラベルタグの作成を行うフォームヘルパー<br>
      class属性に`required`を指定することで、必須バッチが表示される。
    </h5>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      使用方法は、label（任意）と同様
    </div>
    <pre class="copy-target">
      <code>&#123;&#123;Form::labelEx('attr-for', 'ラベル', ['class'=>'required attr-class'])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div>
      {{Form::labelEx('attr-for', 'ラベル', ['class'=>'required attr-class'])}}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;label for=&quot;attr-for&quot; class=&quot;attr-class&quot;&gt;ラベル&lt;/label&gt;
      </code>
    </pre>
  </div>
</div>
