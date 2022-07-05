@php
/**
 *  画面コントロールサンプルblade
 *
 */
  $screenName = '画面コントロールサンプル';
  $functionId = 'baseform';
@endphp
@extends('admin.layouts.app')
@push('app-style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.18.1/styles/dracula.min.css">
<style type="text/css">
  <!--
  .nav-link.active {
    background-color: #321fdb!important;
    color: white !important;
  }
  .side-menu {
    position: fixed; /* 要素の位置を固定する */
    top: 230px; /* 基準の位置を画面の一番下に指定する */
    right: 0; /* 基準の位置を画面の一番右に指定する */
    background-color: #2982cc;
    color: white;
    z-index: 9999;
    border-radius: 10px 0 0 10px;
    padding: 0.5em;
    width: 140px;
    opacity: 80%;
  }
  .side-menu ul {
    list-style: none;
    padding-left: 0;
  }
  .side-menu a {
    color: white;
  }
  .card-header {
    background-color: #321fdb!important;
    color: white;
  }
  .hljs {
    padding-top: 25px;
  }
  h5 {
    padding-bottom: 10px;
    border-bottom: 1px solid #c0c0c0;
  }
  h6 {
    margin-bottom: 0;
    padding: 0.5rem 1rem;
    border-left: 4px solid #000;
    display: inline-block;
  }
  pre {
    margin-bottom: 0;
    height: 100px;
    overflow-y: hidden;
  }
  .result-html {
    margin-top: 5px;
    margin-bottom: 5px;
  }
  .copy {
    margin-left: 10px;
  }
  #copy-element {
    position: relative;
    left: -1000px;
  }
  -->
</style>
@endpush
@push('app-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.18.1/highlight.min.js"></script>
<script>
$(function() {
    $('.copy').on('click', (e) => {
      e.preventDefault();
      let html = $($(e.target).closest('.card-body').find('.copy-target').children()[0]).text();
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
    $('.side-menu a[href^="#"]').click((e) => {
      // アンカーの値取得
      const href= $(e.target).attr("href");
      // 移動先を取得
      const target = href == "#" ? 'html' : href;
      // 移動先を数値で取得
      const position = $(target).offset().top - 60;
      // スムーススクロール
      $('body,html').animate({scrollTop:position}, 400, 'swing');
      return false;
    });
    $('#form').parsley({
      errorsContainer: (ParsleyField) => {
        return ParsleyField.$element.attr('title');
      },
      errorsWrapper: fw.config.page.validation['notify-wrapper'],
      // チェックタイミング設定
      trigger: fw.config.page.validation['validation-timing']
    });
    hljs.initHighlightingOnLoad();
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
          <a class="nav-link" data-toggle="tab" href="#input-string" role="tab" aria-controls="input-string" aria-selected="false">文字列項目</a></li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#input-number" role="tab" aria-controls="input-number" aria-selected="false">数値項目</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#input-date" role="tab" aria-controls="input-date" aria-selected="false">日付項目</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#input-other" role="tab" aria-controls="input-other" aria-selected="false">チェックボックス/ラジオボタン/プルダウン</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#input-attr" role="tab" aria-controls="input-attr" aria-selected="false">属性</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#input-compare" role="tab" aria-controls="input-compare" aria-selected="false">比較符号</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#button-element" role="tab" aria-controls="button-element" aria-selected="false">ボタン</a>
        </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane mt-2 " id="label" role="label">
          @include('admin.baseform.label')
        </div>
        <div class="tab-pane mb-2" id="input-string" role="input-string">
          @include('admin.baseform.input-text')
        </div>
        <div class="tab-pane mb-2" id="input-number" role="input-number">
          @include('admin.baseform.input-number')
        </div>
        <div class="tab-pane mt-2 active" id="input-date" role="input-date">
          @include('admin.baseform.input-date')
        </div>
        <div class="tab-pane mt-2" id="input-other" role="input-other">
          @include('admin.baseform.input-other')
        </div>
        <div class="tab-pane mb-2" id="input-attr" role="input-attr">
          @include('admin.baseform.input-attr')
        </div>
        <div class="tab-pane mb-2" id="input-compare" role="input-compare">
          @include('admin.baseform.input-compare')
        </div>
        <div class="tab-pane mb-2" id="button-element" role="button-element">
          @include('admin.baseform.button')
        </div>
      </div>
    </div>
    <textarea id="copy-element" class="" placeholder="コピー用"></textarea>
  </form>
@endsection
@stack('head-style')
@stack('head-script')