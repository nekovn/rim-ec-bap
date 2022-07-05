<div class="side-menu">
  <ul>
    <li>
      <a href="#">Top</a>
    </li>
    <li>
      <a href="#input-type-date">日付</a>
    </li>
    <li>
      <a href="#month-day">月日</a>
    </li>
    <li>
      <a href="#input-type-time">時刻</a>
    </li>
    <li>
      <a href="#datepicker">datepicker</a>
    </li>
    <li>
      <a href="#monthpicker">monthpicker</a>
    </li>
    <li>
      <a href="#timepicker">timepicker</a>
    </li>
    <li>
      <a href="#datetimepicker">datetimepicker</a>
    </li>
    <li>
      <a href="#daterangepicker">daterangepicker</a>
    </li>
  </ul>
</div>
<div class="card" id="input-type-date">
  <div class="card-header">
    <h4>画面設計書：日時：年月日</h4>
  </div>
  <div class="card-body">
    <h5>
      ブラウザ依存の日付項目に対するフォームヘルパー
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/1bbea6c1-81a0-b072-8583-5f6c8b65ba00.png" class="d-block mb-2">
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
      <code>&#123;&#123Form::dateEx('attr-name', null, ['id'=>'attr-id-date-ex', 'class'=>'attr-class'])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {!!Form::dateEx('attr-name', null, ['id'=>'attr-id-date-ex', 'class'=>'attr-class'])!!}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;input id=&quot;attr-id-date-ex&quot; class=&quot;attr-class&quot; name=&quot;attr-name&quot; type=&quot;date&quot; data-parsley-id=&quot;29&quot;&gt;
      </code>
    </pre>
  </div>
</div>
<div class="card" id="month-day">
  <div class="card-header">
    <h4>画面設計書：日時：月日</h4>
  </div>
  <div class="card-body">
    <h5>
      日付項目に、flatpickerのカレンダーを適用するフォームヘルパー
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/6902f2f7-038d-a862-128e-6bd5b87f27f3.png" class="d-block mb-2">
    </h6>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      共通部品なし
    </div>
    <pre class="copy-target">
      <code>inputタグで頑張る
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      inputタグで頑張る
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre style="height: 160px;">
      <code>inputタグで頑張る
      </code>
    </pre>
  </div>
</div>
<div class="card" id="input-type-time">
  <div class="card-header">
    <h4>画面設計書：日時：時分</h4>
  </div>
  <div class="card-body">
    <h5>
      ブラウザ依存の時刻項目に対するフォームヘルパー
    </h5>
    <h6>
      画面項目定義
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/65ff696a-f92d-3090-227d-6ca5771e20f1.png" class="d-block mb-2">
    </h6>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      使用方法は、日時：年月日と同様
    </div>
    <pre class="copy-target">
      <code>&#123;&#123Form::timeEx('attr-name', null, ['id'=>'attr-id-time-ex', 'class'=>'attr-class'])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {!!Form::timeEx('attr-name', null, ['id'=>'attr-id-time-ex', 'class'=>'attr-class'])!!}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre>
      <code>&lt;input id=&quot;attr-id-time-ex&quot; class=&quot;attr-class&quot; name=&quot;attr-name&quot; type=&quot;time&quot; data-parsley-id=&quot;29&quot;&gt;
      </code>
    </pre>
  </div>
</div>
<div class="card" id="datepicker">
  <div class="card-header">
    <h4>画面設計書：日時：datepicker</h4>
  </div>
  <div class="card-body">
    <h5>
      日付項目に、flatpickerのカレンダーを適用するフォームヘルパー
    </h5>
    <h6>
      画面項目定義<br>
      <code>要調整</code>
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/1bbea6c1-81a0-b072-8583-5f6c8b65ba00.png" class="d-block mb-2">
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
              カレンダーアイコンクラス設定（カレンダーを表示する場合のみ有効）<br>
              未指定の場合、<code>far fa-calendar</code><i class="far fa-calendar ml-2"></i>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <pre class="copy-target">
      <code>&#123;!!Form::datePicker('attr-name', null, ['id'=>'attr-id-datepicker', 'class'=>'attr-class'])!!&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {!!Form::datePicker('attr-name', null, ['id'=>'attr-id-datepicker', 'class'=>'attr-class'])!!}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre style="height: 160px;">
      <code>&lt;div class=&quot;input-group-append calendar-wrapper&quot;&gt;
  &lt;input id=&quot;attr-id-datepicker&quot; class=&quot;form-control clearable w-date attr-class flatpickr-input&quot; readonly=&quot;&quot; data-input=&quot;&quot; name=&quot;attr-name&quot; type=&quot;text&quot;&gt;
  &lt;a class=&quot;input-group-text input-button&quot; data-toggle=&quot;&quot;&gt;
    &lt;i class=&quot;far fa-calendar&quot;&gt;&lt;/i&gt;
  &lt;/a&gt;
