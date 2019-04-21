$(function () {
    $(`#${openCropper}`).on({
        click: function (e) {
            const modalElm = $(`#${modalId}`)
            e.stopPropagation();
            modalElm.modal('show', function() {
                console.log(111);
            });
            var $image = modalElm.find('#image');
            $image.attr('src', $(`#${imageInput}`).val());

            var $download = modalElm.find('#download');
            //获取图片截取的位置
            var $dataX = modalElm.find('#dataX');
            var $dataY = modalElm.find('#dataY');
            var $dataHeight = modalElm.find('#dataHeight');
            var $dataWidth = modalElm.find('#dataWidth');
            var $dataRotate = modalElm.find('#dataRotate');
            var $dataScaleX = modalElm.find('#dataScaleX');
            var $dataScaleY = modalElm.find('#dataScaleY');
            var options = {
                aspectRatio: aspectRatio || 1 , //裁剪框比例1:1
                preview: '.img-preview',
                responsive: true,
                modal: false,
                crop: function (e) {
                $dataX.val(Math.round(e.x));
                $dataY.val(Math.round(e.y));
                $dataHeight.val(Math.round(e.height));
                $dataWidth.val(Math.round(e.width));
                $dataRotate.val(e.rotate);
                $dataScaleX.val(e.scaleX);
                $dataScaleY.val(e.scaleY);
            }
        };
            var originalImageURL = $image.attr('src');
            var uploadedImageURL;

            // Tooltip
            $(`${modalElm}[data-toggle="tooltip"]`).tooltip();


            // Cropper
            setTimeout(() => {
                $image.on({
                    ready: function (e) {
                        console.log(e.type);
                    },
                    cropstart: function (e) {
                        console.log(e.type, e.action);
                    },
                    cropmove: function (e) {
                        console.log(e.type, e.action);
                    },
                    cropend: function (e) {
                        console.log(e.type, e.action);
                    },
                    crop: function (e) {
                        console.log(e.type, e.x, e.y, e.width, e.height, e.rotate, e.scaleX, e.scaleY);
                    },
                    zoom: function (e) {
                        console.log(e.type, e.ratio);
                    }
                }).cropper(options);
            }, 200)

            // 按钮的点击事件
            modalElm.find('.docs-buttons').on('click', '[data-method]', function () {
                var $this = $(this);
                var data = $this.data();
                var $target;
                var result;

                if ($this.prop('disabled') || $this.hasClass('disabled')) {
                    return;
                }

                if ($image.data('cropper') && data.method) {
                    data = $.extend({}, data); // Clone a new one

                    if (typeof data.target !== 'undefined') {
                        $target = $(data.target);

                        if (typeof data.option === 'undefined') {
                            try {
                                data.option = JSON.parse($target.val());
                            } catch (e) {
                                console.log(e.message);
                            }
                        }
                    }

                    if (data.method === 'rotate') {
                        $image.cropper('clear');
                    }

                    result = $image.cropper(data.method, data.option, data.secondOption);

                    if (data.method === 'rotate') {
                        $image.cropper('crop');
                    }

                    switch (data.method) {
                        case 'scaleX':
                        case 'scaleY':
                            $(this).data('option', -data.option);
                            break;
                        //上传图片
                        case 'getCroppedCanvas':
                            $('#image-format-loading').show()
                            const base64Image = result.toDataURL();
                            $.ajax({
                                url: qiNiuToken,
                            type: 'POST',
                            dataType: 'JSON',
                            data: {'_token': csrf_token},
                            success: function (response) {
                                const token = response.uptoken;
                                // putb{{$}}64(token, base64Image, $image);
                            },
                            error: function (error) {

                            }
                    });
                    break;
                case 'destroy':
                    if (uploadedImageURL) {
                        URL.revokeObjectURL(uploadedImageURL);
                        uploadedImageURL = '';
                        $image.attr('src', originalImageURL);
                    }

                    break;
                }

                    if ($.isPlainObject(result) && $target) {
                        try {
                            $target.val(JSON.stringify(result));
                        } catch (e) {
                            console.log(e.message);
                        }
                    }

                }
            });

            // 图片上传
            const $inputImage = $(`#${modalId}Image`);
            $inputImage.change(function () {
                var files = this.files;
                var file;

                if (!$image.data('cropper')) {
                    return;
                }

                if (files && files.length) {
                    file = files[0];

                    if (/^image\/\w+$/.test(file.type)) {
                        if (uploadedImageURL) {
                            URL.revokeObjectURL(uploadedImageURL);
                        }

                        uploadedImageURL = URL.createObjectURL(file);
                        $image.cropper('destroy').attr('src', uploadedImageURL).cropper(options);
                        $inputImage.val('');
                    } else {
                        window.alert('Please choose an image file.');
                    }
                }
            });
        }
    })
});

callbackObj  = callbackObj || {};

callbackObj[`put${modalId}64`] = function (token, imageBase64, imageBtn) {
    let pic = imageBase64.replace(/^.*?base64,/, '')
    let url = "http://upload-z1.qiniup.com/putb64/-1";
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status === 200) {
                const imageName = JSON.parse(xhr.responseText).key;
                putb64_callback();
                $('#image-format-loading').hide()
            }
        }
    }
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/octet-stream");
    xhr.setRequestHeader("Authorization", "UpToken " + token);
    xhr.send(pic);
}

console.log(callbackObj);

function baseCode64(input) {
    var _keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    var output = "";
    var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
    var i = 0;
    input = _utf8_encode(input);
    while (i < input.length) {
        chr1 = input.charCodeAt(i++);
        chr2 = input.charCodeAt(i++);
        chr3 = input.charCodeAt(i++);
        enc1 = chr1 >> 2;
        enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
        enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
        enc4 = chr3 & 63;
        if (isNaN(chr2)) {
            enc3 = enc4 = 64;
        } else if (isNaN(chr3)) {
            enc4 = 64;
        }
        output = output +
            _keyStr.charAt(enc1) + _keyStr.charAt(enc2) +
            _keyStr.charAt(enc3) + _keyStr.charAt(enc4);
    }
    return output;
}

function _utf8_encode(string) {
    string = string.replace(/\r\n/g, "\n");
    var utftext = "";
    for (var n = 0; n < string.length; n++) {
        var c = string.charCodeAt(n);
        if (c < 128) {
            utftext += String.fromCharCode(c);
        } else if ((c > 127) && (c < 2048)) {
            utftext += String.fromCharCode((c >> 6) | 192);
            utftext += String.fromCharCode((c & 63) | 128);
        } else {
            utftext += String.fromCharCode((c >> 12) | 224);
            utftext += String.fromCharCode(((c >> 6) & 63) | 128);
            utftext += String.fromCharCode((c & 63) | 128);
        }

    }
    return utftext;
}

// $("#{{ $modalId }}").bind('hide.bs.modal',function(){
//     $(".modal-backdrop").remove();
// })