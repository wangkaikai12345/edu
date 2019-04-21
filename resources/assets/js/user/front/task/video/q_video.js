import 'Dplayer/DPlayer.min.css';
import DPlayer from 'Dplayer/DPlayer.min.js';
import Hls from 'hls.js';

window.Hls = Hls;

// avi、wmv、mpeg、mp4、mov、mkv、flv、f4v、m4v、rmvb、rm、3gp、dat、ts、mts、vob
const videoTypeArr = ['avi', 'wmv', 'mp4', 'mov', 'mkv', 'flv', '3gp', 'm4v', 'rmvb'];

const url = $('#dplayer').data('url')
// const url = 'http://omf806ou0.bkt.clouddn.com/edu%E6%9C%80%E6%96%B0%E5%AE%A3%E4%BC%A0%E7%89%87.mp4'
    , videoType = url.substr(url.lastIndexOf('.') + 1, url.length);

const dp = new DPlayer({
    element: document.getElementById('dplayer'),
    autoplay: true, // 自动播放
    screenshot: false, // 截屏
    hotkey: true, // 热键
    video: {  //视频源 包含不同分辨率源
        url, // 视频地址
        type: videoTypeArr.indexOf(videoType) > -1 ? 'auto' : 'hls'
    },
});

dp.on('pause', () => {
    // 获取当前播放时间
    var now_time = dp.video.currentTime;
    $('#video-question-time').val(parseInt(now_time));
});
