@foreach($categories as $category)
    <tr>
        <td>
            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                    {{ $category->name }}
                </font></font>
        </td>
        <td>
            <div style="position: absolute;right: 130px;">
                <button class="btn  btn-sm btn-primary"
                        data-target="#myCategorySimpleModal" data-toggle="modal"
                        onclick="showCategorySimpleModal('{{ route('backstage.category.edit',
                                                                                 ['categoryGroup' => $categoryGroup->hashId, 'category' => $category->id])}}')">编辑</button>
            </div>
        </td>
    </tr>
@endforeach
<tr id="categoryPagination">
    <td colspan="4" style="padding-top: 25px">
        {{ $categories->appends(request()->all())->links('vendor.pagination.bootstrap-4') }}
    </td>
</tr>
<script src="/backstage/global/vendor/jquery/1.11.3/jquery.min.js"></script>
<script>


    $("#myCategorySimpleModal .close").click(function () {
        $('#myLgTableModal').show();
    });

    $('#categoryPagination  .pagination .page-item .page-link').click(function (e) {
        e.preventDefault();

        // 获取请求的url
        const url = $(this).attr('href');

        // url存在
        if (url) {
            $("#myLgTableModal .modal-table-content .table-tbody-content").children().remove();
            $("#myLgTableModal .modal-table-content .table-tbody-content").load(url);
        }
    });


    // 展示modal
    function showCategorySimpleModal(url) {
        $("#myCategorySimpleModal .modal-content").load(url);
        $("#myLgTableModal").hide();
    }


</script>