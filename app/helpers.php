<?php

/**
 * 利用本文件你可以自定义全局函数，在自定义之前请优先考虑PHP函数 和辅助函数。
 */

if (!function_exists('dingo_route')) {
    /**
     * Set the version to generate API URLs to.
     *
     * @param string $name 命名路由
     * @param null $params 参数
     * @param string $version 版本
     * @param bool $absolute 绝对路径
     *
     * @return \Dingo\Api\Routing\UrlGenerator
     */
    function dingo_route(string $name, array $params = [], string $version = 'v1', bool $absolute = true): string
    {
        $generator = app('Dingo\Api\Routing\UrlGenerator')->version($version);
        return $generator->route($name, $params, $absolute);
    }
}


if (!function_exists('video_length')) {
    /**
     * 根据小时分钟生成统一单位的时间
     *
     * @param int $minute
     * @param int $second
     * @return float|int
     */
    function video_length(int $minute, int $second)
    {
        return $minute * 60 + $second;
    }
}

if (!function_exists('generate_trade_uuid')) {
    /**
     * 生成交易号
     *
     * @return string
     */
    function generate_trade_uuid()
    {
        return \Ramsey\Uuid\Uuid::uuid4()->getHex();
    }
}

if (!function_exists('hide_info')) {
    /**
     * 对信息进行隐藏
     *
     * @param string $value
     * @return mixed|string
     */
    function hide_info($value)
    {
        $type = null;
        if (preg_match('/^1[3-9]\d{9}$/', $value, $phones) && isset($phones[0])) {
            $type = 'phone';
        } else if (str_contains($value, '@')) {
            $type = 'email';
        }

        switch ($type) {
            case 'phone':
                return substr_replace($value, '****', 3, 4);
                break;
            case 'email':
                $arr = explode('@', $value);
                return substr_replace($arr[0], '******', 1) . '@' . $arr[1];
                break;
            default:
                return substr_replace($value, '******', 1);
        }
    }
}

if (!function_exists('frontend_view')) {

    function frontend_view($name)
    {

        $args = func_get_args();
        $args[0] = 'frontend.' . config('theme.theme') . '.' . $name;
        if (!view()->exists('frontend.' . config('theme.theme') . '.' . $name)) {
            $args[0] = 'frontend.default.' . $name;
            if (!view()->exists($args[0])) {
                abort(404);
            }
        }
        return view(...$args);
    }
}

// 渲染版本详情导航
if (!function_exists('plan_detail_active')) {

    function plan_detail_active($detail)
    {
        return active_class((if_route('plans.' . $detail)));
    }
}

// 渲染课程详情版本切换
if (!function_exists('plan_nav_active')) {

    function plan_nav_active($default, $plan)
    {
        if (if_route('courses.show') && $default == $plan) {
            return 'active';
        }

        if (if_route('plans.show' && if_route_param('plan', $plan))) {
            return 'active';
        }

        return '';
    }
}

/**
 * 货币单位转换，元转为分
 *
 * @param String $money 金额 单位 元
 * @return int
 */
if (!function_exists('ytof')) {
    function ytof($money)
    {
        return intval(round(floatval($money) * 100));
    }
}

/**
 * 货币单位转换，分转为元
 *
 * @param String $money 金额 单位 元
 * @return float
 */
if (!function_exists('ftoy')) {
    function ftoy($money)
    {
        return round(floatval($money) / 100, 2);
    }
}

// 渲染任务类型
if (!function_exists('render_task_type')) {

    function render_task_type($value)
    {
        return \App\Enums\TaskType::getDescription($value);
    }
}

// 渲染任务模式
if (!function_exists('render_task_class')) {

    function render_task_class($value)
    {
        $array = [
            'video' => '&#xe623;',
            'audio' => '&#xe66a;',
            'ppt' => '&#xe721;',
            'doc' => '&#xe63b;',
            'text' => '&#xe60c;',
            'paper' => '&#xe8b3;',
            'homework' => '&#xe60e;',
            'zip' => '&#xe60e;',
        ];

        return $array[$value];
    }
}

// http格式化
if (!function_exists('http_format')) {

    function http_format($value)
    {
        if (preg_match("/^http?:\\/\\/.+/", $value)) {
            return $value;
        } else {
            return 'http://' . $value;
        }
    }
}

