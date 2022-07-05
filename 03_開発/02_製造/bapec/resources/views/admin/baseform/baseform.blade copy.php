<?php
/**
 *  画面コントロールサンプルblade
 *
 */
  $screenName = '画面コントロールサンプル';
  $functionId = 'baseform';
?>
@extends('admin.layouts.app')
@push('app-style')
<style type="text/css">
  <!--
  .code {
    background-color: #1d1f21;
    border: 1px solid #cccccc;
    border-radius: 3px;
    overflow-x: auto;
    text-align: center;
    padding: 10px;
    margin-top: 10px;
    margin-bottom: 10px;
    overflow-wrap: break-word;
  }
  .code-sample {
    color: #c5c8c6;
    margin-bottom: 10px;
  }
  .result-html {
    color: black;
  }
  .copy {
    margin-left: 10px;
  }
  .caption {
    text-align: left;
    color: #c5c8c6;
  }
  -->
</style>
@endpush
@push('app-script')
<script>
  $('.copy').on('click', (e) => {
    e.preventDefault();
    let html = $(e.target).closest('.card-body').find('.copy-target').text();
    html = _.replace(html, '                  ', '\r\n');
    $('#copy-element').val(_.trim(html)).select();
    document.execCommand('copy');

    $.toast({
      text: 'コピーしました。',
      showHideTransition: 'fade',
      hideAfter: 3000,
      position: 'top-center',
      stack: 0,
      bgColor: '#d5f1de',
      textColor: '#18603a',
      icon: 'success',
      loader: false
    });
  });
  $(function() {
    $('#form').parsley({
      errorsContainer: (ParsleyField) => {
        return ParsleyField.$element.attr('title');
      },
      errorsWrapper: false,
      // チェックタイミング設定
      trigger: 'keyup focusout change'
    });

    const flatPickrConfig = {
                            allowInput: true,
                            locale: 'ja',
                            dateFormat: 'Y-m-d',
                            disableMobile: "true",
                            wrap: true,
                            clickOpens: false,
                          };
    $('.input-date').flatpickr(flatPickrConfig);
    let monthPickrconfig = {
            allowInput: false,
            locale: 'ja',
            plugins: [
                new monthSelectPlugin({
                    shorthand: true, //defaults to false
                    dateFormat: "Y/m", //defaults to "F Y"
                    altFormat: "F Y", //defaults to "F Y"
                    theme: "light" // defaults to "light"
                })
            ]
        };
    $('.input-month').flatpickr(monthPickrconfig);
    let timePickrconfig = {
                              enableTime: true,
                              noCalendar: true,
                              dateFormat: "H:i",
                              time_24hr: true,
                              onValueUpdate(selectedDates, dateStr, instance) {
                                  /* BcValidator.resetValidate(instance.element.id); */
                              }
                          };
    $('.input-time').flatpickr(timePickrconfig);

    const dateTimePickrConfig = {
                            enableTime: true,
                            allowInput: true,
                            locale: 'ja',
                            dateFormat: 'Y-m-d H:i',
                            disableMobile: "true",
                            /* wrap: true,
                            clickOpens: false, */
                          };
    $('.input-date-time').flatpickr(dateTimePickrConfig);

    let rangePickrconfig = {
                              allowInput: false,
                              locale: 'ja',
                              plugins: [
                                  new rangePlugin({ input: '#to-date' })
                              ]
                            };
    $('.input-range-date').flatpickr(rangePickrconfig);
  });
</script>
@endpush
@section('content')
<div id="baseform" class="container">
  <div class="mb-3">
    <a href="https://coreui.io/demo/3.2.0/" target="_blank">coreui使い方は、こちらを参照</a>
  </div>
  <form id="form">
    <div class="nav-tabs-boxes">
      <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" data-toggle="tab" href="#label" role="tab" aria-controls="label" aria-selected="true">labelタグ</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#input-string" role="tab" aria-controls="input-string" aria-selected="false">inputタグ（文字列）</a></li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#input-number" role="tab" aria-controls="input-number" aria-selected="false">inputタグ（数値）</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#input-date" role="tab" aria-controls="input-date" aria-selected="false">inputタグ（日付）</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#input-other" role="tab" aria-controls="input-date" aria-selected="false">inputタグ（チェックボックス、ラジオボタン、プルダウン）</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#input-attr" role="tab" aria-controls="input-attr" aria-selected="false">inputタグ（属性）</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#button" role="tab" aria-controls="button" aria-selected="false">buttonタグ</a>
        </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane mb-2" id="input-date" role="input-date">



        </div>
        <div class="tab-pane mb-2" id="button" role="button">
        </div>
      </div>
    </div>



    <textarea id="copy-element" class="" placeholder="コピー用"></textarea>
  </form>
@endsection
