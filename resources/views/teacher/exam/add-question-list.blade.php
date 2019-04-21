<form action="{{ route('manage.question.list.json') }}" id="search-question-form" onsubmit="return false;">

<div class="row">
    <div class="col-lg-4">
        <div class="col-12">
            <div class="form-group">
                <div class="input-group input-group-transparent">
                    <label class="control-label col-lg-4 text-left pr-0 pt-1">标题关键字</label>
                    <input type="text" name="title" id="title" class="form-control col-lg-8"
                           placeholder="关键字" value="{{ request()->title }}">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-7 p-0 row second">
        <div class="col-lg-6 p-0">
            <div class="form-group">
                <div class="input-group input-group-transparent">
                    <label class="control-label col-lg-2 text-left pl-0 pr-0 pt-1">出题人</label>
                    <input type="text" name="username" id="username" class="form-control col-lg-9"
                           placeholder="出题人" value="{{ request()->username }}">
                </div>
            </div>
        </div>
        <div class="col-lg-6 pl-0">
            <div class="form-group">
                <div class="input-group input-group-transparent">
                    <input type="text" name="tag" id="tag" class="form-control col-lg-11"
                           placeholder="标签" value="{{ request()->tag }}">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-1 pl-0">
        <button type="submit" class="btn btn-primary search float-left" id="search-question-btn">搜索</button>
    </div>
</div>
<div class="table_content">
    <table class="table table-hover" id="question_table">
        <thead>
        <tr>
            <th scope="col" width="250">题目名称</th>
            <th scope="col" width="120">题目类型</th>
            <th scope="col" width="200">难度</th>
            <th scope="col">出题人</th>
            <th scope="col">操作</th>
        </tr>
        </thead>
        <tbody id="show-json-question">
            @php
                $ops = ['A', 'B', 'C', 'D', 'E', 'F'];
            @endphp
            @foreach($questions as $question)
                {{--  这里来放置问题详情  --}}
                <div style="display:none">
                    <div id="show-question-info-{{ $question->id }}" class="row">
                        <div class="col-lg-12">
                            <div class="question_content">
                                <div class="question_type">
                                    题目标题
                                </div>
                                <div class="question_title">
                                    {!! $question->title !!}
                                </div>
                            </div>
                            @if($question->type != 'answer')
                                <hr width="85%" style="margin-top:20px;">
                                <div class="option_content">
                                    @foreach($question->options as $key => $option)
                                    <div class="option_item">
                                        {!! $ops[$key] . '.' .$option !!}
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            <tr class="question_tr">
                <th scope="colgroup" class="question_name">
                    {!! $question->title !!}
                </th>
                <input type="hidden" class="question_id" value="{{ $question->id }}">
                <td>{{ \App\Enums\QuestionType::getDescription($question->type) }}</td>
                <td>
                    <div class="star">
                        @for($i = 0; $i < 5; $i++)
                            @if($i < $question->rate)
                                <i class="iconfont">
                                    &#xe601;
                                </i>
                            @else
                                <i class="iconfont">
                                    &#xe60d;
                                </i>
                            @endif
                        @endfor
                    </div>
                </td>
                <td>{{ $question->user->username }}</td>
                <td>
                    <a href="javascript:;" class="show-question-info" data-id="{{ $question->id }}" data-toggle="modal" data-target=".preview-question-modal-sm">预览</a>
                    <a href="javascript:;" class="add_question" data-id="{{ $question->id }}" id="add_question_{{ $question->id }}">添加</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <nav class="pageNumber" aria-label="Page navigation example" style="margin-left:35%;">
        {{ $questions->appends(request()->all())->links('vendor.pagination.bootstrap-4') }}
    </nav>
</div>

</form>
