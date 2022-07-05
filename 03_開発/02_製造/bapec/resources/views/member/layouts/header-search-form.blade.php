{{ Form::open(['route' => 'goods.search', 'method'=>'GET', 'name'=>'header_search_form', 'class'=>'header_search_form']) }}
<div class="header-search-form-inner">
  {{ Form::Text('k',isset($headerParam)? $headerParam['k']: '',['class'=>'header_search_condition_k', 'size'=>'20', 'placeholder' => '&#xf002; 商品を探す']) }}
  {{ Form::hidden('c',isset($headerParam)? $headerParam['c']: '',['class'=>"header_search_condition_c"])}}
  {{ Form::hidden('s','',['class'=>"header_search_condition_s"])}}
</div>
{{ Form::close() }}