&lt;/div&gt;
</code>
    </pre>
  </div>
</div>
<div class="card" id="monthpicker">
  <div class="card-header">
    <h4>画面設計書：日時：年月</h4>
  </div>
  <div class="card-body">
    <h5>
      日付項目に、flatpickerのカレンダーを適用するフォームヘルパー
    </h5>
    <h6>
      画面項目定義<br>
      <code>要調整</code>
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/f30b5203-d2b8-abdc-0ca4-d9125a599ac7.png" class="d-block mb-2">
    </h6>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      使用方法は、datepickerと同様
    </div>
    <pre class="copy-target">
      <code>&#123;&#123Form::monthPicker('attr-name', null, ['id'=>'attr-id-monthpicker', 'class'=>'attr-class'])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {!!Form::monthPicker('attr-name', null, ['id'=>'attr-id-monthpicker', 'class'=>'attr-class'])!!}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre style="height: 160px;">
      <code>&lt;div class=&quot;input-group-append monthpicker-wrapper flatpickr-input&quot; readonly=&quot;readonly&quot;&gt;
  &lt;input id=&quot;attr-id-monthpicker&quot; class=&quot;form-control clearable w-month attr-class&quot; readonly=&quot;&quot; data-input=&quot;&quot; name=&quot;attr-name&quot; type=&quot;text&quot; data-parsley-id=&quot;45&quot;&gt;
  &lt;a class=&quot;input-group-text input-button&quot; data-toggle=&quot;&quot;&gt;
    &lt;i class=&quot;far fa-calendar&quot;&gt;&lt;/i&gt;
  &lt;/a&gt;
&lt;/div&gt;
</code>
    </pre>
  </div>
</div>
<div class="card" id="timepicker">
  <div class="card-header">
    <h4>画面設計書：日時：時分</h4>
  </div>
  <div class="card-body">
    <h5>
      日付項目に、flatpickerのカレンダーを適用するフォームヘルパー
    </h5>
    <h6>
      画面項目定義<br>
      <code>要調整</code>
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/65ff696a-f92d-3090-227d-6ca5771e20f1.png" class="d-block mb-2">
    </h6>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      使用方法は、datepickerと同様
    </div>
    <pre class="copy-target">
      <code>&#123;&#123Form::timePicker('attr-name', null, ['id'=>'attr-id-timepicker', 'class'=>'attr-class'])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {!!Form::timePicker('attr-name', null, ['id'=>'attr-id-timepicker', 'class'=>'attr-class'])!!}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre style="height: 160px;">
      <code>&lt;div class=&quot;input-group-append timepicker-wrapper flatpickr-input&quot; readonly=&quot;readonly&quot;&gt;
  &lt;input id=&quot;attr-id-timepicker&quot; class=&quot;form-control clearable w-time attr-class flatpickr-input active&quot; readonly=&quot;readonly&quot; data-input=&quot;&quot; name=&quot;attr-name&quot; type=&quot;text&quot; data-parsley-id=&quot;47&quot;&gt;
  &lt;a class=&quot;input-group-text input-button&quot; data-toggle=&quot;&quot;&gt;
    &lt;i class=&quot;far fa-clock&quot;&gt;&lt;/i&gt;
  &lt;/a&gt;
&lt;/div&gt;
</code>
    </pre>
  </div>
</div>
<div class="card" id="datetimepicker">
  <div class="card-header">
    <h4>画面設計書：日時：年月日時分</h4>
  </div>
  <div class="card-body">
    <h5>
      日付項目に、flatpickerのカレンダーを適用するフォームヘルパー
    </h5>
    <h6>
      画面項目定義<br>
      <code>要調整</code>
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/310038fe-fdfe-6641-7a9c-86fc2dcb6c79.png" class="d-block mb-2">
    </h6>
    <hr>
    <h6>
      サンプルコード
    </h6>
    <a href="#" class="copy" title="コピー">
      <i class="far fa-copy"></i>
    </a>
    <div>
      使用方法は、datepickerと同様
    </div>
    <pre class="copy-target">
      <code>&#123;&#123Form::dateTimePicker('attr-name', null, ['id'=>'attr-id-datetimepicker', 'class'=>'attr-class'])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {!!Form::dateTimePicker('attr-name', null, ['id'=>'attr-id-datetimepicker', 'class'=>'attr-class'])!!}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre style="height: 160px;">
      <code>&lt;div class=&quot;input-group-append datetime-wrapper&quot;&gt;
  &lt;input id=&quot;attr-id-datetimepicker&quot; class=&quot;form-control clearable w-date-time attr-class flatpickr-input active&quot; readonly=&quot;&quot; data-input=&quot;&quot; name=&quot;attr-name&quot; type=&quot;text&quot; data-parsley-id=&quot;49&quot;&gt;
  &lt;a class=&quot;input-group-text input-button&quot; data-toggle=&quot;&quot;&gt;
    &lt;i class=&quot;far fa-calendar&quot;&gt;&lt;/i&gt;
  &lt;/a&gt;