// 渲染封面图片
if (!function_exists('render_cover')) {

    function render_cover($value = '', $type)
    {
        // 如果资源存在
        if ($value) {

            // 获取配置
            $diskConfig = \Facades\App\Models\Setting::namespace(\App\Enums\SettingType::QINIU);

            // 驱动
            $driver = data_get($diskConfig, 'driver', 'local');

            // 如果是本地
            if ($driver === 'local') {
                return http_format(config('app.url')) . \Illuminate\Support\Facades\Storage::disk('local')->url($value);
            }

            // 如果是七牛
            return http_format($diskConfig['public_domain']) . '/' . $value;
        }

        $avatarConfig = \Facades\App\Models\Setting::namespace(\App\Enums\SettingType::AVATAR);

        if (!empty($avatarConfig) && !empty($avatarConfig['image']) && $type == 'avatar') {
            return $avatarConfig['image'];
        }

        // 返回系统默认
        return asset('imgs/' . $type . '.png');
    }
}

// 渲染课程视频
if (!function_exists('render_task_source')) {

    function render_task_source($value)
    {
        if ($value) {
            // 获取配置
            $diskConfig = \Facades\App\Models\Setting::namespace(\App\Enums\SettingType::QINIU);

            // 驱动
            $driver = data_get($diskConfig, 'driver', 'local');

            // 如果是本地
            if ($driver === 'local') {
                return \Illuminate\Support\Facades\Storage::disk('local')->url($value);
            }

            // 如果是七牛
            return http_format($diskConfig['slice_domain']) . '/' . $value;

        }

    }
}

// 渲染私有资源(音频)
if (!function_exists('render_other_source')) {

    function render_other_source($value)
    {
        if ($value) {

            // 获取配置
            $diskConfig = \Facades\App\Models\Setting::namespace(\App\Enums\SettingType::QINIU);

            // 驱动
            $driver = data_get($diskConfig, 'driver', 'local');

            // 如果是本地
            if ($driver === 'local') {
                return \Illuminate\Support\Facades\Storage::disk('local')->url($value);
            }

            // 如果是七牛
            $disk = \Storage::disk('lessons');

            return $disk->privateDownloadUrl($value);
        }
    }
}

// 渲染个人中心侧边栏
if (!function_exists('user_active')) {

    function user_active($detail)
    {
        return active_class(if_route('users.' . $detail));
    }
}

// 渲染订单状态
if (!function_exists('render_order_status')) {

    function render_order_status($value)
    {
        return \App\Enums\OrderStatus::getDescription($value);
    }
}

// 渲染优惠券类型
if (!function_exists('render_coupon_type')) {

    function render_coupon_type($value)
    {
        return \App\Enums\CouponType::getDescription($value);
    }
}

// 渲染支付类型
if (!function_exists('render_payment')) {

    function render_payment($value)
    {
        return \App\Enums\Payment::getDescription($value);
    }
}

// 渲染状态
if (!function_exists('render_status')) {

    function render_status($value)
    {
        return \App\Enums\Status::getDescription($value);
    }
}

// 渲染用户性别
if (!function_exists('render_user_sex')) {

    function render_user_sex($value)
    {
        return \App\Enums\Gender::getDescription($value);
    }
}

// ajax返回数据格式
if (!function_exists('ajax')) {

    function ajax($status, $message, $data = [])
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ]);
    }
}

/**
 * 生成24位唯一订单号码，格式：YYYY-MMDD-HHII-SS-NNNN,NNNN-CC，
 * 其中：YYYY=年份，MM=月份，DD=日期，HH=24格式小时，II=分，SS=秒，NNNNNNNN=随机数，CC=检查码
 */
if (!function_exists('generate_only')) {

    function generate_only()
    {
        //订单号码主体（YYYYMMDDHHIISSNNNNNNNN）
        $order_id_main = date('YmdHis') . rand(100000, 999999);

        //订单号码主体长度
        $order_id_len = strlen($order_id_main);

        $order_id_sum = 0;

        for ($i = 0; $i < $order_id_len; $i++) {

            $order_id_sum += (int)(substr($order_id_main, $i, 1));

        }

        //唯一订单号码（YYYYMMDDHHIISSNNNNNNNNCC）
        return $order_id_main . str_pad((100 - $order_id_sum % 100) % 100, 2, '0', STR_PAD_LEFT);
    }
}


