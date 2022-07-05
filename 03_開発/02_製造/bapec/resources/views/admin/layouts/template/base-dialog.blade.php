<?php
/**
 * jquery-ui dialog　ベース
 */
?>

<div id="{{ $dialogId }}-area" style="z-index: -1;display:none;" >
  <div class="modal-body">
    <div class="">
      <div class="card ">
        <div class=" card-body p-1">
          <section id="{{ $dialogId }}" >
            @yield($dialogId.'-content')
          </section>
        </div>
      </div>
    </div>
  </div>
</div>
