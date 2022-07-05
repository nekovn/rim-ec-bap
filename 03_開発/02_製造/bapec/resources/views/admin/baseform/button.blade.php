<div class="side-menu">
  <ul>
    <li>
      <a href="#">Top</a>
    </li>
    <li>
      <a href="#button">ボタン</a>
    </li>
  </ul>
</div>
<div class="card" id="button">
  <div class="card-header">
    <strong>画面設計書：ボタン</strong>
  </div>
  <div class="card-body">
    <h5>
      ボタンの宣言方法
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/d43c279a-4266-cd9c-8904-8abf0d3c36d8.png" class="d-block mb-2">
    </h6>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <pre class="copy-target" style="height: 200px;">
      <code>&#123;&#123Form::button(['id'=>'attr-id', 'class'=>'attr-class'])&#125;&#125;
&#123;&#123Form::iconButton(['id'=>'attr-id', 'class'=>'attr-class'], 'fontawesome-icon-name', 'caption')&#125;&#125;
&#123;&#123Form::searchButton(['id'=>'attr-id', 'class'=>'attr-class'])&#125;&#125;
&#123;&#123Form::clearButton(['id'=>'attr-id', 'class'=>'attr-class'])&#125;&#125;
&#123;&#123Form::backButton(['id'=>'attr-id', 'class'=>'attr-class'])&#125;&#125;
&#123;&#123Form::createButton(['id'=>'attr-id', 'class'=>'attr-class'])&#125;&#125;
&#123;&#123Form::updateButton(['id'=>'attr-id', 'class'=>'attr-class'])&#125;&#125;
&#123;&#123Form::dispSearchButton(['id'=>'attr-id', 'class'=>'attr-class'])&#125;&#125;
</code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      <table class="table table-borderd">
        <tr>
          <td>
            {{Form::labelEx('attr-for', '標準ボタン', ['class'=>'attr-class'])}}
          </td>
          <td>
            {{Form::button('ボタン', ['id'=>'attr-id-standard', 'class'=>'btn btn-outline-info attr-class'])}}
          </td>
        </tr>
        <tr>
          <td>
            {{Form::labelEx('attr-for', 'アイコンボタン', ['class'=>'attr-class'])}}
          </td>
          <td>
            {{Form::iconButton(['id'=>'attr-id-icon', 'class'=>'btn btn-secondary attr-class'], 'far fa-address-card', 'icon')}}
          </td>
        </tr>
        <tr>
          <td>
            {{Form::labelEx('attr-for', '検索ボタン', ['class'=>'attr-class'])}}
          </td>
          <td>
            {{Form::searchButton(['id'=>'attr-id-search', 'class'=>'attr-class'])}}
          </td>
        </tr>
        <tr>
          <td>
            {{Form::labelEx('attr-for', 'クリアボタン', ['class'=>'attr-class'])}}
          </td>
          <td>
            {{Form::clearButton(['id'=>'attr-id-clear', 'class'=>'attr-class'])}}
          </td>
        </tr>
        <tr>
          <td>
            {{Form::labelEx('attr-for', '戻るボタン', ['class'=>'attr-class'])}}
          </td>
          <td>
            {{Form::backButton(['id'=>'attr-id-back', 'class'=>'attr-class'])}}
          </td>
        </tr>
        <tr>
          <td>
            {{Form::labelEx('attr-for', '新規登録ボタン', ['class'=>'attr-class'])}}
          </td>
          <td>
            {{Form::createButton(['id'=>'attr-id-create', 'class'=>'attr-class'])}}
          </td>
        </tr>
        <tr>
          <td>
            {{Form::labelEx('attr-for', '登録ボタン', ['class'=>'attr-class'])}}
          </td>
          <td>
            {{Form::storeButton(['id'=>'attr-id-store', 'class'=>'attr-class'])}}
          </td>
        </tr>
        <tr>
          <td>
            {{Form::labelEx('attr-for', '更新ボタン', ['class'=>'attr-class'])}}
          </td>
          <td>
            {{Form::updateButton(['id'=>'attr-id-update', 'class'=>'attr-class'])}}
          </td>
        </tr>
        <tr>
          <td>
            {{Form::labelEx('attr-for', '削除ボタン', ['class'=>'attr-class'])}}
          </td>
          <td>
            {{Form::deleteButton(['id'=>'attr-id-delete', 'class'=>'attr-class'])}}
          </td>
        </tr>
        <tr>
          <td>
            {{Form::labelEx('attr-for', '検索画面表示ボタン', ['class'=>'attr-class'])}}
          </td>
          <td>
            {{Form::dispSearchButton(['id'=>'attr-id-child', 'class'=>'attr-class'])}}
          </td>
        </tr>
      </table>
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
        <code>&lt;input id=&quot;attr-id&quot; class=&quot;attr-class&quot; maxlength=&quot;10&quot; name=&quot;attr-name&quot; type=&quot;text&quot; data-parsley-id=&quot;41&quot;&gt;
      </code>
    </pre>
  </div>
</div>