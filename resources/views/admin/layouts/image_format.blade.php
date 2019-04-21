<link rel="stylesheet" href="/backstage/global/vendor/cropper/cropper.css">
<style>
    #image-format-loading {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 15000;
    }

    #image-format-loading img {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 80px;
        height: 80px;
        margin-top: -15px;
        margin-left: -15px;
    }
</style>
<div id="aetherupload-output"></div>
<div id="aetherupload-progressbar"></div>
<div id="aetherupload-savedpath"></div>
<div class="modal fade " id="{{ $modalId }}" aria-labelledby="{{ $modalId }}Label" role="dialog" tabindex="-1"
     aria-hidden="true">
    <div id="image-format-loading" style="display: none">
        <img src="{{ asset('imgs/loading.gif') }}">
    </div>
    <div class="modal-dialog modal-simple @if(!isset($simple)) modal-lg @endif">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="{{$modalId}}">图片裁剪</h6>
                <button type="button" class="close" id="image-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" id="close">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-md-12">
                        <div class="img-container" style="width: 100%;">
                            <img id="image" src="" style="height: {{$imageHeight}}px">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="docs-buttons">
                    <div class="btn-group">
                        <label class="btn btn-primary btn-upload" for="{{$modalId}}Image " title="Upload image file"
                               style="margin-bottom: 0px">
                            <input type="file" class="sr-only" id="{{$modalId}}Image" name="file"
                                   accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff"
                                   style="width: 90px;height: 50px;opacity: 0;clip:unset;">
                            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false">
                                 上传图片
                                </span>
                        </label>
                    </div>

                    <button type="button" class="btn btn-primary reset" data-method="reset" title="Reset"
                            style="display: none">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title=""
                  data-original-title="$().cropper(&quot;reset&quot;)">
              刷新
            </span>
                    </button>

                    <div class="btn-group btn-group-crop">
                        <button class="btn btn-primary" data-method="getCroppedCanvas"
                                data-option="@if(isset($imageWidth) && isset($imageHeight)){ &quot;width&quot;: {{$imageWidth}}, &quot;height&quot;: {{$imageHeight}} }@endif"
                                type="button">
                            确定裁剪
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/backstage/global/vendor/jquery/1.11.3/jquery.min.js"></script>
<script src="/backstage/global/vendor/bootstrap/bootstrap.js"></script>

<script src="{{ mix('/js/upload/image-aetherupload.js') }}"></script>
<script>
    $(function () {
        $('#{{ $openCropper }}').on({
            click: function (e) {
                e.stopPropagation();
                console.log($('#{{ $modalId }}'));
                $('#{{ $modalId }}').modal('show').on('shown.bs.modal', function () {
                    var $image = $('#{{$modalId}} #image');
                    $image.attr('src', $('{{$imageInput}}').val());
                    var $download = $('#{{$modalId}} #download');
                    //获取图片截取的位置
                    var $dataX = $('#{{$modalId}} #dataX');
                    var $dataY = $('#{{$modalId}} #dataY');
                    var $dataHeight = $('#{{$modalId}} #dataHeight');
                    var $dataWidth = $('#{{$modalId}} #dataWidth');
                    var $dataRotate = $('#{{$modalId}} #dataRotate');
                    var $dataScaleX = $('#{{$modalId}} #dataScaleX');
                    var $dataScaleY = $('#{{$modalId}} #dataScaleY');
                    var options = {
                        aspectRatio: {{ $aspectRatio ?? 1 }} , //裁剪框比例1:1
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
                    {{--$('#{{$modalId}} [data-toggle="tooltip"]').tooltip();--}}


                    // Cropper
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

                    // 按钮的点击事件
                    $('#{{$modalId}} .docs-buttons').on('click', '[data-method]', function () {
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

                                    // 获取当前的配置环境
                                    @php
                                        $setting = \Facades\App\Models\Setting::namespace('qiniu');

                                        $driver = data_get($setting, 'driver', config('filesystems.default'));
                                    @endphp

                                    //调用
                                    const blob = dataURLtoBlob(base64Image);

                                    // 随机生成文件名
                                    const fileName = Math.random().toString(36).substr(2) + '.' + blob.type.substring(blob.type.lastIndexOf('/')+1);

                                    //  转换为文件
                                    var file = blobToFile(blob, fileName);

                                    @if($driver == 'local')
                                        aetherupload(file, 'image').success(function(){
                                            {{ $localSuccessCallback ?? null }}
                                        }).upload();
                                    @else
                                        $.ajax({
                                            url: '{{ route('backstage.qi_niu.token') }}',
                                            type: 'POST',
                                            dataType: 'JSON',
                                            data: {'_token': '{{csrf_token()}}'},
                                            success: function (response) {
                                                const token = response.uptoken;
                                                putb{{$modalId}}64(token, base64Image, $image);
                                            },
                                            error: function (error) {

                                            }
                                        });
                                    @endif
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
                    const $inputImage = $('#{{$modalId}}Image');
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
                });
            }
        })
    });

    function putb{{$modalId}}64(token, imageBase64, imageBtn) {
        let pic = imageBase64.replace(/^.*?base64,/, '')
        let url = "http://upload-z1.qiniup.com/putb64/-1";
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4) {
                if (xhr.status === 200) {
                    const imageName = JSON.parse(xhr.responseText).key;
                    {{ $imageScript }}
                    $('#image-format-loading').hide()
                }
            }
        }
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/octet-stream");
        xhr.setRequestHeader("Authorization", "UpToken " + token);
        xhr.send(pic);
    }


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

    $("#{{ $modalId }}").bind('hide.bs.modal', function () {
        $(".modal-backdrop").remove();
    })



    //将base64转换为blob
    function dataURLtoBlob(dataurl) {
        var arr = dataurl.split(','),
            mime = arr[0].match(/:(.*?);/)[1],
            bstr = atob(arr[1]),
            n = bstr.length,
            u8arr = new Uint8Array(n);
        while (n--) {
            u8arr[n] = bstr.charCodeAt(n);
        }
        return new Blob([u8arr], { type: mime });
    }

    //将blob转换为file
    function blobToFile(theBlob, fileName){
        theBlob.lastModifiedDate = new Date();
        theBlob.name = fileName;
        return theBlob;
    }




</script>

