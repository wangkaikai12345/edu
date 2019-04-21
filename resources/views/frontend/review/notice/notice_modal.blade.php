<div class="modal-header">
    <h6 class="modal-title w-100" id="myModalLabel">通知详情</h6>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body font-small" >
    <div class="notice-content">
        <p>
            <b>{{ $item['data']['title'] }}</b>
        </p>
    </div>
    <h6 class="notice-author font-small">
        {!! $item['data']['content'] !!}
    </h6>
</div>

