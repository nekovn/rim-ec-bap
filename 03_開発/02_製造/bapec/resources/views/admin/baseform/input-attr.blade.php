<div class="side-menu">
  <ul>
    <li>
      <a href="#">Top</a>
    </li>
    <li>
      <a href="#digits">桁数</a>
    </li>
    <li>
      <a href="#required">必須</a>
    </li>
    <li>
      <a href="#align">寄せ</a>
    </li>
    <li>
      <a href="#min">最小値</a>
    </li>
    <li>
      <a href="#max">最大値</a>
    </li>
    <li>
      <a href="#minlength">最小桁数</a>
    </li>
    <li>
      <a href="#maxlength">最大桁数</a>
    </li>
  </ul>
</div>
<div class="card" id="digits">
  <div class="card-header">
    <h4>画面設計書：桁数</h4>
  </div>
  <div class="card-body">
    <h5>
      入力項目の<code>桁数</code>の指定方法<br>
      削除予定
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/3669a413-3564-250b-ceb7-8fac30138670.png" class="d-block mb-2">
    </h6>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      第３引数に<code>maxlength</code>を指定する。
    </div>
    <pre class="copy-target">
      <code>&#123;&#123Form::textEx('attr-name', null, ['id'=>'attr-id-maxlength', 'class'=>'attr-class', 'maxlength'=>10])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {{Form::textEx('attr-name', null, ['id'=>'attr-id-maxlength', 'class'=>'attr-class', 'maxlength'=>10])}}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;input id=&quot;attr-id-maxlength&quot; class=&quot;attr-class&quot; maxlength=&quot;10&quot; name=&quot;attr-name&quot; type=&quot;text&quot; data-parsley-id=&quot;41&quot;&gt;
      </code>
    </pre>
  </div>
</div>
<div class="card" id="required">
  <div class="card-header">
    <h4>画面設計書：必須</h4>
  </div>
  <div class="card-body">
    <h5>
      入力項目の<code>必須</code>の指定方法<br>
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/b62bdff3-b36d-741d-72f3-dab95ae9f155.png" class="d-block mb-2">
    </h6>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      第３引数に<code>required</code>を指定する。<br>
      Labelタグと合わせて使用することが多い。
    </div>
    <pre class="copy-target">
      <code>&#123;&#123Form::labelEx('attr-id-required', 'ラベル', ['class'=>'attr-class required'])&#125;&#125;
&#123;&#123Form::textEx('attr-name', null, ['id'=>'attr-id-required', 'class'=>'attr-class', 'required'=>'required'])&#125;&#125;
</code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {{Form::labelEx('attr-id-required', 'ラベル', ['class'=>'attr-class required'])}}
      {{Form::textEx('attr-name', null, ['id'=>'attr-id-required', 'class'=>'attr-class', 'required'=>'required'])}}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;label for=&quot;attr-id-required&quot; class=&quot;attr-class required&quot;&gt;ラベル&lt;/label&gt;<br>
&lt;input id=&quot;attr-id-required&quot; class=&quot;attr-class&quot; required=&quot;required&quot; name=&quot;attr-name&quot; type=&quot;text&quot;&gt;
      </code>
    </pre>
  </div>
</div>
<div class="card" id="align">
  <div class="card-header">
    <h4>画面設計書：寄せ</h4>
  </div>
  <div class="card-body">
    <h5>
      入力項目の<code>寄せ</code>の指定方法<br>
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/d4b7a037-d619-c7bf-85e7-6384808177da.png" class="d-block mb-2">
    </h6>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      第３引数に<code>text-center / text-right</code>を指定する。<br>
      <strong>左寄せ： 未指定</strong><br>
      <strong>中央寄せ： text-center</strong><br>
      <strong>右寄せ： text-right <code>※数値タイプの場合、右寄せの指定は不要</code></strong>
    </div>
    <pre>
      <code>&#123;&#123Form::textEx('attr-name', null, ['id'=>'attr-id-text-center', 'class'=>'attr-class text-center'])&#125;&#125;
&#123;&#123Form::textEx('attr-name', null, ['id'=>'attr-id-text-right', 'class'=>'attr-class text-right'])&#125;&#125;
</code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      <div>
        {{Form::textEx('attr-name', null, ['id'=>'attr-id-center', 'class'=>'attr-class text-center'])}}
      </div>
      <div>
        {{Form::textEx('attr-name', null, ['id'=>'attr-id-right', 'class'=>'attr-class text-right'])}}
      </div>
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;input id=&quot;attr-id-center&quot; class=&quot;attr-class text-center&quot; name=&quot;attr-name&quot; type=&quot;text&quot; data-parsley-id=&quot;45&quot;&gt;
&lt;input id=&quot;attr-id-right&quot; class=&quot;attr-class text-right&quot; name=&quot;attr-name&quot; type=&quot;text&quot; data-parsley-id=&quot;45&quot;&gt;
</code>
    </pre>
  </div>
