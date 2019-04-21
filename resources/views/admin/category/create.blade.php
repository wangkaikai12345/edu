<form class="modal-content form-horizontal" id="createCategoryGroupForm" autocomplete="off" action="javaScript:"
      method="POST">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="mySimpleModal">添加分类分组</h4>
    </div>
    {{ csrf_field() }}
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-md-3 form-control-label">
                名称
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="title"/>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 form-control-label">
                编码
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="name"/>
            </div>
        </div>
    </div>
     <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="validateCreateCategoryGroupButton">提交</button>
    </div>
</form>
@include('admin.layouts.validation')

<script>
    (function () {
        formValidationAjax('#createCategoryGroupForm', '#validateCreateCategoryGroupButton', {
            title: {
                validators: {
                    notEmpty: {
                        message: '名称不能为空.'
                    },
                    stringLength: {
                        min: 1,
                        max: 30,
                        message: '名称最短1个字符,最长30字符.'
                    },
                    remote: {
                        url: "{{ route('backstage.categoryGroup.verify') }}",
                        type: 'POST',
                        data: {
                            'key': 'title',
                            '_token': "{{ csrf_token() }}"
                        },
                        validkey: 'valid',
                        delay: 2000,
                        message: '名称重复.'
                    }
                }
            },
            name: {
                validators: {
                    notEmpty: {
                        message: '编码不能为空.'
                    },
                    stringLength: {
                        max: 30,
                        message: '编码最长30字符.'
                    },
                    remote: {
                        url: "{{ route('backstage.categoryGroup.verify') }}",
                        type: 'POST',
                        data: {
                            'key': 'name',
                            '_token': "{{ csrf_token() }}"
                        },
                        validkey: 'valid',
                        delay: 2000,
                        message: '编码重复.'
                    }
                }
            }
        }, function ($form) {
            return serializeObject($form)
        }, "{{ route('backstage.categoryGroup.store') }}", 'POST', true, true, true)
    })();
</script>