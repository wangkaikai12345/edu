@extends('frontend.review.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/personal/information.css') }}">

    <link href="/vendor/summernoteExtra/summernote.css" rel="stylesheet">
    <link rel="stylesheet" href="/vendor/summernoteExtra/summernote-add-text-tags.css">
    <style>
        .note-editor {
            max-width: 785px;
        }
    </style>
@endsection

@section('content')
    <div class="xh_plan_content driver upload_token" data-driver="{{ data_get(\Facades\App\Models\Setting::namespace('qiniu'), 'driver', 'local') }}" data-token="{{ route('manage.qiniu.token.hash') }}">
        <div class="container" style="padding-bottom: 100px">
            <div class="row padding-content">
                @include('frontend.review.personal.navBar')
                <div class="col-xl-9 col-md-12 col-12 form_content p-0">
                    <form action="{{ route('users.update', $user) }}" id="edit" method="post">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                        <div class="card student_style">
                            <div class="card-body row_content">
                                <div class="row_div">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <h6>个人信息</h6>
                                        </div>
                                    </div>
                                    <hr class="personal_hr m-0">
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="col-md-12 row">
                                            <div class="col-md-7">
                                                <div class="form-group col-md-12 row">
                                                    <label class="form-control-label col-md-2 text-left p-0">头像</label>
                                                    <div class="input-group input-group-transparent col-10 p-0"
                                                    >
                                                        <input type="hidden" name="avatar" value="{{ $user->avatar }}">
                                                        <input type="hidden" name="current" value="{{ render_cover($user->avatar, 'avatar') }}">

                                                        <img src="{{ render_cover($user->avatar, 'avatar') }}" alt=""
                                                             data-domain="{{ fileDomain() }}"
                                                             id="openCropperIco" style="width:70px;height:70px">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-5">
                                                <label class="form-control-label">用户名</label>
                                                <div class="input-group input-group-transparent">
                                                    <input type="text" class="form-control" id="input-email" disabled
                                                           placeholder="用户名" value="{{ $user['username'] }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 row">
                                            <div class="form-group col-md-7">
                                                <label class="form-control-label">真实姓名</label>
                                                <div class="input-group input-group-transparent">
                                                    <input type="text" class="form-control col-md-9" id="input-email"
                                                           placeholder="请输入姓名" name="profile[name]" value="{{ $user->profile['name'] }}"
                                                    >
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-control-label">性别</label>
                                                <div class="input-group input-group-transparent">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="radio" class="custom-control-input"
                                                               value="male" {{ $user->profile['gender'] == 'male' ? 'checked' : '' }}
                                                               id="customCheck1" name="profile[gender]">
                                                        <label class="custom-control-label" for="customCheck1">男</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="radio" class="custom-control-input"
                                                               value="female" {{ $user->profile['gender'] == 'female' ? 'checked' : '' }}
                                                               id="customCheck2" name="profile[gender]">
                                                        <label class="custom-control-label" for="customCheck2">女</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="radio" class="custom-control-input"
                                                               value="secret" {{ $user->profile['gender'] == 'secret' ? 'checked' : '' }}
                                                               id="customCheck3" name="profile[gender]">
                                                        <label class="custom-control-label" for="customCheck3">保密</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 row">
                                            <div class="form-group col-md-7">
                                                <label class="form-control-label">头衔</label>
                                                <div class="input-group input-group-transparent">
                                                    <input type="text" class="form-control col-md-9"
                                                           name="profile[title]" value="{{ $user->profile['title'] }}"
                                                           id="input-email" placeholder="请输入你的头衔">
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-control-label">个人签名</label>
                                                <div class="input-group input-group-transparent">
                                                    <input type="text" class="form-control " id="input-email"
                                                           name="signature" value="{{ $user['signature'] }}"
                                                           placeholder="请输入个人签名">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 row">
                                            <div class="form-group col-md-12">
                                                <label class="form-control-label">自我介绍</label>
                                                <div id="editorjs"></div>
                                                {{--<script id="editor" name="profile[about]" type="text/plain">{!! $user->profile['about'] !!}</script>--}}
                                                <input id="about" type="hidden" name="profile[about]" value="{{ $user->profile['about'] }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12 row">
                                            <div class="form-group col-md-7">
                                                <label class="form-control-label">公司</label>
                                                <div class="input-group input-group-transparent">
                                                    <input type="text" class="form-control col-md-9"
                                                           name="profile[company]" value="{{ $user->profile['company'] }}"
                                                           id="input-email">
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-control-label">职业</label>
                                                <div class="input-group input-group-transparent">
                                                    <input type="text" class="form-control "
                                                           name="profile[job]" value="{{ $user->profile['job'] }}"
                                                           id="input-email">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 row">
                                            <div class="form-group col-md-7">
                                                <label class="form-control-label">个人空间/个人网站</label>
                                                <div class="input-group input-group-transparent">
                                                    <input type="text" class="form-control col-md-9"
                                                           name="profile[site]" value="{{ $user->profile['site'] }}"
                                                           id="input-email">
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-control-label">微博</label>
                                                <div class="input-group input-group-transparent">
                                                    <input type="text" class="form-control "
                                                           name="profile[weibo]" value="{{ $user->profile['weibo'] }}"
                                                           id="input-email">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 row">
                                            <div class="form-group col-md-7">
                                                <label class="form-control-label">微信</label>
                                                <div class="input-group input-group-transparent">
                                                    <input type="text" class="form-control col-md-9"
                                                           name="profile[weixin]" value="{{ $user->profile['weixin'] }}"
                                                           id="input-email">
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-control-label">QQ</label>
                                                <div class="input-group input-group-transparent">
                                                    <input type="text" class="form-control "
                                                           name="profile[qq]" value="{{ $user->profile['qq'] }}"
                                                           id="input-email">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 row">
                                            <div class="form-group col-md-7">
                                                <label class="form-control-label">毕业院校</label>
                                                <div class="input-group input-group-transparent">
                                                    <input type="text" class="form-control col-md-9"
                                                           name="profile[school]" value="{{ $user->profile['school'] }}"
                                                           id="input-email">
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-control-label">所学专业</label>
                                                <div class="input-group input-group-transparent">
                                                    <input type="text" class="form-control "
                                                           name="profile[major]" value="{{ $user->profile['major'] }}"
                                                           id="input-email">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 row">
                                            <div class="form-group col-md-7">
                                                <label class="form-control-label">生日</label>
                                                <div class="input-group input-group-transparent">
                                                    <input type="date" class="form-control col-md-9" id="input-email"
                                                           name="profile[birthday]" value="{{ $user->profile['birthday'] }}">
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-control-label">城市</label>
                                                <div class="input-group input-group-transparent">
                                                    <input type="text" class="form-control "
                                                           name="profile[city]" value="{{ $user->profile['city'] }}"
                                                           id="input-email">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 row">
                                            <div class="col-md-12">
                                                <button class="btn btn-primary btn-submit">保存</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @component('admin.layouts.image_format')
                @slot('modalId')
                    imageIcoModal
                @endslot
                @slot('imageUrl')
                    {{ render_cover($user->avatar, 'avatar') }}
                @endslot
                @slot('imageHeight')
                    500
                @endslot
                @slot('openCropper')
                    openCropperIco
                @endslot
                @slot('imageScript')

                    $('#openCropperIco').attr('src', $('#openCropperIco').data('domain')+'/'+imageName);
                    $('input[name="avatar"]').val(imageName);

                    {{--$('#imageIcoModal').modal('hide')--}}
                    $('.close').trigger('click');
                    $('#imageIcoModal .reset').trigger('click');

                @endslot
                @slot('imageInput')
                    input[name="current"]
                @endslot
                @slot('localSuccessCallback')
                    $('#openCropperIco').attr('src', $('#openCropperIco').data('domain')+'/'+this.savedPath);
                    $('input[name="avatar"]').val(this.savedPath);

                    $('.close').trigger('click');
                    $('#imageIcoModal .reset').trigger('click');
                @endslot

            @endcomponent


        </div>
    </div>
@endsection
@section('script')
    <script src="/backstage/global/vendor/cropper/cropper.min.js"></script>
    <script src="/vendor/summernoteExtra/summernote.js"></script>
    <script src="/vendor/summernoteExtra/summernote-zh-CN.js"></script>
    <script src="/vendor/summernoteExtra/summernote-add-text-tags.js"></script>
    <script src="/tools/qiniu/qiniu2.min.js"></script>
    <script src="/tools/sha1.js"></script>
    <script src="/tools/qetag.js"></script>
    <script src="/tools/qiniu/qiniu-luwnto.js"></script>
    <script src="{{ mix('/js/front/personal/infomation.js') }}"></script>

@endsection