@extends('teacher.plan.plan_layout')
@section('plan_content')
    <div class="czh_task_content col-xl-9 col-md-9">
        <div class="operation_header">
            <p>弹题统计</p>
        </div>
        <div class="basics_information">
            <form action="">
                {{--<div class="form_search_item">--}}
                    {{--<select class="form-control question_count_select" name="" id="">--}}
                        {{--<option value="1">任务名</option>--}}
                        {{--<option value="2">试卷名</option>--}}
                    {{--</select>--}}
                {{--</div>--}}
                <div class="question_count_table">
                    <table class="table table-hover table-cards align-items-center">
                        <thead>
                        <tr>
                            <th scope="col">试卷名称</th>
                            <th scope="col">时间点</th>
                            <th scope="col">参与人数</th>
                            <th scope="col">答题次数</th>
                            <th scope="col">及格次数</th>
                            <th scope="col">及格率</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($papers as $paper)
                            <tr class="bg-white">
                                <td>{{ $paper->title }}</td>
                                <td>{{ timeFormat($paper->video_time, true) }}</td>
                                <td>{{ $paper->persons }}</td>
                                <td>{{ $paper->numbers }}</td>
                                <td>{{ $paper->trueCount }}</td>
                                <td>{{ round($paper->trueCount / $paper->numbers, 2) * 100 }}%</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <nav class="course_list" aria-label="Page navigation example">
                        {{ $papers->appends(request()->all())->links('vendor.pagination.bootstrap-4') }}
                    </nav>
                </div>
            </form>
        </div>
    </div>

@endsection
@section('script')

@endsection
