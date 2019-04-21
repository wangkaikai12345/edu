import 'Dplayer/DPlayer.min.css';
import DPlayer from 'Dplayer/DPlayer.min.js';
import Hls from 'hls.js';
import edu_proto from '../../../../edu/edu';
import aetherupload from "../../../upload/image-aetherupload";

const edu = new edu_proto();

$(function () {
    window.Hls = Hls;

    let dp = null;

// avi、wmv、mpeg、mp4、mov、mkv、flv、f4v、m4v、rmvb、rm、3gp、dat、ts、mts、vob
    const videoTypeArr = ['avi', 'wmv', 'mp4', 'mov', 'mkv', 'flv', '3gp', 'm4v', 'rmvb'];

    const url = $('#dplayer').data('url')
// const url = 'http://omf806ou0.bkt.clouddn.com/edu%E6%9C%80%E6%96%B0%E5%AE%A3%E4%BC%A0%E7%89%87.mp4'
        , videoType = url.substr(url.lastIndexOf('.') + 1, url.length);

    window.dp = dp = new DPlayer({
        element: document.getElementById('dplayer'),
        autoplay: true, // 自动播放
        screenshot: true, // 截屏
        hotkey: true, // 热键
        video: {  //视频源 包含不同分辨率源
            url, // 视频地址
            type: videoTypeArr.indexOf(videoType) > -1 ? 'auto' : 'hls'
        },
    });

    setInterval(() => {
        $('video').attr('x5-playsinline');
    }, 1000);

    dp.on('screenshot', async (dataurl) => {

        var now_time = dp.video.currentTime;
        $('#dplayer').data('col', parseInt(now_time));

        const driver = $('.driver').attr('data-driver')
            , token = $('.upload_token').attr('data-token');

        let imgUrl = '';

        if(!driver) {
            throw '请检查页面驱动是否存在！';
        }

        if(driver === 'local') {

            await new Promise((resolve => {

                dataurl['name'] = 'dplayer.png';

                aetherupload(dataurl, 'file')

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
                uploadFile([dataurl], token, 'img', '', (type, res) => {
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

        $('#note_editorjs').summernote('insertNode', imgNode);

        edu.alert('success','上传成功！')
    });

    const debug = false;
    let timer = null,
        judegTimer = null,
        tantiTimer = null;

    function judgeInterVal() {
        const localTimer = JSON.parse(window.localStorage.getItem('task_timer'));
        if (!localTimer || !localTimer.status) {
            // 如果没有设置 localstorage
            debug && console.log('其它网页没有设置，开启轮询...');
            clearInterval(timer);
            timer = setInterval(() => {
                timerInit();
            }, 5000);
        } else {
            // 如果有设置 localstorage
            if ((new Date().getTime() - JSON.parse(window.localStorage.getItem('task_timer')).time) / 1000 > 10) {
                debug && console.log('其它网页有设置，且距离上次设置时间大于10s，开启轮询...');
                //   如果 localstorage 存储时间 距离当前时间大于10s,本页面开启轮询
                clearInterval(timer);
                timer = setInterval(() => {
                    timerInit();
                }, 5000);
            } else {
                debug && console.log('其它网页在线...');
                setTimeout(() => {
                    judgeInterVal();
                }, 5000)
            }
        }
    }

    function timerInit(time) {
        window.localStorage.setItem('task_timer', JSON.stringify({'status': true, 'time': new Date().getTime()}));
        // $.ajax({
        //     url: $('#dplayer').data('route'),
        //     method: 'PUT',
        //     data: {
        //         time: time || dp.video.currentTime
        //     },
        //     success: (res) => {
        //
        //         var pro = $('#zh_directory .progress_now');
        //
        //         pro && pro.css('width', res.data.status+'%');
        //
        //         if(res && res.data && res.data.status == 100) {
        //             const aElm = $('#zh_directory .directory_footer a');
        //             aElm && aElm.removeClass('disabled').attr('disabled', false).attr('href', aElm.attr('data-href')).text('任务完成，开始作业');
        //         }
        //     }
        // })
    }

    function autoNextPlan() {
        const nextElm = $('#zh_directory .directory_list .directory_item.active').next();
        // nextElm.length && (window.location.href = nextElm.find('a').attr('href'));
        nextElm.length && nextElm.find('a').trigger('click');
    }

    function browserRedirect() {
        var sUserAgent = navigator.userAgent.toLowerCase();
        var bIsIpad = sUserAgent.match(/ipad/i) == "ipad";
        var bIsIphoneOs = sUserAgent.match(/iphone os/i) == "iphone os";
        var bIsMidp = sUserAgent.match(/midp/i) == "midp";
        var bIsUc7 = sUserAgent.match(/rv:1.2.3.4/i) == "rv:1.2.3.4";
        var bIsUc = sUserAgent.match(/ucweb/i) == "ucweb";
        var bIsAndroid = sUserAgent.match(/android/i) == "android";
        var bIsCE = sUserAgent.match(/windows ce/i) == "windows ce";
        var bIsWM = sUserAgent.match(/windows mobile/i) == "windows mobile";
        if (bIsIpad || bIsIphoneOs || bIsMidp || bIsUc7 || bIsUc || bIsAndroid || bIsCE || bIsWM) {
            return 'mobile';
        } else {
            return 'pc';
        }
    }

    dp.on('play', function () {
        timer = setInterval(timerInit, 5000);
        judgeInterVal();
        tantiTimer = setInterval(() => {
            if (!timeArr.length) clearInterval(tantiTimer);

            if (dp.video.currentTime > timeArr[0]) {
                dp.pause();
                $(`.bulletProblem_body[data-time=${timeArr[0]}]`).addClass('active').show();
                nowIndex = 1;
            }

        }, 1000)
    });

    dp.on('pause', () => {
        clearInterval(timer);
        clearInterval(tantiTimer);

        // 获取当前播放时间
        var now_time = dp.video.currentTime;
        $('#dplayer').data('col', parseInt(now_time));

    });

    dp.on('ended', () => {
        clearInterval(timer);
        timerInit($('#dplayer').data('length'));
        autoNextPlan();
    });

    window.addEventListener("storage", function (e) {
        if (timer) {
            debug && console.log('清除轮询计时器...');
            clearInterval(timer);
        }
        if (!judegTimer && dp.video.currentTime !== 0) {
            debug && console.log('开始检测online计时器...');
            judegTimer = setInterval(judgeInterVal, 10000);
        }
    });

    const tantiData = $('#dplayer').data('tanti')
        , chatStr = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    let tantiIndex = 0
        , nowIndex = 1
        , timeArr = [];

    // if(browserRedirect() === 'pc') {
    //     for (let i in tantiData) {
    //
    //         const paper = tantiData[i]['paper']
    //             , questions = paper['questions'];
    //
    //         if(paper.current_paper_result.length) {
    //             continue;
    //         }
    //
    //         // 记录 外层 弹题 索引
    //         tantiIndex++;
    //
    //         timeArr.push(i);
    //
    //         let html = '';
    //
    //         html += `<div class="bulletProblem_body ${tantiIndex === 1 ? 'active' : ''}" style="display: none;" data-time="${i}" data-paperId="${paper.id}">
    //             <div class="bulletProblem_content">
    //                 <div class="modal_header">
    //                     <p class="modal_title">
    //                         边学边测
    //                     </p>
    //                     <p class="modal_question_num question_num">
    //                         题数：<span class="q_now">${nowIndex}</span>/<span class="q_total">${questions.length}</span>
    //                     </p>
    //                 </div>
    //             <div class="modal_content">`;
    //
    //         questions.map((item, index) => {
    //             // 遍历类型，标题
    //             html += `<div class="question_item" data-questionId="${item.id}" data-status="waiting" data-type="${item.answers.length > 1 ? "multiple" : 'single'}" data-answer="${item.answers.toString()}" style="${index === 0 ? 'display: block;' : ''}">
    //             <div class="modal_question_option">
    //                 <div class="question_title_content">
    //                     <p class="question_type">${item.type === "multiple" ? '多' : '单'}选题：</p>
    //                     <div class="question_title">
    //                         ${item.title}</div>
    //                 </div>
    //                 <div class="question_options">`;
    //
    //             // 遍历选项
    //             item.options.map((item, index) => {
    //                 html += `<div class="option_item">
    //                     <p class="option_num">
    //                         ${chatStr[index]}
    //                     </p>
    //                     <div class="option_content">
    //                         ${item}
    //                     </div>
    //                     <p class="option_status">
    //                         <i class="iconfont">&#xe699;</i>
    //                     </p>
    //                 </div>`
    //             });
    //
    //             // 拼接答案提示
    //             html += `</div>
    //             </div>
    //             <div class="modal_answer">
    //                 <div class="question_title_content">
    //                     <p class="question_type answer_result" style="width: 15%;"></p>
    //                 </div>
    //                 <div class="question_title_content">
    //                     <p class="question_type" style="width: 15%;">正确答案：</p>
    //                     <div class="question_title correct_answer" style="width: 85%;color:#999;font-weight: 400;">
    //                     </div>
    //                 </div>
    //                 <div class="question_title_content">
    //                     <p class="question_type" style="width: 15%;">您的答案：</p>
    //                     <div class="question_title user_answer" style="width: 85%;color:#999;font-weight: 400;"></div>
    //                 </div>
    //             </div>`;
    //
    //             // 拼接答案解析
    //             html += `<div class="modal_answer answer_keys" data-status="${item.explain && item.explain.replace(/\\s/g, '') ? 'true' : 'false'}">
    //                 <div class="question_title_content">
    //                     <p class="question_type" style="width: 15%;">答案解析：</p>
    //                     <div class="question_title" style="width: 85%;color:#999;font-weight: 400;">
    //                         ${item.explain}
    //                     </div>
    //                 </div>
    //             </div>
    //         </div>`;
    //
    //         });
    //
    //         html += `</div>
    //             <div class="modal_footer">
    //                 <button class="btn btn-primary next_end" data-type="submit">提交</button>
    //             </div>
    //             </div>
    //         </div>`;
    //
    //         $('.video_page').append(html);
    //
    //     }
    //
    //     $(document).on('click', '.option_item', function () {
    //         const questionItemElm = $(this).parents('.question_item')
    //             ,type = $('.next_end').attr('data-type');
    //
    //         if(type === 'next' || type === 'end') {
    //             return;
    //         }
    //
    //         if (questionItemElm.data('type') !== 'multiple') {
    //             $(this).parent().find('.option_item').removeClass('active');
    //         }
    //
    //         $(this).toggleClass('active');
    //     });
    //
    //     $('.next_end').on({
    //         click: function () {
    //
    //             if ($(this).attr('data-type') === 'end') {
    //                 dp.play();
    //                 $('.bulletProblem_body.active').hide().removeClass('active');
    //                 return;
    //             }
    //
    //             const bulletProblemElm = $('.bulletProblem_body.active')
    //                 , questionItemElm = bulletProblemElm.find('.question_item[data-status="waiting"]').eq(0)
    //                 , optionItemElm = questionItemElm.find('.option_item')
    //                 , activeOptionItem = questionItemElm.find('.option_item.active')
    //                 , dataAnswerArr = questionItemElm.data('answer').toString().split(',')
    //                 , endAnswerElm = $('.bulletProblem_body.active .question_item[data-status="end_answer"]');
    //
    //             let correctAnswerHtml = ''
    //                 , userAnswerHtml = '';
    //
    //             if ($(this).attr('data-type') === 'next') {
    //                 nowIndex++;
    //                 bulletProblemElm.find('.question_num .q_now').text(nowIndex);
    //                 endAnswerElm.hide();
    //                 questionItemElm.show();
    //                 $(this).text('提交').attr('data-type', 'submit');
    //                 return;
    //             }
    //
    //             if (activeOptionItem.length <= 0) {
    //                 edu.alert('danger', '请选择选项后提交！');
    //                 return;
    //             }
    //
    //             optionItemElm.each((index, item) => {
    //
    //                 if ($(item).hasClass('active')) {
    //                     userAnswerHtml += `${chatStr[index]}`;
    //                 }
    //
    //                 if (dataAnswerArr.indexOf(index.toString()) >= 0) {
    //
    //                     correctAnswerHtml += `${chatStr[index]}`;
    //
    //                     if (!$(item).hasClass('active')) {
    //                         $(item).addClass('false_style').find('.option_status').addClass('o_false_style').show().find('.iconfont').html('&#xe612;');
    //                         return;
    //                     }
    //
    //                     $(item).addClass('true_style').find('.option_status').addClass('o_true_style').show().find('.iconfont').html('&#xe699;');
    //                 }
    //
    //             });
    //
    //             if (correctAnswerHtml === userAnswerHtml) {
    //                 questionItemElm.find('.answer_result').addClass('text-success').text('回答正确！');
    //             } else {
    //                 questionItemElm.find('.answer_result').addClass('text-danger').text('回答错误！');
    //             }
    //
    //             // 追加 正确答案
    //             questionItemElm
    //                 .find('.correct_answer')
    //                 .text(correctAnswerHtml)
    //                 // 追加 用户选择答案
    //                 .end()
    //                 .find('.user_answer')
    //                 .text(userAnswerHtml)
    //                 .end()
    //                 .find('.modal_answer:not(.answer_keys)')
    //                 .show();
    //
    //             if (questionItemElm.find('.answer_keys').data('status')) {
    //                 questionItemElm.find('.answer_keys').show();
    //             }
    //
    //             questionItemElm.attr('data-status', 'end_answer');
    //
    //             if (!$('.bulletProblem_body.active .question_item[data-status="waiting"]').length) {
    //                 $(this).text('关闭').attr('data-type', 'end');
    //
    //                 // 提交数据
    //                 // var retDatas = {
    //                 //     video_id: 1,
    //                 //     paper_id: 1,
    //                 //     questions: [
    //                 //         {
    //                 //             question_id: 1,
    //                 //             answer: [0, 2],
    //                 //         },
    //                 //         {
    //                 //             question_id: 2,
    //                 //             answer: [0, 1],
    //                 //         }
    //                 //     ],
    //                 // };
    //
    //                 let retDatas = {
    //
    //                     paper_id: bulletProblemElm.attr('data-paperid'),
    //                     questions: []
    //
    //                 };
    //
    //                 bulletProblemElm.find('.question_item').map((index, item) => {
    //
    //                     let answerArr = [];
    //                     $(item).find('.question_options .option_item').map((index, item) => {
    //                         if ($(item).hasClass('active')) {
    //                             answerArr.push(index);
    //                         }
    //                     });
    //
    //                     retDatas['questions'].push(
    //                         {
    //                             question_id: $(item).attr('data-questionid'),
    //                             answer: answerArr
    //                         }
    //                     )
    //                 });
    //
    //                 $.ajax({
    //                     url: $('#dplayer').attr('data-taskRoute'),
    //                     type: 'post',
    //                     data: retDatas,
    //                     success: (res) => {
    //                         if (res.status == 200) {
    //                             edu.alert('success', '提交成功！');
    //                         }
    //                     },
    //                     error: () => {
    //                         edu.alert('danger', '提交失败！');
    //                     }
    //                 });
    //
    //                 timeArr.shift();
    //
    //                 return;
    //             }
    //
    //             $(this).text('下一题').attr('data-type', 'next');
    //         }
    //     });
    // }
});