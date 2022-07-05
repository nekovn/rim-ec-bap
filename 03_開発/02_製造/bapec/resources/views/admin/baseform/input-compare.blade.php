<div class="side-menu">
  <ul>
    <li>
      <a href="#">Top</a>
    </li>
    <li>
      <a href="#gt-fixed">超過（固定値）</a>
    </li>
    <li>
      <a href="#gt">超過（他項目）</a>
    </li>
    <li>
      <a href="#equalto-fixed">等しい（固定値）</a>
    </li>
    <li>
      <a href="#equalto">等しい（他項目）</a>
    </li>
    <li>
      <a href="#gte-fixed">以上（固定値）</a>
    </li>
    <li>
      <a href="#gte">以上（他項目）</a>
    </li>
    <li>
      <a href="#lte-fixed">以下（固定値）</a>
    </li>
    <li>
      <a href="#lte">以下（他項目）</a>
    </li>
    <li>
      <a href="#lt-fixed">未満（固定値）</a>
    </li>
    <li>
      <a href="#lt">未満（他項目）</a>
    </li>
  </ul>
</div>
<div class="card" id="gt-fixed">
  <div class="card-header">
    <h4>画面設計書：比較符号：超過（固定値）</h4>
  </div>
  <div class="card-body">
    <h5>
      比較符号の<code>超過</code>の指定方法（固定値と比較）
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/5003c4f5-e344-4fb0-6b23-7e38fe42423d.png" class="d-block mb-2">
    </h6>
    <hr>
    <div>
      第３引数に<code>'data-parsley-gt'=>'固定値'</code>を指定する。
    </div>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <pre class="copy-target">
      <code>&#123;&#123Form::textEx('attr-name', null, ['id'=>'attr-id-gt-fixed', 'class'=>'attr-class', 'data-parsley-gt'=>'10'])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {{Form::textEx('attr-name', null, ['id'=>'attr-id-gt-fixed', 'class'=>'attr-class', 'data-parsley-gt'=>'10'])}}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;input id=&quot;attr-id-gt-fixed&quot; class=&quot;attr-class&quot; data-parsley-gt=&quot;10&quot; name=&quot;attr-name&quot; type=&quot;text&quot; data-parsley-id=&quot;67&quot;&gt;
      </code>
    </pre>
  </div>
</div>
<div class="card" id="gt">
  <div class="card-header">
    <h4>画面設計書：比較符号：超過（他項目）</h4>
  </div>
  <div class="card-body">
    <h5>
      比較符号の<code>超過</code>の指定方法（他項目と比較）
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/5003c4f5-e344-4fb0-6b23-7e38fe42423d.png" class="d-block mb-2">
    </h6>
    <hr>
    <div>
      第３引数に<code>'data-parsley-gt'=>'#比較対象セレクター'</code>を指定する。<br>
      比較対象に<code>'data-compate-name'=>'項目名'</code>を指定する。
    </div>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <pre class="copy-target">
      <code>&#123;&#123Form::textEx('attr-name', null, ['id'=>'attr-id-gt', 'class'=>'attr-class', 'data-parsley-gt'=>'#anotherfield-gt'])&#125;&#125;
&#123;&#123Form::textEx('attr-name', null, ['id' => 'anotherfield-gt', 'class'=>'attr-class', 'data-compare-name'=>'比較対象'])&#125;&#125;
</code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {{Form::textEx('attr-name', null, ['id'=>'attr-id-gt', 'class'=>'attr-class', 'data-parsley-gt'=>'#anotherfield-gt'])}}
      {{Form::textEx('attr-name', '10', ['id'=>'anotherfield-gt', 'class'=>'attr-class', 'data-compare-name'=>'比較対象'])}}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;input id=&quot;attr-id-gt&quot; class=&quot;attr-class&quot; data-parsley-gt=&quot;#anotherfield-gt&quot; name=&quot;attr-name&quot; type=&quot;text&quot; data-parsley-id=&quot;67&quot;&gt;
      </code>
    </pre>
  </div>
