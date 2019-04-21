@foreach($courses as $course)
    <tr>
        <td>{{ $course->title }}</td>
        <td>{{ empty($course->default_plan) ? 0 : $course->default_plan->price / 100 }}元</td>
        <td>{{ $course->plans_count }}</td>
        <td>{{ $status[$course->status] }}</td>
        <td>{{ $course->students_count }}</td>
        <td>{{ $course->user->username ?? null}}</td>
        <td>{{ $course->serialize_mode == 'none' ? '非连载' : '连载中' }}</td>
        <td>
            <button class="btn btn-sm btn-primary" onclick="selectCourse('{{$course->id}}', '{{$course->title}}')">选择</button>
        </td>
    </tr>
@endforeach
<tr id="coursePagination">
    <td colspan="8" style="padding-top: 25px;text-align: center">
        <style>
            .pagination {
                display: inline-flex;
            !important;
            }
        </style>
        {{ $courses->appends(request()->all())->links('vendor.pagination.bootstrap-4') }}
    </td>
</tr>
<script src="/backstage/global/vendor/jquery/1.11.3/jquery.min.js"></script>
<script>
    $('#coursePagination  .pagination .page-item .page-link').click(function (e) {
        e.preventDefault();

        // 获取请求的url
        const url = $(this).attr('href');

        // url存在
        if (url) {
            $("#plansModal .modal-content .table-tbody-content").children().remove();
            $("#plansModal .modal-content .table-tbody-content").load(url);
        }
    });

    function selectCourse(hashId, title) {
        $('input[name="product_id"]').val(hashId);
        $('#plan_title_content').html(title)
        $('.plan_title').show();
        $('#plansModal .close').trigger('click');
    }
</script>