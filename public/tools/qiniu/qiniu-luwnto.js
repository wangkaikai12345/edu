/**
 * 七牛前端上传封装
 * 该文件依赖 qiniu2.min.sj  qetag.sj sha1.js, 请务必保证在这三个文件之后引用
 */


// 设置 ajax 请求头
$.ajaxSetup({
    dataType: 'json',
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {

        // 数据验证未通过
        if (XMLHttpRequest.status == '422') {

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

/**
 * 上传文件并自动验证是否重复
 *
 * @param files 要上传的文件, 数组
 * @param url 获取上传token的url
 * @param type 要上传的资源类型, 即目标库, 参考配置文件 filesystems
 * @param fileType 要限制的文件上传类型 ['']
 * @param callback 上传成功之后的回调
 * @returns {boolean}
 */
function uploadFile(files, url, type, fileType, callback) {
    if (files.length <= 0) {
        callback('err', {message: '缺少文件'});
        return false;
    }
    var file = files[0];

    // 获取当前的配置环境
    getFileHash(file, function (hash) {
        // 发送Ajax, 验证文件是否存在, 同时返回
        $.ajax({
            url: url,
            type: 'POST',
            data: {hash: hash, type: type},
            success: function (res) {
                if (res.status == 200) {
                    // 执行上传
                    var _res = res;

                    qiniuUpload(file, res.data, fileType, function (type, res) {
                        res.domain = _res.data.domain;
                        callback(type, res);
                    });
                } else if (res.status == 412) {
                    callback('exist', res);
                }
            }
        });

    });
}

/**
 * 生成文件hash的函数
 *
 * @param f 要验证的文件
 * @param callback 验证之后的回调函数
 */
function getFileHash(f, callback) {
    var reader = new FileReader();
    reader.readAsArrayBuffer(f);
    reader.onload = function () {
        callback(getEtag(this.result));
    };
}

/**
 * 七牛前端上传
 *
 * @param file 要上传的文件
 * @param data 后端获取的token, key等数据
 * @param type 限制上传的资源类型
 * @param callback 上传成功只有的回调
 */
function qiniuUpload(file, data, type, callback) {
    var putExtra = {
        fname: "",
        params: {},
        mimeType: type || null
    };

    var config = {
        useCdnDomain: true,
        region: qiniu.region.z1,
    };

    var observable = qiniu.upload(file, data['key'], data['token'], putExtra, config);
    var observer = {
        next: function (res) {
            callback('next', res);
        },
        error: function (err) {
            callback('err', err);
        },
        complete: function (res) {
            callback('complete', res);
        }
    };
    var subscription = observable.subscribe(observer);
}
