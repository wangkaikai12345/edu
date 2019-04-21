@extends('teacher.homework.homework_layout')

@section('title', '作业批阅详情')

@section('homework_style')
    <link rel="stylesheet" href="{{ mix('/css/teacher/homework/info.css') }}">
@endsection

@section('homework_content')
    <div class="czh_list_con col-md-9 p-0" style="border-radius: 0.375rem;box-shadow: 0 0 4px rgba(0, 0, 0, 0.1);">
        <div class="card">
            <div class="row justify-content-end m-0">
                <a href="{{ route('manage.homework.post.index') }}" class="btn btn-primary btn-sm top_btn">
                    <i class="iconfont">
                        &#xe61a;
                    </i>
                    返回
                </a>
                @if($homeworkPost->status == 'reading')
                <a href="javascript:;" class="btn btn-primary btn-sm top_btn top_btn_2" data-toggle="modal"
                   data-target="#modal_1" data-backdrop="static" data-keyboard="false">
                    批改
                </a>
                @endif
                {{--<a href="javaScript:;" id="lock" class="btn btn-outline-danger btn-sm top_btn top_btn_2">--}}
                    {{--作废--}}
                {{--</a>--}}
            </div>
            <div class="row m-0">
                <div class="homework_info">
                    <div class="info_content">
                        <div class="info_item">
                            课程
                        </div>
                        <div class="info_item">
                            所属版本
                        </div>
                        <div class="info_item">
                            学员
                        </div>
                        <div class="info_item item_time">
                            提交时间
                        </div>
                        <div class="info_item">
                            状态
                        </div>
                        <div class="info_item">
                            {{ $homeworkPost->course->title }}
                        </div>
                        <div class="info_item">
                            {{ $homeworkPost->plan->title }}
                        </div>
                        <div class="info_item">
                            {{ $homeworkPost->user->username }}
                        </div>
                        <div class="info_item item_time">
                            {{ $homeworkPost->created_at }}
                        </div>
                        <div class="info_item {{ $homeworkPost->status == 'reading' ? 'text-warning' : 'text-success' }}">
                            {{ \App\Enums\HomeworkPostStatus::getDescription($homeworkPost->status) }}
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row m-0">
                <div class="info_content_item">
                    <div class="info_title">
                        学员作业
                    </div>

                    @if(in_array('zip', $homework->post_type))
                    <div class="content_item">
                                    <span class="item_label">
                                        作业包
                                    </span>
                        <div class="item_content">
                            <a href="{{ render_cover($homeworkPost->package, '') }}">
                                学员作业
                                <i class="iconfont">
                                    &#xe622;
                                </i>
                            </a>
                        </div>
                    </div>
                    @endif

                    @if(in_array('img', $homework->post_type))
                    <div class="content_item">
                                    <span class="item_label">
                                        效果图
                                    </span>
                        <div class="item_content">
                            @if($homeworkPost->post_img)
                                @foreach($homeworkPost->post_img as $img)
                                <img src="{{ render_cover($img, '') }}" alt="">
                                @endforeach
                            @endif
                        </div>
                    </div>
                    @endif

                    @if(in_array('code', $homework->post_type))
                        <div class="content_item">
                                    <span class="item_label">
                                        代码
                                    </span>
                            <div class="item_content" style="color:#666;background: transparent;border: 1px solid #d5d5d5;border-radius: 4px; padding: 10px">
                                {!! $homeworkPost->code !!}
                            </div>
                        </div>
                    @endif

                    @if(!empty($homeworkPost->student_review))
                    <div class="content_item">
                                    <span class="item_label">
                                        学员描述
                                    </span>
                        <div class="item_content">
                            <input type="text" readonly value="{{ $homeworkPost->student_review }}">
                        </div>
                    </div>
                    @endif
                    @if(!empty($homeworkPost->teacher_review))
                    <div class="content_item">
                                    <span class="item_label">
                                        教师点评
                                    </span>
                        <div class="item_content">
                            <input type="text" readonly value="{{ $homeworkPost->teacher_review }}">
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <hr>
            <div class="row m-0">
                <div class="info_content_item content_star">
                    <div class="info_title">
                        作业名称: {{ $homework->title }}
                    </div>
                    @if(!empty($homework->about))
                    <div class="content_item">
                                    <span class="item_label">
                                        问题描述
                                    </span>
                        <div class="item_content">
                            {!! $homework->about !!}
                        </div>
                    </div>
                    @endif
                    @if(!empty($homework->hint))
                    <div class="content_item">
                                    <span class="item_label">
                                        解锁提示
                                    </span>
                        <div class="item_content">
                            {!! $homework->hint !!}
                        </div>
                    </div>
                    @endif
                    @if(!empty($homework->grades_content))
                    <div class="content_item">
                                    <span class="item_label">
                                        批改标准
                                    </span>
                        <div class="item_content">
                            @foreach($homework->grades_content as $i)
                                <p>{{ $i }}</p>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if(!empty($homework->video))
                    <div class="content_item">
                                    <span class="item_label">
                                        讲解视频
                                    </span>
                        <div class="item_content" style="border: 0;">
                            <video style="height:200px;" src="{{ render_cover($homework->video, '') }}" controls="controls">
                                您的浏览器不支持 video 标签。
                            </video>
                        </div>
                    </div>
                    @endif
                    @if(!empty($homework->package))
                    <div class="content_item">
                                    <span class="item_label">
                                        资料包
                                    </span>
                        <div class="item_content" style="border: 0">
                            <a href="{{ render_cover($homework->package, '') }}" target="_blank" download="">下载资料包</a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_1" tabindex="-1" role="dialog" aria-labelledby="modal_1" aria-hidden="true">
        <div class="modal-dialog enter_homework" role="document">
            <div class="modal-content">
                <form action="{{ route('manage.homework.post.read', $homeworkPost) }}" method="post">
                    {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_title_6">完成批阅</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="py-3 text-center">
                            <div class="row justify-content-center">

                                <div class="col-8">

                                    <div class="label float-left">
                                        批改视频
                                    </div>
                                    <div class="float-left upload_wrap">
                                        <input data-token="{{ route('manage.qiniu.token.hash') }}" type="file" id="correct_media" class="custom-input-file"
                                               data-multiple-caption="{count} files selected" accept="video/*"/>
                                        <label for="correct_media" class="file_upload">
                                            <span>选择文件</span>
                                        </label>
                                        <input type="hidden" id="video-key" name="correct_media" value="">
                                        <input type="hidden" id="video-hash" name="correct_hash" value="">
                                    </div>
                                    <div class="progress-wrapper" style="display: none; position: relative;width: 74%;margin-left: 23%;margin-top: 30px;" id="up-progress">
                                        <h4 class="progress-tooltip" style="left: 0%;position: absolute" id="video_progress_show">0%</h4>
                                        <div class="progress" style="height: 3px;">
                                            <div class="progress-bar bg-primary"
                                                 id="video_progress_color"
                                                 role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                                 aria-valuemax="100" style="width:0%;"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-8">
                                    <div class="label float-left">
                                        各项评分
                                    </div>
                                    <div class="star_content">

                                        @foreach($retGrades as $k => $grade)
                                        <div class="star_item text-left" data-id="{{ $grade['id'] }}" data-star="{{ $grade['score'] }}">
                                            <div class="item_title">
                                                <span id="grade-title-{{ $grade['id'] }}">{{ $k + 1 }}、{{ $grade['title'] }}</span>
                                                <span class="zongfen">
                                                    满分：{{ $grade['score'] }}分
                                                </span>
                                            </div>
                                            <div class="input-slider-container">
                                                <div class="input-slider float-left" data-range-value-min="100"
                                                     data-range-value-max="500"></div>
                                                <!-- Input slider values -->
                                                <div class="float-left">得分：<span class="star_fen">0</span></div>
                                            </div>
                                        </div>
                                            <input type="hidden" class="one-grade-content" id="grade-content-{{$grade['id']}}">
                                            <input type="hidden" name="grades[]" id="grade-content-post-{{$grade['id']}}" value="">
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="label float-left">
                                        总分
                                    </div>
                                    <div class="float-left" style="width: 74%;">
                                        <input type="text" class="form-control form-control-sm" readonly placeholder="" name="result"
                                               value="0" id="all-score-show">
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="label float-left">
                                        作业点评
                                    </div>
                                    <textarea class="pingjia" name="teacher_review" id="grade-content-show">1、编码规范：有几处不规范，继续努力</textarea>
                                </div>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-sm btn-primary">提交</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('homework_script')
    <script src="{{  '/vendor/nouislider/distribute/nouislider.min.js'  }}"></script>
    <script>
        window.onload = function () {
            var grades_content = @json($grades_contents);

            $('.star_item').each((index, item) => {
                noUiSlider.create($(item).find('.input-slider').get(0), {
                    start: [0],
                    range: {
                        'min': 0,
                        'max': 100
                    },
                    connect: [true, false],
                });

                (function (item) {
                    $(item).find('.input-slider').get(0).noUiSlider.on('update', function (values, handle) {
                        var score = Math.floor($(item).data('star') * values[handle] / 100);
                        $(item).find('.star_fen').get(0).innerHTML = score;

                        // 根据评分找出对应的评语
                        var gid = $(item).data('id');
                        var allGc = grades_content[gid]; // 这条的所有差中好评

                        var gc = '';
                        var index = 0;
                        var percent = parseInt(values[0]);
                        if (percent >= 40 && percent < 80) {
                            gc = allGc[1];
                            index = Math.round(((percent - 40) / 40 ) * (gc.length - 1));
                        } else if (percent >= 80) {
                            gc = allGc[2];
                            index = Math.round(((percent - 80) / 20 ) * (gc.length - 1));
                        } else {
                            gc = allGc[0];
                            index = Math.round((percent / 40 ) * (gc.length - 1));
                        }

                        $('#grade-content-' + gid).val($('#grade-title-' + gid).text() + "：" + gc[index] + "\r\n");
                        $('#grade-content-post-' + gid).val($('#grade-title-' + gid).text() + "：" + score + ' 分');

                        // 处理总分
                        var allScore = 0;
                        $('.star_fen').each(function () {
                            allScore += parseInt($(this).text());
                        });
                        $('#all-score-show').val(allScore);

                        // 处理总评语
                        var allGradeContent = '';
                        $('.one-grade-content').each(function () {
                            allGradeContent += $(this).val();
                        });
                        $('#grade-content-show').html(allGradeContent);

                    });


                }(item))
            });

            /**
             * 讲解视频上传触发的函数
             */
            $('#correct_media').change(function (event) {
                var files = event.target.files;
                $('#up-progress').show();
                uploadFile(files, $(this).data('token'), 'zip', '', function (type, res) {
                    if (type == 'complete') {
                        // 上传成功之后将文件key放入隐藏id
                        $('#video-key').val(res.key);
                        $('#video-hash').val(res.hash);
                        edu.alert('success', '视频上传成功!');
                    } else if (type == 'next') {
                        var perc = res.total.percent.toFixed(2) + '%';
                        $('#video_progress_show').text(perc);
                        $('#video_progress_show').css('left', perc)
                        $('#video_progress_color').css('width', perc)
                    } else if (type == 'exist') {
                        edu.alert('danger', '视频已存在!');
                        console.log(res);
                    } else if (type == 'err') {
                        edu.alert('danger', res.message);
                    }
                });
            });

//            /**
//             * 作废作业
//             */
//            $("#lock").click(function(){
//                if (confirm('是否作废本次提交的作业?')) {
//
//                }
//            });

        }
    </script>
@endsection