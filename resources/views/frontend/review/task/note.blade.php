<link rel="stylesheet" href="{{ mix('/css/front/task/note/index.css') }}">
<link href="/vendor/summernoteExtra/summernote.css" rel="stylesheet">
<link rel="stylesheet" href="/vendor/summernoteExtra/summernote-add-text-tags.css">

<div class="czhNote driver upload_token" id="czh_note" data-driver="{{ data_get(\Facades\App\Models\Setting::namespace('qiniu'), 'driver', 'local') }}" data-token="{{ route('manage.qiniu.token.hash') }}">
    <div class="scroll_change">

    </div>
    <div class="note_scroll" style="padding-bottom: 76px;">
        <h3>评论</h3>
        <div class="row pb-80">
            <ul class="nav nav-pills nav-fill flex-column flex-sm-row" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link mb-sm-3 active" id="createNote-tab" data-toggle="tab" href="#createNote"
                       role="tab"
                       aria-controls="createNote" aria-selected="true">创建评论</a>
                </li>
                {{--<li class="nav-item">--}}
                    {{--<a class="nav-link mb-sm-3" id="selectedNote-tab" data-toggle="tab" href="#selectedNote" role="tab"--}}
                       {{--aria-controls="selectedNote" aria-selected="false"--}}
                       {{--data-route="{{ route('tasks.notes.index', $task) }}">精选笔记</a>--}}
                {{--</li>--}}
                <li class="nav-item">
                    <a class="nav-link mb-sm-3" id="myNote-tab" data-toggle="tab" href="#myNote" role="tab"
                       aria-controls="myNote" aria-selected="false"
                       data-route="{{ route('tasks.notes.index', [$task, 'my' => 'self']) }}">我的评论</a>
                </li>
            </ul>
            <div class="tab-content col-12 pl-0" id="myTabContent">
                <div class="tab-pane fade show active" id="createNote" role="tabpanel" aria-labelledby="createNote-tab">
                    <div class="batV">
                        <div id="note_editorjs"></div>
                    </div>
                    {{--<div class="custom-control custom-checkbox mb-3">--}}
                        {{--<input type="checkbox" class="custom-control-input" id="is_public" checked>--}}
                        {{--<label class="custom-control-label" for="customCheck1">公开</label>--}}
                    {{--</div>--}}
                    {{--<div class="screenShot">--}}
                    {{--<i class="iconfont">&#xe620;</i>--}}
                    {{--<span>截图</span>--}}
                    {{--</div>--}}
                    <div class="postNoteBtn">
                        <button class="btn" id="submit_note" data-route="{{ route('tasks.notes.store', $task) }}">提交
                        </button>
                    </div>
                </div>
                <div class="tab-pane fade col-md-12 notePublic no_data" id="selectedNote" role="tabpanel"
                     aria-labelledby="selectedNote-tab">
                    <div class="note_wrap" id="collect_note">

                        {{--<div class="note_item">--}}
                            {{--<div class="titleHeight">--}}
                                {{--<p class="heightSwitch">全文</p>--}}
                            {{--</div>--}}
                            {{--<div class="noteData">--}}
                                {{--<p class="useNum"><span>1</span>查看</p>--}}
                                {{--<p class="fabulousNum"><span>3</span> <i class="iconfont active">&#xe641;</i></p>--}}
                                {{--<p class="noteCreateTime">2019-01-01 00:00:00</p>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                    </div>
                    <a href="javascript:;" id="more_collect_note" style="display:none" data-page="1">加载更多</a>
                </div>
                <div class="tab-pane fade col-md-12 notePublic no_data" id="myNote" role="tabpanel"
                     aria-labelledby="myNote-tab">
                    <div class="note_wrap" id="my_note">
                    </div>
                    <a href="javascript:;" id="more_my_note" style="display:none" data-page="1">加载更多</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ mix('/js/front/task/note/index.js') }}"></script>
<script src="/vendor/summernoteExtra/summernote.js"></script>
<script src="/vendor/summernoteExtra/summernote-zh-CN.js"></script>
<script src="/vendor/summernoteExtra/summernote-add-text-tags.js"></script>
<script src="/tools/qiniu/qiniu2.min.js"></script>
<script src="/tools/sha1.js"></script>
<script src="/tools/qetag.js"></script>
<script src="/tools/qiniu/qiniu-luwnto.js"></script>
