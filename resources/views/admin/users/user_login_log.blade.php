@foreach($logs as $log)
    <tr>
        <td>
            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                    {{ $log->user->username ?? '暂无信息' }}
                </font></font>
        </td>
        <td>
            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                    {{ $log->created_at ?? '暂无信息' }}
                </font></font>
        </td>
        <td>
            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                    {{ $log->user->registered_ip ?? '暂无信息'}}
                </font></font>
        </td>
        <td>
            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                    {{ $log->area ?? '暂无信息' }}
                </font></font>
        </td>
    </tr>
@endforeach
<tr id="userLogPagination">
    <td colspan="4" style="padding-top: 25px">
        {{ $logs->links() }}
    </td>
</tr>
<script src="/backstage/global/vendor/jquery/1.11.3/jquery.min.js"></script>
<script>
    $('#userLogPagination  .pagination .page-item .page-link').click(function (e) {
        e.preventDefault();

        // 获取请求的url
        const url = $(this).attr('href');

        // url存在
        if (url) {
            $("#myLgModal .modal-content .table-tbody-content").children().remove();
            $("#myLgModal .modal-content .table-tbody-content").load(url);
        }
    });
</script>