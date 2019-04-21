@extends('teacher.homework.homework_layout')

@section('title', '作业列表')

@section('homework_style')
    <link rel="stylesheet" href="{{ mix('/css/teacher/homework/list.css') }}">
    <link rel="stylesheet" href="/vendor/select2/dist/css/select2.min.css">
@endsection

@section('homework_content')
    <div class="czh_list_con col-md-9 p-0">
        <div class="list_head">
            <p>作业列表</p>
        </div>
        <div class="list_content">
            <div class="con_form">
                <form action="#">
                    <div class="form-group search_title">
                        <input type="text" class="form-control" name="title"
                               value="{{ request('title') }}" placeholder="标题">
                    </div>

                    <div class="form-group search_tag">
                        <select id="label" name="label" type="text"
                                class="form-control col-md-12 col-lg-9 col-xl-9">
                            <option value="">请输入标签</option>
                                @foreach($labels as $label)
                                    <option value="{{ $label->text }}" {{ request()->label == $label->text ? 'selected' : '' }}>{{ $label->text }}</option>
                                @endforeach
                        </select>
                    </div>
                    <div class="form-group search_btn">
                        <button type="submit" class="btn btn-primary">搜索</button>
                    </div>
                </form>
            </div>
            <div class="list_item">
                <table class="table table-hover table-cards align-items-center">
                    <thead>
                    <tr>
                        <th scope="col">作业名称</th>
                        <th scope="col">状态</th>
                        <th scope="col">标签</th>
                        <th scope="col" style="min-width: 150px">操作</th>
                    </tr>
                    </thead>
                    @foreach($homeworks as $homework)
                        <tr class="bg-white">

                            <td>
                                {{ $homework->title }}
                            </td>
                            <td>
                                <span class="{{ $homework->status == \App\Enums\Status::PUBLISHED ? 'text-success' : 'text-danger' }}">
                                    {{ \App\Enums\Status::getDescription($homework->status) }}
                                </span>
                            </td>
                            <td>
                                <div style="width:200px;">
                                    @if ($homework->tags)
                                        @foreach($homework->tags as $tag)
                                            <span class="badge badge-success">{{ $tag->name }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            </td>
                            <td class="list_item_action">
                                <button class="yl btn btn-link" data-toggle="modal"
                                        data-target="#modal" data-url="{{ route('manage.homework.show', $homework) }}">预览
                                </button>
                                <button class="jq_q btn btn-link check-status"
                                        data-status="{{ $homework->status }}"
                                        data-url="{{ route('manage.homework.status', $homework) }}">
                                    @if ($homework->status == 'draft')
                                        点击发布
                                    @else
                                        取消发布
                                    @endif
                                </button>

                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                </table>
            </div>
            <nav class="course_list" aria-label="Page navigation example">
                {{ $homeworks->appends(request()->all())->links('vendor.pagination.bootstrap-4') }}
            </nav>

        </div>
    </div>

@endsection
@section('homework_script')
    <script src="/vendor/select2/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        /**
         * 标签相关的select2搜索+多选
         */
        {{--var labels = {!! $labels !!};--}}
        var labels = {};
        $("#label").select2({
            maximumSelectionSize: 20,
            placeholder: '请输入标签名称',
            createSearchChoice: function (term, data) {
                if ($(data).filter(function () {
                        return this.text.localeCompare(term) === 0;
                    }).length === 0) {
                    return {id: term, text: term};
                }
            },
            multiple: false,
            data: labels,
        });

        window.onload = function () {
            $('.check-status').click(function () {
                var status = $(this).data('status');
                var msg = '';
                if (status == 'draft') {
                    msg = '是否发布这个作业?';
                } else {
                    msg = '是否关闭这个作业?'
                }

                var url = $(this).data('url');
                edu.confirm({
                    type: 'danger', dataType: 'html', message: '<img src>', title: msg, callback: function (props) {
                        if (props.type === 'success') {
                            $.ajax({
                                url: url,
                                type: 'patch',
                                success: function (res) {
                                    if (res.status == 200) {
                                        edu.alert('success', res.message);
                                        window.location.reload();
                                    } else {
                                        edu.alert('danger', res.message);
                                    }
                                }
                            });
                        } else {
                            return false;
                        }
                    }
                });

            })
        }
    </script>
@endsection