if (!function_exists('fileUrl')) {

    function fileUrl($key)
    {
        // 获取驱动配置
        $diskConfig = \Facades\App\Models\Setting::namespace('qiniu');

        // 驱动
        $driver = data_get($diskConfig, 'driver', config('filesystems.default'));

        if ($driver === 'local') {
            return \Illuminate\Support\Facades\Storage::disk('local')->url($key);
        }

        return $diskConfig['public_domain'] . '/' . $key;
    }
}


if (!function_exists('fileDomain')) {

    function fileDomain()
    {
        $diskConfig = \Facades\App\Models\Setting::namespace('qiniu');

        $driver = data_get($diskConfig, 'driver', 'local');

        if ($driver == 'local') {
            return config('app.url') . '/storage/';
        } else {
            return http_format($diskConfig['public_domain']);
        }
    }
}

if (!function_exists('getLocalLength')) {

    function getLocalLength($value)
    {
        $getID3 = new \getID3();

        $ThisFileInfo = $getID3->analyze(fileUrl($value));

        return !empty($ThisFileInfo['playtime_seconds']) ? ceil($ThisFileInfo['playtime_seconds']) : 0;
    }
}

if (!function_exists('renderSerial')) {

    function renderSerial($value)
    {
        $array = [
            '1' => 'st',
            '2' => 'nd',
            '3' => 'rd',
        ];

        if (array_key_exists($value, $array)) {
            return $array[$value];
        }

        return 'th';
    }
}

/**
 * 获取某个视频下所有的试卷和题目
 */
if (!function_exists('videoQuestion')) {

    function videoQuestion($videoId)
    {
        // TODO 查询用户已经答过题的试卷

        // 查询所有试卷id
        $videoQuestions = \App\Models\VideoQuestion::select(['id', 'video_time', 'paper_id'])
            ->where('video_id', $videoId)
            ->with(['paper' => function ($query) {
                return $query
                    ->select(['id', 'title', 'expect_time', 'questions_count', 'total_score', 'pass_score']);
            }, 'paper.questions' => function ($query) {
//                return $query->select(['questions.title', 'questions.type', 'questions.rate', 'questions.options']);
            }, 'paper.currentPaperResult'])
            ->orderBy('video_time')
            ->get()
            ->keyBy('video_time');
        return $videoQuestions;
    }
}

if (!function_exists('typeResult')) {

    function typeResult($chapter, $type, $userId)
    {
        $chapter = \App\Models\Chapter::find($chapter);

        if (!$chapter) {
            return false;
        }

        $tasks = $chapter->tasks->where('type', $type)->where('status', 'published');

        if (!$tasks->count()) {
            return false;
        }

        $finish = 0;
        foreach ($tasks as $task) {
            $res = $task->currentResult($userId);

            if ($res != 'finish') {
                return ytof($finish / $tasks->count());
            }

            $finish++;
        }

        return ytof($finish / $tasks->count());
    }
}


if (!function_exists('homeworkResult')) {

    function homeworkResult($chapter, $userId)
    {
        $chapter = \App\Models\Chapter::find($chapter);

        if (!$chapter) {
            return false;
        }

        $tasks = $chapter->tasks->where('type', 'd-homework')->where('status', 'published');

        if (!$tasks->count()) {
            return false;
        }

        foreach ($tasks as $task) {
            $res = $task->target->homeworkPosts()->where(['user_id' => $userId, 'locked' => 'open'])->exists();
            if (!$res) {
                return false;
            }
        }

        return true;
    }
}

if (!function_exists('renderTaskRoute')) {

    function renderTaskRoute($array, $member)
    {
        if (!empty($member->classroom_id)) {
            $array['cid'] = $member->classroom_id;
            return route('tasks.show', $array);
        }

        return route('tasks.show', $array);
    }
}

if (!function_exists('renderTaskResultRoute')) {

    function renderTaskResultRoute($route, $array, $member)
    {
        if (!empty($member->classroom_id)) {
            $array['cid'] = $member->classroom_id;
            return route($route, $array);
        }

        return route($route, $array);
    }
}

