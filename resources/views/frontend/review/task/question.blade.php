<link rel="stylesheet" href="{{ mix('/css/front/task/question/index.css') }}">

<div class="zh_question" id="zh_question" data-task-id="{{ $task->id }}">
    <div class="scroll_change">

    </div>
    <div class="question_scroll">
        <h3>问答</h3>
        <div class="form_controls">
            <div class="form_wrap">
                <form action="" class="row search_wrap">
                    <input type="text" placeholder="请输入你的问题" class="col-md-8" id="search_input">
                    <i class="iconfont" id="search_icon">
                        &#xe653;
                    </i>
                    <div class="col-md-4">
                        <button class="btn btn-primary btn-circle" id="open_question">提问</button>
                    </div>
                </form>
                <div class="form">
                    <input type="text" placeholder="一句话描述你的问题" class="col-md-12 question_title" id="title">
                    <script id="question_editor" name="question_editor" type="text/plain"></script>
                    <div class="col-md-4 pl-0">
                        <button type="button"
                                class="btn btn-primary btn-circle float-left" style="padding: 5px 35px;margin-top: 30px;"
                                id="question" data-route="{{ route('tasks.topics.store', $task) }}">提问</button>
                        <a href="" class="float-left" style="margin: 36px 0 0 30px;font-size: 14px;" id="question_back">返回</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row pb-80" id="row" data-route="{{ route('tasks.topics.show', $task) }}">
            <ul class="nav nav-pills nav-fill flex-column flex-sm-row" id="myTab" data-route="{{ route('tasks.topics.show', $task) }}" role="tablist">
                <li class="nav-item">
                    <a class="nav-link mb-sm-3 active" id="hot-tab"  href="javascript:;">热门</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mb-sm-3" id="questions-tab"  href="javascript:;">我的提问</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mb-sm-3" id="contact-tab"  href="javascript:;" role="tab"
                       >我的回答</a>
                </li>
            </ul>
            <div class="tab-content col-12 pl-0" id="myTabContent">
                <div class="tab-pane fade show active" id="hot" role="tabpanel" aria-labelledby="hot-tab">
                    <div class="question_wrap no_data" id="question_wrap">

                    </div>
                    <a href="javascript:;" id="more_my_question" style="display:none" data-page="1">加载更多</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ mix('/js/front/task/question/index.js') }}"></script>