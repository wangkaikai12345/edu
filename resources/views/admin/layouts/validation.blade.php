<script src="{{ asset('backstage/global/vendor/formvalidation/formValidation.js') }}"></script>
<script src="{{ asset('backstage/global/vendor/formvalidation/framework/bootstrap4.min.js') }}"></script>
<script>
    // AJAX
    function formValidationAjax(formDom, validateButtonDom, fields, dataCallback, fetchUrl, method, modalClose = false, reload = false, callback = null, reset = true) {
        const fv = $(formDom).formValidation({
            framework: "bootstrap4",
            button: {
                selector: validateButtonDom,
                disabled: 'disabled'
            },
            icon: null,
            fields: fields,
            err: {
                clazz: 'invalid-feedback'
            },
            control: {
                // The CSS class for valid control
                valid: 'is-valid',
                // The CSS class for invalid control
                invalid: 'is-invalid'
            },
            row: {
                invalid: 'has-danger'
            }
        }).on('success.form.fv', function (e) {
            e.preventDefault();

            const $form = $(e.target);
            // 获取数据
            const data = dataCallback($form);
            // 组装数据
            const formData = Object.assign({}, data, {"_token": "{{csrf_token()}}", "_method": method});

            // 进行AJAX请求
            $.ajax({
                url: fetchUrl,
                type: method,
                dataType: 'JSON',
                data: formData,
                success: function (response) {
                    if (reset) {
                        // 清空表单
                        $form.data('formValidation').resetForm(true);
                    }

                    // 提示操作成功
                    notie.alert({'type': 1, 'text': '操作成功', 'time': 1.5});

                    // 关闭模态框
                    if (modalClose) {
                        $('.modal .close').trigger('click');
                    }

                    // 重新加载页面
                    if (reload) {
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);
                    }

                    if (typeof callback === 'function') {
                        callback(response);
                    }
                },
                error: function (error) {

                    // 获取返回的状态码
                    const statusCode = error.status;

                    // 提示信息
                    let message = null;
                    // 状态码判断
                    switch (statusCode) {
                        case 422:
                            message = getFormValidationMessage(error.responseJSON.errors);
                            break;
                        case 201:
                            message = '操作成功';
                            break;
                        default:
                            message = error.responseJSON.message == null ? '操作失败' : error.responseJSON.message;
                            break;
                    }

                    // 弹出提示
                    notie.alert({'type': 3, 'text': message, 'time': 1.5});

                    // 重置按钮
                    $form.data('formValidation').disableSubmitButtons();
                }
            });
        });
    }

    // 获取后台的表单验证错误的信息
    function getFormValidationMessage(messages) {
        for (let i in messages) {
            return messages[i][0];
        }
    }
</script>