</div>
<div class="card" id="equalto-fixed">
  <div class="card-header">
    <h4>画面設計書：比較符号：等しい（固定値）</h4>
  </div>
  <div class="card-body">
    <h5>
      比較符号の<code>等しい</code>の指定方法（固定値と比較）
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/0cc6d84c-3ec8-acac-1371-e9e978d295e1.png" class="d-block mb-2">
    </h6>
    <hr>
    <div>
      第３引数に<code>'data-parsley-equalto'=>'固定値'</code>を指定する。
    </div>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <pre class="copy-target">
      <code>&#123;&#123Form::textEx('attr-name', null, ['id'=>'attr-id-equalto-fixed-value', 'class'=>'attr-class', 'data-parsley-equalto'=>'fixed-value'])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {{Form::textEx('attr-name', null, ['id'=>'attr-id-equalto-fixed-value', 'class'=>'attr-class', 'data-parsley-equalto'=>'fixed-value'])}}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;input id=&quot;attr-id-equalto-fixed-value&quot; class=&quot;attr-class&quot; data-parsley-equalto=&quot;fixed-value&quot; name=&quot;attr-name&quot; type=&quot;text&quot; data-parsley-id=&quot;55&quot;&gt;
      </code>
    </pre>
  </div>
</div>
<div class="card" id="equalto">
  <div class="card-header">
    <h4>画面設計書：比較符号：等しい（他項目）</h4>
  </div>
  <div class="card-body">
    <h5>
      比較符号の<code>等しい</code>の指定方法（他項目の値と比較）
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/0cc6d84c-3ec8-acac-1371-e9e978d295e1.png" class="d-block mb-2">
    </h6>
    <hr>
    <div>
      第３引数に<code>'data-parsley-equalto'=>'#比較対象セレクター'</code>を指定する。<br>
      比較対象に<code>'data-compate-name'=>'項目名'</code>を指定する。
    </div>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <pre class="copy-target">
      <code>&#123;&#123Form::textEx('attr-name', null, ['id'=>'attr-id-equalto', 'class'=>'attr-class', 'data-parsley-equalto'=>'#anotherfield-equalto'])&#125;&#125;
&#123;&#123Form::textEx('attr-name', null, ['id' => 'anotherfield-equalto', 'class'=>'attr-class', 'data-compare-name'=>'比較対象'])&#125;&#125;
</code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {{Form::textEx('attr-name', null, ['id'=>'attr-id-equalto', 'class'=>'attr-class', 'data-parsley-equalto'=>'#anotherfield-equalto'])}}
      {{Form::textEx('attr-name', 'compare', ['id'=>'anotherfield-equalto', 'class'=>'attr-class', 'data-compare-name'=>'比較対象'])}}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;input id=&quot;attr-id-equal-to&quot; class=&quot;attr-class&quot; data-parsley-equalto=&quot;#anotherfield-equalto&quot; name=&quot;attr-name&quot; type=&quot;text&quot; data-parsley-id=&quot;55&quot;&gt;
      </code>
    </pre>
  </div>
</div>
<div class="card" id="gte-fixed">
  <div class="card-header">
    <h4>画面設計書：比較符号：以上（固定値）</h4>
  </div>
  <div class="card-body">
    <h5>
      比較符号の<code>以上</code>の指定方法（固定値と比較）
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/552b801c-1c44-a106-6130-3f095515e90a.png" class="d-block mb-2">
    </h6>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      第３引数に<code>'data-parsley-gte'=>'数値'</code>を指定する。
    </div>
    <pre class="copy-target">
      <code>&#123;&#123Form::textEx('attr-name', null, ['id'=>'attr-id-gte-fixed', 'class'=>'attr-class', 'data-parsley-gte'=>'10'])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {{Form::textEx('attr-name', null, ['id'=>'attr-id-gte-fixed', 'class'=>'attr-class', 'data-parsley-gte'=>'10'])}}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;input id=&quot;attr-id-gte-fixed&quot; class=&quot;attr-class&quot; data-parsley-gte=&quot;10&quot; name=&quot;attr-name&quot; type=&quot;text&quot; data-parsley-id=&quot;59&quot;&gt;
      </code>
    </pre>
  </div>
