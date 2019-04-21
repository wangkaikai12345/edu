import aetherupload from "../../../upload/image-aetherupload";

$(function () {
    $('.modal').on('hide.bs.modal',function(){
        console.log(1);
        $(".modal-backdrop").remove();
    })
    $('#note_editorjs').summernote({
            placeholder: '请输入内容...',
            tabsize: 2,
            height: 500,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'add-text-tags', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                [ ['fontsize']],
                [['picture']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
            ],
            lang: 'zh-CN',
            callbacks: {
                onImageUpload: async function(files) {

                    const file = files[0]
                        , driver = $('.driver').attr('data-driver')
                        , token = $('.upload_token').attr('data-token');

                    let imgUrl = '';


                    if(!driver) {
                        throw '请检查页面驱动是否存在！';
                    }

                    if(driver === 'local') {

                        await new Promise((resolve => {

                            aetherupload(file, 'file')

                                .success(function () {
                                    imgUrl = `/${this.savedPath}`;
                                    resolve();
                                })

                                .upload();

                        }))

                    }else {

                        if(!uploadFile) {
                            throw '请检查页面是否引入七牛上传！';
                        }

                        if(!token) {
                            throw '请检查页面token 是否存在！';
                        }

                        await new Promise(((resolve, reject) => {
                            uploadFile(files, token, 'img', '', (type, res) => {
                                if(type === 'complete') {
                                    imgUrl = `${res.domain}/${res.key}`;
                                    resolve();
                                }
                                if(type === 'error') {
                                    edu.alert('danger', '上传失败！');
                                    throw '上传失败！';
                                }
                            })
                        }))
                    }

                    const imgNode = document.createElement('img');

                    imgNode.style.width = '50%';

                    imgNode.src = imgUrl;

                    $('#editorjs').summernote('insertNode', imgNode);

                    edu.alert('success','上传成功！')
                }
            }
        });

    $(document).on('click', '.heightSwitch', function () {
        if ($(this).html() == '收起') {
            $(this).parent().prev().removeClass('fullText').addClass('retract');
            $(this).html('全文');
        } else if ($(this).html() == "全文") {
            $(this).parent().prev().removeClass('retract').addClass('fullText');
            $(this).html('收起');
        }
    });

    /**
     * 笔记出来的函数
     * @param page
     * @param route 路由的id元素
     * @param con 要塞进去的容器的id元素
     * @param more 查看更多的id元素
     */
    function goNote(page, route, con, more) {
        var data = {};
        if (page) {
            data.page = page;
        }

        $.ajax({
            url: $('#' + route).data('route'),
            type: 'get',
            data: data,
            success: function (res) {

                if (res.status == '200') {

                    if(res.data.data.length === 0) {
                        $('#' + con).addClass('no_data').html('暂无数据...');
                    }

                    // 判断是否展示加载更多
                    if (res.data.data.length < 5) {
                        $('#' + more).attr('style', 'display:none');
                    } else {
                        $('#' + more).attr('style', 'display:block');
                        $('#' + more).attr('data-page', res.data.current_page + 1);
                    }

                    // 填充数据
                    res.data.data.map(function (item) {
                        // var note_content = item.content;
                        var note_content = item.content.slice(3, item.content.length - 4);
                        $('#' + con).append(`
                             <div class="note_item">
                                <div class="retract">
                                    <p class="noteTitle">
                                        ${note_content}
                                    </p>
                                </div>
                                <div class="titleHeight">
                                    <p class="heightSwitch">全文</p>
                                </div>
                                <div class="noteData">
                                    <p class="noteCreateTime">${item.created_at}</p>
                                </div>
                            </div>
                        `).removeClass('no_data').parent().removeClass('no_data');
                    })

                } else {
                    edu.alert('danger', res.message);
                }
            },
        })
    }

// 提交笔记
    $('#submit_note').click(function () {

        // 获取笔记内容
        var content = $('#note_editorjs').summernote('code');

        if (!content) {
            edu.alert('danger', '请输入内容');
            return false;
        }
        // 获取是否公开
        var is_public = $('#is_public').prop('checked') ? 1 : 0;
        var collection = $('#dplayer').data('col') ? $('#dplayer').data('col'): 0;

        // 保存笔记
        $.ajax({
            url: $(this).data('route'),
            method: 'post',
            data: {
                'content': content,
                'is_public': 1,
                'collection': collection,
            },
            success: function (res) {

                if (res.status == '200') {
                    edu.alert('success', '添加评论成功');
                    $('#note_editorjs').summernote('code', '');

                } else {
                    edu.alert('danger', res.message);
                }
            },
        })

    })

// 精选笔记
    $('#selectedNote-tab').click(function () {

        $('#collect_note').empty();

        goNote('', 'selectedNote-tab', 'collect_note', 'more_collect_note');

    })

// 精选笔记 查看更多
    $('#more_collect_note').click(function () {
        var page = $(this).data('page');
        goNote(page, 'selectedNote-tab', 'collect_note', 'more_collect_note');
    })


// 我的笔记
    $('#myNote-tab').click(function () {

        $('#my_note').empty();

        goNote('', 'myNote-tab', 'my_note', 'more_my_note');
    })

// 我的笔记查看更多
    $('#more_my_note').click(function () {
        var page = $(this).data('page');
        goNote(page, 'myNote-tab', 'my_note', 'more_my_note');
    })

// 笔记收藏点赞
    $(document).on('click', '.collect', function () {
        var favourite = $(this);

        var id = $(this).data('id');

        if (!id) {
            alert('收藏出错');
            return false;
        }

        favourite.removeClass('collect');

        $.ajax({
            url: '/favorites',
            method: 'post',
            data: {'model_type': 'note', 'model_id': id},
            success: function (res) {

                if (res.status == '200') {

                    edu.alert('success', res.message);

                    if (!res.data.length) {

                        favourite.removeClass('active').addClass('active');

                        favourite.parents('.fabulousNum').find('.nu').text(parseInt(favourite.parents('.fabulousNum').find('.nu').html()) + 1);

                    } else {
                        favourite.removeClass('active');

                        favourite.parents('.fabulousNum').find('.nu').text(parseInt(favourite.parents('.fabulousNum').find('.nu').html()) - 1);
                    }

                    favourite.addClass('collect');
                }
            }
        })
    })
});

//默认显示内容必须在实例化编辑器之后才能显示
//    ue.ready(function () {
//        var $content = '';
//        //默认显示内容
//        ue.setContent($content);
//        //文本框获取焦点时清空默认显示的内容
//        ue.addListener("focus", function () {
//            var nowText = ue.getContent();
//            if (nowText == $content) {
//                ue.setContent('');
//            }
//        });
//        //文本框是去焦点时,若内容为空则显示默认显示的内容
//        ue.addListener("blur", function () {
//            if (!ue.getContent()) {
//                ue.setContent('');
//            }
//        });
//    });