&lt;/div&gt;
</code>
    </pre>
  </div>
</div>
<div class="card" id="daterangepicker">
  <div class="card-header">
    <h4>画面設計書：日時：期間</h4>
  </div>
  <div class="card-body">
    <h5>
      日付項目に、flatpickerのカレンダーを適用するフォームヘルパー
    </h5>
    <h6>
      画面項目定義<br>
      <code>要調整</code>
      <img src="https://qiita-image-store.s3.ap-northeast-1.amazonaws.com/0/459867/ecd4b65b-1db9-140a-bb3d-2b4c23f3328e.png" class="d-block mb-2">
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
              to属性の属性を配列で設定<br>
              id属性は必須。<code>['id' => 'to-id']</code>
            </td>
          </tr>
        </tbody>
      </table>
      <span class="caption">※第４引数に、id属性は必須</span>
    </div>
    <pre class="copy-target">
      <code>&#123;&#123Form::dateRangePicker('attr-name', null, ['id'=>'attr-id-daterangepicker', 'class'=>'attr-class'], ['name'=>'to-attr-name', 'default-value'=>null, 'attr'=>['id'=>'to-date', 'class'=>'to-attr-class']])&#125;&#125;
      </code>
    </pre>
    <h6>
      実行結果
    </h6>
    <div class="result-html">
      {!!Form::dateRangePicker('attr-name', null, ['id'=>'attr-id-daterangepicker', 'class'=>'attr-class'],
                                                 ['name'=>'to-attr-name', 'default-value'=>null, 'attr'=>['id'=>'to-date', 'class'=>'to-attr-class']])!!}
    </div>
    <h6>
      出力されるhtml
    </h6>
    <pre style="height: 350px;">
      <code>&lt;div class=&quot;daterange-wrapper&quot;&gt;
  &lt;div class=&quot;input-group-append datepicker-wrapper&quot;&gt;
    &lt;input id=&quot;attr-id-daterangepicker&quot; class=&quot;form-control clearable w-date fp-range attr-class flatpickr-input active&quot; data-to-id=&quot;#to-date&quot; readonly=&quot;readonly&quot; data-input=&quot;&quot; name=&quot;attr-name&quot; type=&quot;text&quot; data-parsley-id=&quot;51&quot;&gt;
    &lt;a class=&quot;input-group-text input-button&quot; data-toggle=&quot;&quot;&gt;
      &lt;i class=&quot;far fa-calendar&quot;&gt;&lt;/i&gt;
    &lt;/a&gt;
  &lt;/div&gt;
  &lt;span class=&quot;separator-period&quot;&gt;～&lt;/span&gt;
  &lt;div class=&quot;input-group-append datepicker-wrapper&quot;&gt;
    &lt;input id=&quot;to-date&quot; class=&quot;form-control clearable w-date fp-range to-attr-class flatpickr-input&quot; readonly=&quot;readonly&quot; data-input=&quot;&quot; name=&quot;to-attr-name&quot; type=&quot;text&quot; data-fp-omit=&quot;&quot;&gt;
      &lt;a class=&quot;input-group-text input-button&quot; data-toggle=&quot;&quot;&gt;
        &lt;i class=&quot;far fa-calendar&quot;&gt;&lt;/i&gt;
      &lt;/a&gt;
  &lt;/div&gt;
&lt;/div&gt;
</code>
    </pre>
  </div>
