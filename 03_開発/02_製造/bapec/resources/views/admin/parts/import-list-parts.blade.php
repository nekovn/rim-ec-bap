
{{-- import_log一覧　部品 --}}
<?php
use \App\Enums\CodeDefine;
use Carbon\Carbon;
$fid = $functionId.'-log-list';
?>
<section id={{$fid}}>
    {{-- {{Form::open(['id' =>'form-'.$fid])}}
        {{Form::hidden('functionId',$fid)}} --}}
        <div class="row ">
            <div class="col-md-12 form-inline">
                <div class="font-xl font-bold">処理状態</div>
                <div class="text-muted ml-3">
                    ※直近5件を表示しています
                </div>
                <button type="button" class="btn btn-info btn btn-info ml-auto" 
                id="{{$functionId."-btn-reload"}}" title ='最新に更新'>
                    <i class="fas fa-redo-alt"></i>
                </button>
            </div>
        </div>
    {{-- {{Form::close()}} --}}

    {{-- 検索結果 --}}
    <div id="log-list-parts-result" >
        <div class="row mt-1">
            {{-- <div id="total-count" class="col-12 text-right"></div> --}}
            {{-- {!! Form::grid("import-list-parts",[], false) !!} --}}
            <div id="{{$functionId}}-grid" class="ag-theme-alpine grid mb-2"></div>
        </div>
    </div>
</section>