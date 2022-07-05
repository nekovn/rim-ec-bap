{{-- kbn:create, store, update, delete, search, clear --}}
@if(($kbn == "create" or $kbn=='update' or $kbn=='delete')and $updAuth==0)
        {{-- 更新権限がないため表示しない --}}
@else
    @if ($kbn=="create")
        <button type="button" {{ $attributes->merge(['class'=>'btn btn-outline-dark']) }}>
            <i class="fas fa-plus"></i>
            {{ isset($caption) ? $caption : '新規登録'}}

    @elseif ($kbn=="store")
        <button type="button" {{ $attributes->merge(['class'=>'btn btn-info']) }}>
            <i class="far fa-edit"></i>
            {{ isset($caption) ? $caption : '登録'}}

    @elseif ($kbn=="update")
        <button type="button" {{ $attributes->merge(['class'=>'btn btn-info']) }}>
            <i class="far fa-edit"></i>
            {{ isset($caption) ? $caption : '更新'}}

    @elseif ($kbn=="delete")
        <button type="button" {{ $attributes->merge(['class'=>'btn btn-danger']) }}>
            <i class="far fa-trash-alt"></i>
            {{ isset($caption) ? $caption : '削除'}}
    @endif
    </button>
@endif