</div>
@push('app-script')
<script>
  function bindCalendar(selector, settings) {
    // カレンダーロケール設定
    let config = {
      allowInput: true,
      locale: fw.config.locale,
      dateFormat: 'Y-m-d',
      disableMobile: true,
      wrap: fw.config.page.supportParts.date.datepicker['use-calendar-icon'],
      clickOpens: true,
      onChange: function (selectedDates, dateStr, instance) {
        if (fw.config.page.supportParts.date.datepicker['use-calendar-icon']) {
          $(instance.element).find('input').val(dateStr).parsley().validate();
          $(instance.element).find('input').trigger('change');
        }
      }
    };
    if (settings) {
      config = Object.assign(config, settings);
    }

    let flatpickrObject;
    if (fw.config.page.supportParts.date.datepicker['use-calendar-icon']) {
      flatpickrObject = $('.datepicker-wrapper').flatpickr(config);
    } else {
      flatpickrObject = $(selector).flatpickr(config);
    }
    // Enterキー押下で、カレンダーを閉じる
    $(selector).on('keydown', (e) => {
      if (e.keyCode === fw.KeyEvent.DOM_VK_RETURN || e.keyCode === fw.KeyEvent.DOM_VK_TAB) {
        flatpickrObject.setDate(this.value);
        flatpickrObject.close();
      } else if (e.keyCode === fw.KeyEvent.DOM_VK_BACK_SPACE || e.keyCode === fw.KeyEvent.DOM_VK_DELETE) {
        e.target.value = '';
      }
    });
  }
  function bindMonthCalendar(selector, settings) {
    let config = {
      allowInput: false,
      locale: fw.config.locale,
      plugins: [
        new monthSelectPlugin({
          shorthand: true, //defaults to false
          dateFormat: 'Y/m', //defaults to "F Y"
          altFormat: 'F Y', //defaults to "F Y"
          theme: 'light' // defaults to "light"
        })
      ],
      onChange: function (selectedDates, dateStr, instance) {
        if (fw.config.page.supportParts.date.monthpicker['use-calendar-icon']) {
          $(instance.element).find('input').val(dateStr);
        }
      }
    };
    if (settings) {
      config = Object.assign(config, settings);
    }
    let flatpickrObject;
    if (fw.config.page.supportParts.date.monthpicker['use-calendar-icon']) {
      flatpickrObject = $('.monthpicker-wrapper').flatpickr(config);
    } else {
      flatpickrObject = $(selector).flatpickr(config);
    }
    // Enterキー押下で、カレンダーを閉じる
    $(selector).on('keydown', (e) => {
      if (e.keyCode == fw.KeyEvent.DOM_VK_RETURN || e.keyCode == fw.KeyEvent.DOM_VK_TAB) {
        flatpickrObject.setDate(this.value);
        flatpickrObject.close();
      }
    });
  }
  function bindTimepicker(selector, defaultDate) {
    let config = {
      enableTime: true,
      noCalendar: true,
      dateFormat: 'H:i',
      time_24hr: true,
      onValueUpdate(selectedDates, dateStr, instance) {
        let target;
        if (fw.config.page.supportParts.date.timepicker['use-calendar-icon']) {
          if ($(instance.element).is('input')) {
            target = $(instance.element);
          } else {
            target = $(instance.element).find('input');
          }
          target.val(dateStr);
        } else {
          target = $(instance.element);
        }
        if (target.hasClass('tooltipstered')) {
          target.tooltipster('destroy');
        }
        if (typeof target.parsley().reset() == 'function') {
          target.parsley().reset();
        }
        target.removeAttr('title');
      }
    };
    if (defaultDate) {
      config['defaultDate'] = defaultDate;
    }
    if (fw.config.page.supportParts.date.timepicker['use-calendar-icon']) {
      $('.timepicker-wrapper').flatpickr(config);
    } else {
      $(selector).flatpickr(config);
    }

    $(selector).flatpickr(config);
  }
  function bindDateTimePicker(selector, defaultDate) {
    let config = {
      enableTime: true,
      allowInput: true,
      locale: fw.config.locale,
      dateFormat: 'Y-m-d H:i',
      disableMobile: true,
      wrap: true
    };
    if (defaultDate) {
      config['defaultDate'] = defaultDate;
    }
    if (fw.config.page.supportParts.date.datetimepicker['use-calendar-icon']) {
      $('.datetime-wrapper').flatpickr(config);
    } else {
      $(selector).flatpickr(config);
    }
  }
  function bindRangeCalendar(selector, settings) {
    $(selector).each((index, element) => {
      const toElement = $(element).data('to-id');
      let config = {
        allowInput: false,
        locale: fw.config.locale,
        plugins: [new rangePlugin({input: toElement})],
        onChange: function (selectedDates, dateStr, instance) {
          if (fw.config.page.supportParts.date.datepicker['use-calendar-icon']) {
            $(instance.element).find('input').val(dateStr);
          }
        }
      };
      if (settings) {
        config = Object.assign(config, settings);
      }
      let flatpickrObject;
      if (fw.config.page.supportParts.date.datepicker['use-calendar-icon']) {
        flatpickrObject = $('.fp-range').flatpickr(config);
      } else {
        flatpickrObject = $(element).flatpickr(config);
      }
      // Enterキー押下で、カレンダーを閉じる
      $(selector).on('keydown', (e) => {
        if (e.keyCode == fw.KeyEvent.DOM_VK_RETURN || e.keyCode == fw.KeyEvent.DOM_VK_TAB) {
          flatpickrObject.setDate(this.value);
          flatpickrObject.close();
        }
      });
    });
  }
  $(function() {
    // bindCalendar('#attr-id-datepicker');
    // bindMonthCalendar('#attr-id-monthpicker');
    // bindTimepicker('#attr-id-timepicker');
    // bindDateTimePicker('#attr-id-datetimepicker');
    // bindRangeCalendar('#attr-id-daterangepicker');
  });
</script>
@endpush