</div>
<div class="card" id="min">
  <div class="card-header">
    <h4>画面設計書：最小値</h4>
  </div>
  <div class="card-body">
    <h5>
      入力項目の<code>最小値</code>の指定方法<br>
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/26abf2b6-04db-bf51-f9c2-73b2864a9aea.png" class="d-block mb-2">
    </h6>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      第３引数に<code>min</code>を指定する。
    </div>
    <pre class="copy-target">
      <code>&#123;&#123Form::textEx('attr-name', null, ['id'=>'attr-id-min', 'class'=>'attr-class', 'min'=>1])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {{Form::textEx('attr-name', null, ['id'=>'attr-id-min', 'class'=>'attr-class', 'min'=>1])}}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;input id=&quot;attr-id-min&quot; class=&quot;attr-class&quot; min=&quot;1&quot; name=&quot;attr-name&quot; type=&quot;text&quot; data-parsley-id=&quot;47&quot;&gt;
      </code>
    </pre>
  </div>
</div>
<div class="card" id="max">
  <div class="card-header">
    <h4>画面設計書：最大値</h4>
  </div>
  <div class="card-body">
    <h5>
      入力項目の<code>最大値</code>の指定方法<br>
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/932767c9-7a5f-4c6c-8f63-e2b619507180.png" class="d-block mb-2">
    </h6>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      第３引数に<code>max</code>を指定する。
    </div>
    <pre class="copy-target">
      <code>&#123;&#123Form::textEx('attr-name', null, ['id'=>'attr-id-max', 'class'=>'attr-class', 'max'=>5])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {{Form::textEx('attr-name', null, ['id'=>'attr-id-max', 'class'=>'attr-class', 'max'=>5])}}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;input id=&quot;attr-id-max&quot; class=&quot;attr-class&quot; max=&quot;5&quot; name=&quot;attr-name&quot; type=&quot;text&quot; data-parsley-id=&quot;49&quot;&gt;
      </code>
    </pre>
  </div>
</div>
<div class="card" id="minlength">
  <div class="card-header">
    <h4>画面設計書：最小桁</h4>
  </div>
  <div class="card-body">
    <h5>
      入力項目の<code>最小桁</code>の指定方法<br>
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/932767c9-7a5f-4c6c-8f63-e2b619507180.png" class="d-block mb-2">
    </h6>
    sample code.
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div class="code">
      <code class="code-sample copy-target">
        &#123;&#123Form::textEx('attr-name', null, ['id'=>'attr-id-minlength', 'class'=>'attr-class', 'minlength'=>5])&#125;&#125;
      </code>
      <div>
        <span class="caption">※minlength属性を指定する。</span>
      </div>
    </div>
    result：
    <code class="result-html">
      {{Form::textEx('attr-name', null, ['id'=>'attr-id-minlength', 'class'=>'attr-class', 'minlength'=>5])}}
    </code>
    <div class="code">
      <code class="code-sample">
        &lt;input id=&quot;attr-id-minlength&quot; class=&quot;attr-class&quot; minlength=&quot;5&quot; name=&quot;attr-name&quot; type=&quot;text&quot; data-parsley-id=&quot;51&quot;&gt;
      </code>
    </div>
  </div>
</div>
<div class="card" id="maxlength">
  <div class="card-header">
    <h4>画面設計書：最大桁</h4>
  </div>
  <div class="card-body">
    <h5>
      入力項目の<code>最大桁</code>の指定方法<br>
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/ea422d99-9d19-d711-a94f-01d73aa17922.png" class="d-block mb-2">
    </h6>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      第３引数に<code>minlength</code>を指定する。
    </div>
    <pre class="copy-target">
      <code>&#123;&#123Form::textEx('attr-name', null, ['id'=>'attr-id', 'class'=>'attr-class', 'maxlength'=>5])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {{Form::textEx('attr-name', null, ['id'=>'attr-id', 'class'=>'attr-class', 'maxlength'=>5])}}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;input id=&quot;attr-id&quot; class=&quot;attr-class&quot; maxlength=&quot;5&quot; name=&quot;attr-name&quot; type=&quot;text&quot; data-parsley-id=&quot;53&quot;&gt;
      </code>
    </pre>
  </div>
</div>