</div>
<div class="card" id="gte">
  <div class="card-header">
    <h4>画面設計書：比較符号：以上（他項目）</h4>
  </div>
  <div class="card-body">
    <h5>
      比較符号の<code>以上</code>の指定方法（他項目の値と比較）
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/552b801c-1c44-a106-6130-3f095515e90a.png" class="d-block mb-2">
    </h6>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      第３引数に<code>'data-parsley-gte'=>'#比較対象セレクター'</code>を指定する。<br>
      比較対象に<code>'data-compate-name'=>'項目名'</code>を指定する。
    </div>
    <pre class="copy-target">
      <code>&#123;&#123Form::textEx('attr-name', null, ['id'=>'attr-id-gte', 'class'=>'attr-class', 'data-parsley-gte'=>'anotherfield-gte'])&#125;&#125;
&#123;&#123Form::textEx('attr-name', null, ['id' => 'anotherfield-gte', 'class'=>'attr-class', 'data-compare-name'=>'比較対象'])&#125;&#125;
</code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {{Form::textEx('attr-name', null, ['id'=>'attr-id-gte', 'class'=>'attr-class', 'data-parsley-gte'=>'#anotherfield-gte'])}}
      {{Form::textEx('attr-name', '10', ['id'=>'anotherfield-gte', 'class'=>'attr-class', 'data-compare-name'=>'比較対象'])}}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;input id=&quot;attr-id-gte&quot; class=&quot;attr-class&quot; data-parsley-gte=&quot;#anotherfield-gte&quot; name=&quot;attr-name&quot; type=&quot;text&quot; data-parsley-id=&quot;59&quot;&gt;
      </code>
    </pre>
  </div>
</div>
<div class="card" id="lte-fixed">
  <div class="card-header">
    <h4>画面設計書：比較符号：以下（固定値）</h4>
  </div>
  <div class="card-body">
    <h5>
      比較符号の<code>以下</code>の指定方法（固定値と比較）
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/7e2dbeae-1442-0d84-e905-0efa693bcbed.png" class="d-block mb-2">
    </h6>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      第３引数に<code>'data-parsley-gte'=>'数値'</code>を指定する。
    </div>
    <pre class="copy-target">
      <code>&#123;&#123Form::textEx('attr-name', null, ['id'=>'attr-id-lte-fixed', 'class'=>'attr-class', 'data-parsley-lte'=>'10'])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {{Form::textEx('attr-name', null, ['id'=>'attr-id-lte-fixed', 'class'=>'attr-class', 'data-parsley-lte'=>'10'])}}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;input id=&quot;attr-id-lte-fixed&quot; class=&quot;attr-class&quot; data-parsley-lte=&quot;10&quot; name=&quot;attr-name&quot; type=&quot;text&quot; data-parsley-id=&quot;63&quot;&gt;
      </code>
    </pre>
  </div>
</div>
<div class="card" id="lte">
  <div class="card-header">
    <h4>画面設計書：比較符号：以下（他項目）</h4>
  </div>
  <div class="card-body">
    <h5>
      比較符号の<code>以下</code>の指定方法（他項目の値と比較）
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/7e2dbeae-1442-0d84-e905-0efa693bcbed.png" class="d-block mb-2">
    </h6>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      第３引数に<code>'data-parsley-lte'=>'#比較対象セレクター'</code>を指定する。<br>
      比較対象に<code>'data-compate-name'=>'項目名'</code>を指定する。
    </div>
    <pre class="copy-target">
      <code>&#123;&#123Form::textEx('attr-name', null, ['id'=>'attr-id-lte', 'class'=>'attr-class', 'data-parsley-lte'=>'anotherfield-lte'])&#125;&#125;
