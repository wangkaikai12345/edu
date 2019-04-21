import $ from 'jquery';
import 'bootstrap/js/dist/modal';

if (!window.$ || !window.jQuery) {
    window.$ = window.jQuery = $;

    // const _ajax = $.ajax;
    //
    // $.extend({
    //     ajax: function() {
    //         const completeFn = arguments[0].complete
    //             , data = {
    //             ...arguments[0],
    //             complete: () => {
    //                 $('a, button').removeClass('disabled').attr('disabled', false);
    //                 completeFn && completeFn();
    //             }
    //         };
    //         $('a, button').addClass('disabled').attr('disabled', true);
    //        _ajax.call(null, data);
    //     }
    // })
}

// 设置 ajax 请求头
$.ajaxSetup({
    dataType: 'json',
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {

        // 数据验证未通过
        if (XMLHttpRequest.status == '422') {
            let message;
            var result = XMLHttpRequest.responseJSON;
            for (let i in result.errors) {
                message = result.errors[i][0];
                break;
            }

            edu.alert('danger', message);

        } else {
            edu.alert('danger', '请求服务器失败，请确认您的网络可用！');
        }
    }
});

class edu__proto__proto {
    constructor() {
        this.$ = $;
    }
}

class edu_proto extends edu__proto__proto {
    constructor(props) {
        super(props);
        this.inited = false;
        this.notification_num = 0;
    }

    init = () => {
        let html = `<div class="zh_notification" id="zh_notification"></div>`;
        $('body').append(html);
        this.inited = true;
    };

    add_notification = (props) => {
        return new Promise((resolve, reject) => {
            try {
                let randomId = this.randomRangeId(20)
                    ,
                    html = `<div class="alert alert-${props.type} alert-dismissible fade show zh_alert zh_alert_${this.notification_num}" id="${randomId}" role="alert">
                                <span class="alert-inner--text">${props.message}</span>
                            </div>`;

                $('#zh_notification').append(html);

                this.notification_num++;

                resolve(randomId);

            } catch (e) {

                reject(e);
                throw e;

            }
        })
    };

    remove_notification = (props) => {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                $(`#${props.randomId}`).css({
                    'marginTop': '-77px'
                });
                setTimeout(() => {
                    $(`#${props.randomId}`).remove();
                    this.notification_num--;
                }, 1000)
            }, 3000 + this.notification_num * 300);
        })
    };

    sleep = (time) => {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                try {
                    resolve();
                } catch (e) {
                    reject();
                }
            }, time * 1000)
        })
    };

    alert = async (type, message, sleep) => {
        if (!this.inited) {
            this.init();
        }

        if (this.notification_num < 10) {
            await this.sleep(sleep);

            const randomId = await this.add_notification({type, message});

            const remove_result = await this.remove_notification({randomId});
        }

    };

    confirm = (props) => {
        let {type, title, message, dataType, callback} = props
            , html = `<div class="modal modal-${type} fade" id="confirm_modal" tabindex="-1" role="dialog" aria-labelledby="confirm_modal" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modal_title_6">${title}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    ${dataType === 'html' ? message : `<div class="py-3 text-center">
                                        <i class="fas fa-exclamation-circle fa-4x"></i>
                                        <p>
                                            ${message}
                                        </p>
                                    </div>`}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-dismiss="modal" id="confirm_close">取消</button>
                                    <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal" id="confirm_enter">确认</button>
                                </div>
                            </div>
                        </div>
                    </div>`;
        $('body').append(html);

        $('#confirm_modal').modal('toggle');

        $('#confirm_enter').on({
            click: () => {
                callback({
                    type: 'success'
                });
                $('#confirm_modal').remove();
            }
        });

        $("#confirm_modal").bind('hide.bs.modal',function(){
            $(".modal-backdrop").remove();
        });

        $('#confirm_close').on({
            click: () => {
                callback({
                    type: 'close'
                });
                $('#confirm_modal').remove();
            }
        })
    };

    throttle = (func, wait = 500, mustRun = 1000) => {
        var timeout,
            startTime = new Date();
        return function () {
            var context = this,
                args = arguments,
                curTime = new Date();
            clearTimeout(timeout);
            if (curTime - startTime >= mustRun) {
                func.apply(context, args);
                startTime = curTime;
            } else {
                timeout = setTimeout(func, wait);
            }
        };
    };
}


edu_proto.prototype.randomRangeId = (num) => {
    let returnStr = "",
        charStr = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    for (let i = 0; i < num; i++) {
        let index = Math.round(Math.random() * (charStr.length - 1));
        returnStr += charStr.substring(index, index + 1);
    }

    return returnStr;
};

export default edu_proto;