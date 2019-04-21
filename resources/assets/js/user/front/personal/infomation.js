import aetherupload from '../../upload/image-aetherupload';
import edu_proto from '../../../edu/edu';
const edu = new edu_proto();

$(function () {
    $('#editorjs').summernote({
        placeholder: '请输入内容...',
        tabsize: 2,
        height: 100,
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
            },
            onChange: function(contents, $editable) {
                console.log(contents);
                $('#about').val(contents);
            }
        }
    });

    // 获取自我介绍
    var about = $($('#about').val()).get(0);
    if(about) {
        $('#editorjs').summernote('insertNode', about || '');
    }

});