&#123;&#123Form::textEx('attr-name', null, ['id' => 'anotherfield-lte', 'class'=>'attr-class', 'data-compare-name'=>'比較対象'])&#125;&#125;
</code>
    </pre>
    result：
    <code class="result-html">
      {{Form::textEx('attr-name', null, ['id'=>'attr-id-lte', 'class'=>'attr-class', 'data-parsley-lte'=>'#anotherfield-lte'])}}
      {{Form::textEx('attr-name', '10', ['id'=>'anotherfield-lte', 'class'=>'attr-class', 'data-compare-name'=>'比較対象'])}}
    </code>
    <div class="code">
      <code class="code-sample">
        &lt;input id=&quot;attr-id-lte&quot; class=&quot;attr-class&quot; data-parsley-lte=&quot;#anotherfield-lte&quot; name=&quot;attr-name&quot; type=&quot;text&quot; data-parsley-id=&quot;63&quot;&gt;
      </code>
    </div>
  </div>
</div>
<div class="card" id="lt-fixed">
  <div class="card-header">
    <h4>画面設計書：比較符号：未満（固定値）</h4>
  </div>
  <div class="card-body">
    <h5>
      比較符号の<code>未満</code>の指定方法（固定値と比較）
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/1764b143-8c55-9931-c29c-f28c50d0614b.png" class="d-block mb-2">
    </h6>
    <hr>
    <div>
      第３引数に<code>'data-parsley-gt'=>'固定値'</code>を指定する。<br>
    </div>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <pre class="copy-target">
      <code>&#123;&#123Form::textEx('attr-name', null, ['id'=>'attr-id-lt-fixed', 'class'=>'attr-class', 'data-parsley-lt'=>'10'])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {{Form::textEx('attr-name', null, ['id'=>'attr-id-lt-fixed', 'class'=>'attr-class', 'data-parsley-lt'=>'10'])}}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;input id=&quot;attr-id-lt-fixed&quot; class=&quot;attr-class&quot; data-parsley-lt=&quot;10&quot; name=&quot;attr-name&quot; type=&quot;text&quot; data-parsley-id=&quot;71&quot;&gt;
      </code>
    </pre>
  </div>
</div>
<div class="card" id="lt">
  <div class="card-header">
    <h4>画面設計書：比較符号：未満（他項目）</h4>
  </div>
  <div class="card-body">
    <h5>
      比較符号の<code>未満</code>の指定方法（他項目と比較）
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/1764b143-8c55-9931-c29c-f28c50d0614b.png" class="d-block mb-2">
    </h6>
    <hr>
    <div>
      第３引数に<code>'data-parsley-lt'=>'#比較対象セレクター'</code>を指定する。<br>
      比較対象に<code>'data-compate-name'=>'項目名'</code>を指定する。
    </div>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <pre class="copy-target">
      <code>&#123;&#123Form::textEx('attr-name', null, ['id'=>'attr-id-lt', 'class'=>'attr-class', 'data-parsley-gte'=>'anotherfield-lt'])&#125;&#125;
&#123;&#123Form::textEx('attr-name', null, ['id' => 'anotherfield-lt', 'class'=>'attr-class', 'data-compare-name'=>'比較対象'])&#125;&#125;
</code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {{Form::textEx('attr-name', null, ['id'=>'attr-id-lt', 'class'=>'attr-class', 'data-parsley-lt'=>'#anotherfield-lt'])}}
      {{Form::textEx('attr-name', '10', ['id'=>'anotherfield-lt', 'class'=>'attr-class', 'data-compare-name'=>'比較対象'])}}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;input id=&quot;attr-id-lt&quot; class=&quot;attr-class&quot; data-parsley-lt=&quot;#anotherfield-lt&quot; name=&quot;attr-name&quot; type=&quot;text&quot; data-parsley-id=&quot;71&quot;&gt;
      </code>
    </pre>
  </div>
</div>