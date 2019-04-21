{{--批量撤销--}}
<div class="modal fade" id="destroyCouponModal" aria-labelledby="destroyCouponModalLabel" role="dialog" tabindex="-1"
     style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-simple modal-lg">
        <div class="modal-content form-horizontal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">批量撤销</h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-hover  toggle-circle"
                       data-paging="false" data-plugin="selectable" data-row-selectable="true" id="messageTable">
                    <thead>
                    <tr>
                        <th>批次号</th>
                        <th>创建时间</th>
                        <th>状态</th>
                        <th>类型</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody class="table-tbody-content">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
