<style>
    .form-horizontal .checkbox-custom, .form-horizontal .radio-custom {
        padding-top:0px;
    }
</style>
<form class="modal-content form-horizontal" id="updateUserForm" autocomplete="off" action="javaScript:"
      method="POST">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myLgModal">用户编辑</h4>
    </div>
    {{ csrf_field() }}
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-6">
                <label class="form-control-label" for="username">
                    昵称
                    <span class="required">*</span>
                </label>
                <input type="text" class="form-control" id="username" name="username" placeholder="昵称"
                       value="{{ $user->username }}" autocomplete="off">
            </div>
            <div class="form-group col-md-6">
                <label class="form-control-label" for="title">头衔</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="头衔"
                       value="{{ $user->profile->title }}" autocomplete="off">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label class="form-control-label" for="name">
                    真实姓名
                    <span class="required">*</span>
                </label>
                <input type="text" class="form-control" id="name" name="name" placeholder="真实姓名"
                       value="{{ $user->profile->name }}" autocomplete="off">
            </div>
            <div class="form-group col-md-6">
                <label class="form-control-label" for="signature">个人签名</label>
                <input type="text" class="form-control" id="signature" name="signature" placeholder="个人签名"
                       value="{{ $user->signature }}" autocomplete="off">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label class="form-control-label" for="gender">
                    性别
                    <span class="required">*</span>
                </label>
                <div class="form-control">
                    <div class="radio-custom radio-default radio-inline">
                        <input type="radio" id="inputBasicMale" name="gender" value="male"
                               @if($user->profile->gender == 'male') checked @endif>
                        <label for="inputBasicMale">男</label>
                    </div>
                    <div class="radio-custom radio-default radio-inline">
                        <input type="radio" id="inputBasicMale" name="gender" value="female"
                               @if($user->profile->gender == 'female') checked @endif>
                        <label for="inputBasicMale">女</label>
                    </div>
                    <div class="radio-custom radio-default radio-inline">
                        <input type="radio" id="inputBasicMale" name="gender" value="secret"
                               @if($user->profile->gender == 'secret') checked @endif>
                        <label for="inputBasicMale">保密</label>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label class="form-control-label" for="site">个人主页</label>
                <input type="text" class="form-control" id="site" name="site" placeholder="个人主页"
                       value="{{ $user->profile->site }}" autocomplete="off">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label class="form-control-label" for="idcard">
                    身份证号码
                </label>
                <input type="text" class="form-control" id="idcard" name="idcard" placeholder="身份证号码"
                       value="{{ $user->profile->idcard }}"
                       autocomplete="off">
            </div>
            <div class="form-group col-md-6">
                <label class="form-control-label" for="major">专业</label>
                <input type="text" class="form-control" id="major" name="major" placeholder="专业"
                       value="{{ $user->profile->major }}"
                       autocomplete="off">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label class="form-control-label" for="weixin">
                    微信
                </label>
                <input type="text" class="form-control" id="weixin" name="weixin" placeholder="微信"
                       value="{{ $user->profile->weixin }}"
                       autocomplete="off">
            </div>
            <div class="form-group col-md-6">
                <label class="form-control-label" for="weibo">微博</label>
                <input type="text" class="form-control" id="weibo" name="weibo" placeholder="微博"
                       value="{{ $user->profile->weibo }}"
                       autocomplete="off">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label class="form-control-label" for="qq">
                    QQ
                </label>
                <input type="text" class="form-control" id="qq" name="qq" placeholder="QQ"
                       value="{{ $user->profile->qq }}"
                       autocomplete="off">
            </div>
            <div class="form-group col-md-6">
                <label class="form-control-label" for="school">毕业学校</label>
                <input type="text" class="form-control" id="school" name="school" placeholder="毕业学校"
                       value="{{ $user->profile->school }}"
                       autocomplete="off">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label class="form-control-label" for="company">
                    公司
                </label>
                <input type="text" class="form-control" id="company" name="company" placeholder="公司"
                       value="{{ $user->profile->company }}"
                       autocomplete="off">
            </div>
            <div class="form-group col-md-6">
                <label class="form-control-label" for="job">职业</label>
                <input type="text" class="form-control" id="job" name="job" placeholder="职业"
                       value="{{ $user->profile->job }}"
                       autocomplete="off">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <label class="form-control-label" for="about">
                    个人介绍
                </label>
                <textarea class="form-control" name="about" placeholder="个人介绍">{{ $user->profile->about }}</textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="validateUserButton">提交</button>
    </div>
</form>
<script>
    (function () {
        formValidationAjax('#updateUserForm', '#validateUserButton', {
            username: {
                validators: {
                    notEmpty: {
                        message: '昵称不能为空.'
                    },
                    stringLength: {
                        min: 2,
                        max: 30,
                        message: '昵称最短2个字符,最长30字符.'
                    }
                }
            },
            title: {
                validators: {
                    stringLength: {
                        max: 50,
                        message: '头衔最长50字符.'
                    }
                }
            },
            name: {
                validators: {
                    notEmpty: {
                        message: '真实姓名不能为空.'
                    },
                    stringLength: {
                        min: 2,
                        max: 30,
                        message: '真实姓名最短2个字符,最长30字符.'
                    }
                }
            },
            gender: {
                validators: {
                    notEmpty: {
                        message: '性别不能为空.'
                    }
                }
            },
            site: {
                validators: {
                    uri: {
                        message: '个人主页应为网址.'
                    },
                    stringLength: {
                        max: 150,
                        message: '个人主页最长150字符.'
                    }
                }
            },
            idcard: {
                validators: {
                    id: {
                        country: 'CN',
                        message: '身份证错误.'
                    }
                }
            },
            major: {
                validators: {
                    stringLength: {
                        max: 50,
                        message: '专业最长50字符.'
                    }
                }
            },
            weixin: {
                validators: {
                    stringLength: {
                        max: 50,
                        message: '专业最长50字符.'
                    }
                }
            },
            weibo: {
                validators: {
                    uri: {
                        message: '微博应为网址.'
                    },
                    stringLength: {
                        max: 150,
                        message: '微博最长150字符.'
                    }
                }
            },
            qq: {
                validators: {
                    stringLength: {
                        max: 15,
                        message: 'QQ最长15字符.'
                    }
                }
            },
            school: {
                validators: {
                    stringLength: {
                        max: 30,
                        message: '毕业学校最长30字符.'
                    }
                }
            },
            company: {
                validators: {
                    stringLength: {
                        max: 30,
                        message: '公司最长30字符.'
                    }
                }
            },
            job: {
                validators: {
                    stringLength: {
                        max: 30,
                        message: '工作最长30字符.'
                    }
                }
            }
        }, function ($form) {
            return serializeObject($form)
        }, "{{ route('backstage.users.update', ['user' => $user->id]) }}", 'PUT', true)
    })();
</script>