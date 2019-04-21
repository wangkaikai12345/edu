@extends('frontend.default.user.index')
@section('title', '个人信息')

@section('partStyle')
    <link href="{{ asset('dist/profile/css/index.css') }}" rel="stylesheet">
@endsection
@section('rightBody')
    <div class="col-xl-9 profile">
        <div class="card">
            <form action="{{ route('users.update', $user) }}" id="edit" method="post">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <div class="card-body">
                <h6 class="card-title">个人信息</h6>
                <hr>

                <div class="row pl-3">
                    <div class="col-xl-6">
                        <div class="avatar-wrap" data-toggle="modal" data-target="#centralModallg">
                            <img src="{{ render_cover($user['avatar'],'avatar') }}" alt="加载中..." id="userAvatar"
                                 data-token="{{ route('qiniu.token') }}">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <span class="font-small secondary">请上传jpg, gif, png格式的图片,<br> 建议图片大小不超过2MB</span>
                        <input type="hidden" name="avatar" value="{{ $user['avatar'] }}">
                    </div>
                    <div class="col-xl-6">
                        <h6 class="mt-3">用户名</h6>
                        <input type="text" id="username" class="form-control mt-4" value="{{ $user['username'] }}" disabled>
                    </div>
                </div>
                <div class="row pl-3 mt-4">
                    <div class="col-xl-6">
                        <h6 class="mt-3">真实姓名</h6>
                        <input type="text" id="name" name="profile[name]" class="form-control mt-4" value="{{ $user->profile['name'] }}">
                    </div>
                    <div class="col-xl-6">
                        <h6 class="mt-3 mb-4">性别</h6>
                        <div class="form-check float-left pl-0">
                            <input type="radio" class="form-check-input" id="male" value="male" name="profile[gender]" required {{ $user->profile['gender'] == 'male' ? 'checked' : '' }}>
                            <label class="form-check-label" for="male">男</label>
                        </div>
                        <div class="form-check float-left">
                            <input type="radio" class="form-check-input" id="female" value="female" name="profile[gender]" {{ $user->profile['gender'] == 'female' ? 'checked' : '' }}>
                            <label class="form-check-label" for="female">女</label>
                        </div>
                        <div class="form-check float-left">
                            <input type="radio" class="form-check-input" id="secret" value="secret" name="profile[gender]" {{ $user->profile['gender'] == 'secret' ? 'checked' : '' }}>
                            <label class="form-check-label" for="secret">保密</label>
                        </div>
                    </div>
                </div>
                <div class="row pl-3 mt-4">
                    <div class="col-xl-6">
                        <h6 class="mt-3">头衔</h6>
                        <input type="text" id="title" class="form-control mt-4" name="profile[title]" value="{{ $user->profile['title'] }}">
                    </div>
                    <div class="col-xl-6">
                        <h6 class="mt-3">个人签名</h6>
                        <input type="text" id="signature" class="form-control mt-4" name="profile[signature]" value="{{ $user['signature'] }}">
                    </div>
                </div>
                <div class="row pl-3 mt-4">
                    <div class="col-xl-11">
                        <h6 class="mt-3 mb-4">自我介绍</h6>
                        <script id="editor" name="profile[about]" type="text/plain">{!! $user->profile['about'] !!}</script>
                    </div>
                </div>
                <div class="row pl-3 mt-4">
                    <div class="col-xl-6">
                        <h6 class="mt-3">公司</h6>
                        <input type="text" id="company" class="form-control mt-4" name="profile[company]" value="{{ $user->profile['company'] }}">
                    </div>
                    <div class="col-xl-6">
                        <h6 class="mt-3">职业</h6>
                        <input type="text" id="job" class="form-control mt-4" name="profile[job]" value="{{ $user->profile['job'] }}">
                    </div>
                </div>
                <div class="row pl-3 mt-4">
                    <div class="col-xl-6">
                        <h6 class="mt-3">个人空间/个人网站</h6>
                        <input type="text" id="site" class="form-control mt-4" name="profile[site]" value="{{ $user->profile['site'] }}">
                    </div>
                    <div class="col-xl-6">
                        <h6 class="mt-3">微博</h6>
                        <input type="text" id="weibo" class="form-control mt-4" name="profile[weibo]" value="{{ $user->profile['weibo'] }}">
                    </div>
                </div>
                <div class="row pl-3 mt-4">
                    <div class="col-xl-6">
                        <h6 class="mt-3">微信</h6>
                        <input type="text" id="weixin" class="form-control mt-4" name="profile[weixin]" value="{{ $user->profile['weixin'] }}">
                    </div>
                    <div class="col-xl-6">
                        <h6 class="mt-3">QQ</h6>
                        <input type="text" id="qq" class="form-control mt-4" name="profile[qq]" value="{{ $user->profile['qq'] }}">
                    </div>
                </div>
                <div class="row pl-3 mt-4">
                    <div class="col-xl-6">
                        <h6 class="mt-3">毕业院校</h6>
                        <input type="text" id="school" class="form-control mt-4" name="profile[school]" value="{{ $user->profile['school'] }}">
                    </div>
                    <div class="col-xl-6">
                        <h6 class="mt-3">所学专业</h6>
                        <input type="text" id="major" class="form-control mt-4" name="profile[major]" value="{{ $user->profile['major'] }}">
                    </div>
                </div>
                <div class="row pl-3 mt-4 mb-4">
                    <div class="col-xl-6">
                        <h6 class="mt-3">生日</h6>
                        <div class="md-form">
                            <input placeholder="请选择日期" data-value="2000/02/00" type="text" id="birthday" class="form-control datepicker" name="profile[birthday]">
                            <label for="date-picker-example">点击我...</label>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <h6 class="mt-3">城市</h6>
                        <input type="text" id="city" name="profile[city]" class="form-control mt-4" value="{{ $user->profile['city'] }}">
                    </div>
                </div>
                <div class="row col-xl-12 pl-4">
                    <button type="submit"
                            class="btn btn-sm btn-primary waves-effect waves-light">保存
                    </button>
                </div>
            </div>
            </form>
        </div>
        <div class="modal fade" id="centralModallg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title w-100" id="myModalLabel">上传头像</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row pl-2 pr-2">
                            <form class="md-form mt-0 mb-0" action="#">
                                <div class="file-field">
                                    <div class="btn blue-gradient btn-sm float-left">
                                        <span><i class="fas fa-cloud-upload-alt mr-2" aria-hidden="true"></i>请选择文件</span>
                                        <input type="file" class="file-upload" multiple>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="row pl-2 pr-2 mt-3 crop-wrap">
                            <div class="pl-2 pr-2 col-xl-9">
                                <img src="" alt="" id="upload-pre">
                            </div>
                            <div class="col-xl-3 pl-4">
                                <div id="crop-pre" class="mb-3">
                                </div>
                                <div class="btn-wrap">
                                    <button class="btn btn-primary btn-sm ml-0" id="CropperZoomUp" data-toggle="tooltip" data-placement="auto" title="放大"><i class="fas fa-search-plus"></i></button>
                                    <button class="btn btn-primary btn-sm ml-0" id="CropperZoomDown" data-toggle="tooltip" data-placement="auto" title="缩小"><i class="fas fa-search-minus"></i></button>
                                    <button class="btn btn-primary btn-sm ml-0 mr-0" id="resetCropper" data-toggle="tooltip" data-placement="auto" title="重置"><i class="fas fa-redo-alt"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">关闭</button>
                        <button type="button" class="btn btn-primary btn-sm" id="saveAvatar">保存</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('partScript')
    <script type="text/javascript" src="{{ asset('dist/profile/js/index.js') }}"></script>
    <script>
        // 表单验证
        $('#edit').validate({
            rules: {
                signature: {
                    required: true
                }
            },
            messages: {
                signature: {
                    required: "一句话描述自己"
                }
            }
        });

        edu.file_upload({
            elm: '.file-upload',
            callback: (res) => {
                $('#saveAvatar').on({
                    click: function () {
                        const { cropper, files } = res;
                        cropper && cropper.crop() && cropper.getCroppedCanvas().toBlob((blob) => {
                            edu.ajax({
                                url: $('.avatar-wrap img').data('token'),
                                method: 'get',
                                callback: (res) => {
                                    const token = res.data.domain;
                                        if(res.status !== 'error') {
                                        edu.qiniu_upload({
                                            token: res.data.token,
                                            blob,
                                            key: res.data.media_uri,
                                            callback: (res) => {
                                                if(res.status === 'complete') {
                                                    $('#centralModallg').modal('hide');
                                                    $('#userAvatar').attr('src', token + '/' + res.data.key);
                                                    $('input[name=avatar]').val(res.data.key);
                                                }
                                            }
                                        })
                                    }
                                }
                            })
                        });
                    }
                })
            }
        });
    </script>
@endsection