if (!function_exists('timeFormat')) {
    function timeFormat($seconds, $showS = false)
    {

        $str = '';

        $d = floor($seconds / 3600 / 24);
        $h = floor($seconds / 3600 % 24);
        $m = floor($seconds % 3600 / 60);
        $s = floor($seconds % 3600 % 60);

        if ($d > 0) {
            $str .= "{$d} 天 ";
        }

        if ($h > 0) {
            $str .= "{$h} 小时 ";
        }
        if ($m > 0) {
            $str .= "{$m} 分";
        }

        if ($showS) {
            $str .= "{$s} 秒";
        }

        return $str;
    }
}

if (!function_exists('questionScore')) {

    function questionScore($pid, $qid)
    {
        $model = \App\Models\PaperQuestion::where('paper_id', $pid)
            ->where('question_id', $qid)
            ->first();

        if (empty($model)) return false;

        return $model->score;
    }
}

// 判断是否移动端
if (!function_exists('is_show_mobile_page')) {
    function is_show_mobile_page()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA'])) {
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array('nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile'
            );
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('mobile_view')) {

    function mobile_view($type)
    {
        $arr = [
            'video', 'audio', 'text', 'homework', 'practice'
        ];

        return in_array($type, $arr);
    }
}

if (!function_exists('get_client_ip')) {
    function get_client_ip()
    {
        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $ip = getenv('REMOTE_ADDR');
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return preg_match('/[\d\.]{7,15}/', $ip, $matches) ? $matches [0] : '';
    }
}

//php防注入和XSS攻击通用过滤  清除标签
if (!function_exists('string_remove_xss')) {
    function string_remove_xss($html)
    {
        preg_match_all("/\<([^\<]+)\>/is", $html, $ms);

        $searchs[] = '<';
        $replaces[] = '&lt;';
        $searchs[] = '>';
        $replaces[] = '&gt;';

        if ($ms[1]) {
            $allowtags = 'img|a|font|div|table|tbody|caption|tr|td|th|br|p|b|strong|i|u|em|span|ol|ul|li|blockquote';
            $ms[1] = array_unique($ms[1]);
            foreach ($ms[1] as $value) {
                $searchs[] = "&lt;" . $value . "&gt;";

                $value = str_replace('&amp;', '_uch_tmp_str_', $value);
                $value = string_htmlspecialchars($value);
                $value = str_replace('_uch_tmp_str_', '&amp;', $value);

                $value = str_replace(array('\\', '/*'), array('.', '/.'), $value);
                $skipkeys = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate',
                    'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange',
                    'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick',
                    'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate',
                    'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete',
                    'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel',
                    'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart',
                    'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop',
                    'onsubmit', 'onunload', 'javascript', 'script', 'eval', 'behaviour', 'expression', 'style', 'class');
                $skipstr = implode('|', $skipkeys);
                $value = preg_replace(array("/($skipstr)/i"), '.', $value);
                if (!preg_match("/^[\/|\s]?($allowtags)(\s+|$)/is", $value)) {
                    $value = '';
                }
                $replaces[] = empty($value) ? '' : "<" . str_replace('&quot;', '"', $value) . ">";
            }
        }
        $html = str_replace($searchs, $replaces, $html);

        return $html;
    }
}


//php防注入和XSS攻击通用过滤  转义标签
if (!function_exists('string_htmlspecialchars')) {
    function string_htmlspecialchars($string, $flags = null)
    {
        if (is_array($string)) {
            foreach ($string as $key => $val) {
                $string[$key] = string_htmlspecialchars($val, $flags);
            }
        } else {
            if ($flags === null) {
                $string = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string);
                if (strpos($string, '&amp;#') !== false) {
                    $string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $string);
                }
            } else {
                if (PHP_VERSION < '5.4.0') {
                    $string = htmlspecialchars($string, $flags);
                } else {
                    if (!defined('CHARSET') || (strtolower(CHARSET) == 'utf-8')) {
                        $charset = 'UTF-8';
                    } else {
                        $charset = 'ISO-8859-1';
                    }
                    $string = htmlspecialchars($string, $flags, $charset);
                }
            }
        }

        return $string;
    }
}


if (!function_exists('timeChange')) {

    function timeChange($time){

        $hour=(int)($time/3600);
        $minute=(int)($time%3600/60);
        $second=(int)($time%3600%60);
        return ($hour>=10?("".$hour):("0".$hour)).":".($minute>=10?("".$minute):("0".$minute)).":".($second>=10?("".$second):("0".$second));
    }
}
