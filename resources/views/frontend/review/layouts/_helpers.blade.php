<script>
    /**
     * 公共函数文件
     */
    (function () {
        // 表单验证的 validator 设置
        $.validator.setDefaults({

            // 是否开启 dubug 模式
            debug: false,
            // 对隐藏域也进行校验
            // ignore : [],
            highlight : function(element) {
                $(element).closest('input').addClass('border-danger');
                $(element).closest('input').parent().find('.input-group-text').addClass('border-danger');
            },
            unhighlight : function(element) {
                $(element).closest('input').removeClass('border-danger');
                $(element).closest('input').parent().find('.input-group-text').removeClass('border-danger');
            },
            errorElement : 'div',
            errorClass : 'invalid-feedback',
            errorPlacement : function(error, element) {
                if (element.parents('.input-group-transparent').length) {
                    error.insertAfter(element.parents('.input-group-transparent'));
                } else {
                    error.insertAfter(element);
                }
            }

        });

    })();

    /**
     * ----------------------------------------------------------------------------
     *
     * 转换 html 实体
     *
     * ----------------------------------------------------------------------------
     */
    var HtmlToEntity = function (data) {

        var type;

        for (i in data) {

            type = typeof data[i];

            if (type == 'string') {
                data[i] = data[i].replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&apos;");
            } else if (type == 'object' || type == 'array') {
                data[i] = HtmlToEntity(data[i]);
            }
        }

        return data;

    };

    /**
     * ----------------------------------------------------------------------------
     *
     * 表单验证 jquery validator
     *
     * ----------------------------------------------------------------------------
     * @type {init: FormValidator.init}
     */
    var FormValidator = {

        init: function (ele, rules, callback, container = null) {
            rules.submitHandler = function (form) {

                return callback(form);
            };

            if (container !== null) {
                rules.errorPlacement = function (error, element) {

                    // 如果同级元素的兄弟元素中存在消息提示框，优先使用同级元素，如果不存在，则使用父级元素的兄弟元素中的消息提示框
                    var container = element.nextAll('.control');
                    if (container.length > 0) {
                        error.appendTo(container);
                    } else {
                        error.appendTo(element.parent().nextAll('.control'));
                    }
                };
            }

            ele.validate(rules);
        }

    };

</script>