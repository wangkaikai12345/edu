<form class="modal-content form-horizontal" id="createuserForm" autocomplete="off" action="javaScript:"
      method="POST">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="mySimpleModal">添加导航</h4>
    </div>
    {{ csrf_field() }}
    @if(!empty($parent) && $parent->parent_id == 0)
        <input type="hidden" value="{{$parent->id}}" name="parent_id">
    @endif        <input type="hidden" value="header" name="type">


    <div class="modal-body">
        <div class="form-group row">
            <label class="col-md-3 form-control-label">
                导航名称
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="name" value="{{ $navigation->name ?? null }}"/>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 form-control-label">
                导航链接
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="link" value="{{ $navigation->link ?? null}}"/>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 form-control-label">
                新开窗口
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <div class="radio-custom radio-default radio-inline">
                    <input type="radio" id="target_true" name="target"
                           value="1" @if(empty($navigation->id)) checked=""
                           @else @if($navigation->target) checked="" @endif @endif>
                    <label for="target_true">是</label>
                </div>
                <div class="radio-custom radio-default radio-inline">
                    <input type="radio" id="target_false" name="target"
                           value="0" @if(!empty($navigation->id) && !$navigation->target) checked="" @endif>
                    <label for="target_false">否</label>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 form-control-label">
                状态
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <div class="radio-custom radio-default radio-inline">
                    <input type="radio" id="status_true" name="status"
                           value="1" @if(empty($navigation->id)) checked=""
                           @else @if($navigation->status) checked="" @endif @endif>
                    <label for="status_true">展示</label>
                </div>
                <div class="radio-custom radio-default radio-inline">
                    <input type="radio" id="status_false" name="status"
                           value="0" @if(!empty($navigation->id) && !$navigation->status) checked="" @endif>
                    <label for="status_false">隐藏</label>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="validateCreateUserButton">提交</button>
    </div>
</form>
@include('admin.layouts.validation')
<script>
    (function () {
        @if(empty($navigation->id) && empty($parent))
        formValidationAjax('#createuserForm', '#validateCreateUserButton', {
            name: {
                validators: {
                    notEmpty: {
                        message: '导航名称不能为空.'
                    },
                    stringLength: {
                        min: 2,
                        max: 100,
                        message: '导航名称最短2个字符,最长100字符.'
                    },
                    remote: {
                        url: "{{ route('backstage.settings.nav.verify') }}",
                        type: 'POST',
                        data: {
                            'key': 'name',
                            'type': 'header',
                            '_token': "{{ csrf_token() }}"
                        },
                        validkey: 'valid',
                        delay: 2000,
                        message: '导航名称重复.'
                    }
                }
            },
            link: {
                validators: {
                    notEmpty: {
                        message: '导航链接不能为空.'
                    },
                    uri: {
                        message: '导航链接格式错误.'
                    }
                }
            },
            target: {
                validators: {
                    notEmpty: {
                        message: '新开窗口不能为空.'
                    }
                }
            },
            status: {
                validators: {
                    notEmpty: {
                        message: '状态不能为空.'
                    }
                }
            }
        }, function ($form) {
            return serializeObject($form)
        }, "{{ route('backstage.settings.head.store') }}", 'POST', true, true);
        @elseif(!empty($navigation->id) && !empty($navigation->parent_id))
        formValidationAjax('#createuserForm', '#validateCreateUserButton', {
            name: {
                validators: {
                    notEmpty: {
                        message: '导航名称不能为空.'
                    },
                    stringLength: {
                        min: 2,
                        max: 100,
                        message: '导航名称最短2个字符,最长100字符.'
                    },
                    remote: {
                        url: "{{ route('backstage.settings.nav.verify') }}",
                        type: 'POST',
                        data: {
                            'key': 'name',
                            'type': 'header',
                            '_token': "{{ csrf_token() }}",
                            'navigation_id': "{{ $navigation->id }}",
                            'parent_id': "{{ $navigation->parent_id }}",
                        },
                        validkey: 'valid',
                        delay: 2000,
                        message: '导航名称重复.'
                    }
                }
            },
            link: {
                validators: {
                    notEmpty: {
                        message: '导航链接不能为空.'
                    },
                    uri: {
                        message: '导航链接格式错误.'
                    }
                }
            },
            target: {
                validators: {
                    notEmpty: {
                        message: '新开窗口不能为空.'
                    }
                }
            },
            status: {
                validators: {
                    notEmpty: {
                        message: '状态不能为空.'
                    }
                }
            }
        }, function ($form) {
            return serializeObject($form)
        }, "{{ route('backstage.settings.navigation.update', ['navigation' => $navigation->hashId]) }}", 'PUT', true, true);
        @elseif(!empty($parent))
        formValidationAjax('#createuserForm', '#validateCreateUserButton', {
            name: {
                validators: {
                    notEmpty: {
                        message: '导航名称不能为空.'
                    },
                    stringLength: {
                        min: 2,
                        max: 100,
                        message: '导航名称最短2个字符,最长100字符.'
                    },
                    remote: {
                        url: "{{ route('backstage.settings.nav.verify') }}",
                        type: 'POST',
                        data: {
                            'key': 'name',
                            'type': 'header',
                            '_token': "{{ csrf_token() }}",
                            'parent_id': "{{ $parent->id }}",
                        },
                        validkey: 'valid',
                        delay: 2000,
                        message: '导航名称重复.'
                    }
                }
            },
            link: {
                validators: {
                    notEmpty: {
                        message: '导航链接不能为空.'
                    },
                    uri: {
                        message: '导航链接格式错误.'
                    }
                }
            },
            target: {
                validators: {
                    notEmpty: {
                        message: '新开窗口不能为空.'
                    }
                }
            },
            status: {
                validators: {
                    notEmpty: {
                        message: '状态不能为空.'
                    }
                }
            }
        }, function ($form) {
            return serializeObject($form)
        }, "{{ route('backstage.settings.navigation.store.child', ['navigation' => $parent->hashId]) }}", 'POST', true, true);
        @else
        formValidationAjax('#createuserForm', '#validateCreateUserButton', {
            name: {
                validators: {
                    notEmpty: {
                        message: '导航名称不能为空.'
                    },
                    stringLength: {
                        min: 2,
                        max: 100,
                        message: '导航名称最短2个字符,最长100字符.'
                    },
                    remote: {
                        url: "{{ route('backstage.settings.nav.verify') }}",
                        type: 'POST',
                        data: {
                            'key': 'name',
                            'type': 'header',
                            '_token': "{{ csrf_token() }}",
                            'navigation_id': "{{ $navigation->id }}"
                        },
                        validkey: 'valid',
                        delay: 2000,
                        message: '导航名称重复.'
                    }
                }
            },
            link: {
                validators: {
                    notEmpty: {
                        message: '导航链接不能为空.'
                    },
                    uri: {
                        message: '导航链接格式错误.'
                    }
                }
            },
            target: {
                validators: {
                    notEmpty: {
                        message: '新开窗口不能为空.'
                    }
                }
            },
            status: {
                validators: {
                    notEmpty: {
                        message: '状态不能为空.'
                    }
                }
            }
        }, function ($form) {
            return serializeObject($form)
        }, "{{ route('backstage.settings.navigation.update', ['navigation' => $navigation->hashId]) }}", 'PUT', true, true);
        @endif
    })();
</script>