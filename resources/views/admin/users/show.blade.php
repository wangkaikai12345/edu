<style>
    .navbar-text {
        float: none;
    }
</style>
<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myLgModal">用户详情</h4>
    </div>
    <div class="modal-body">
        <div class="example-grid">
            <div class="row">
                <div class="col-lg-2 text-right">
                    <p class="navbar-text">用户名:</p>
                </div>
                <div class="col-lg-3  text-left">
                    <p class="navbar-text">{{ $user->username }}</p>
                </div>
                <div class="col-lg-2  offset-lg-1 text-right">
                    <p class="navbar-text">Email:</p>
                </div>
                <div class="col-lg-3  text-left">
                    <p class="navbar-text">{{ $user->email ?? '暂无信息' }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 text-right">
                    <p class="navbar-text">用户组:</p>
                </div>
                <div class="col-lg-3  text-left">
                    <p class="navbar-text">{{ $role }}</p>
                </div>
                <div class="col-lg-2  offset-lg-1 text-right">
                    <p class="navbar-text">注册时间:</p>
                </div>
                <div class="col-lg-3  text-left">
                    <p class="navbar-text">{{ $user->created_at ?? '暂无信息' }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 text-right">
                    <p class="navbar-text">注册地点:</p>
                </div>
                <div class="col-lg-3  text-left">
                    <p class="navbar-text">{{ $user->registered_way  ?? '暂无信息'}}</p>
                </div>
                <div class="col-lg-2  offset-lg-1 text-right">
                    <p class="navbar-text">登录时间:</p>
                </div>
                <div class="col-lg-3  text-left">
                    <p class="navbar-text">{{ $user->last_logined_at ?? '暂无信息' }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 text-right">
                    <p class="navbar-text">登录IP:</p>
                </div>
                <div class="col-lg-3  text-left">
                    <p class="navbar-text">{{ $user->last_logined_ip ?? '暂无信息' }}</p>
                </div>
                <div class="col-lg-2  offset-lg-1 text-right">
                    <p class="navbar-text">昵称:</p>
                </div>
                <div class="col-lg-3  text-left">
                    <p class="navbar-text">{{ $user->username ?? '暂无信息' }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 text-right">
                    <p class="navbar-text">姓名:</p>
                </div>
                <div class="col-lg-3  text-left">
                    <p class="navbar-text">{{ $user->profile->name }}</p>
                </div>
                <div class="col-lg-2  offset-lg-1 text-right">
                    <p class="navbar-text">性别:</p>
                </div>
                <div class="col-lg-3  text-left">
                    @switch($user->profile->gender)
                        @case("male"):
                        <p class="navbar-text"> 男</p>
                        @break
                        @case("female"):
                        <p class="navbar-text"> 女</p>
                        @break
                        @case("secret"):
                        <p class="navbar-text"> 保密</p>
                        @break
                        @default
                        <p class="navbar-text"> 暂无信息</p>
                        @break
                    @endswitch

                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 text-right">
                    <p class="navbar-text">身份证号码:</p>
                </div>
                <div class="col-lg-3  text-left">
                    <p class="navbar-text">{{ $user->profile->idcard?? '暂无信息' }}</p>
                </div>
                <div class="col-lg-2  offset-lg-1 text-right">
                    <p class="navbar-text">手机号码:</p>
                </div>
                <div class="col-lg-3  text-left">
                    <p class="navbar-text">{{ $user->phone ?? '暂无信息' }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 text-right">
                    <p class="navbar-text">公司:</p>
                </div>
                <div class="col-lg-3  text-left">
                    <p class="navbar-text">{{ $user->profile->company ?? '暂无信息' }}</p>
                </div>
                <div class="col-lg-2  offset-lg-1 text-right">
                    <p class="navbar-text">专业:</p>
                </div>
                <div class="col-lg-3  text-left">
                    <p class="navbar-text">{{ $user->profile->major ?? '暂无信息' }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 text-right">
                    <p class="navbar-text">头衔:</p>
                </div>
                <div class="col-lg-3  text-left">
                    <p class="navbar-text">{{ $user->profile->title ?? '暂无信息' }}</p>
                </div>
                <div class="col-lg-2  offset-lg-1 text-right">
                    <p class="navbar-text">职业:</p>
                </div>
                <div class="col-lg-3  text-left">
                    <p class="navbar-text">{{ $user->profile->job ?? '暂无信息' }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 text-right">
                    <p class="navbar-text">自我介绍:</p>
                </div>
                <div class="col-lg-3  text-left">
                    <p class="navbar-text">{{ $user->profile->about ?? '暂无信息' }}</p>
                </div>
                <div class="col-lg-2  offset-lg-1 text-right">
                    <p class="navbar-text">个性签名:</p>
                </div>
                <div class="col-lg-3  text-left">
                    <p class="navbar-text">{{ $user->signature ?? '暂无信息' }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 text-right">
                    <p class="navbar-text">微博:</p>
                </div>
                <div class="col-lg-3  text-left">
                    <p class="navbar-text">{{ $user->profile->weibo ?? '暂无信息' }}</p>
                </div>
                <div class="col-lg-2  offset-lg-1 text-right">
                    <p class="navbar-text">个人主页:</p>
                </div>
                <div class="col-lg-3  text-left">
                    <p class="navbar-text">{{ $use->profiler->site ?? '暂无信息' }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 text-right">
                    <p class="navbar-text">QQ:</p>
                </div>
                <div class="col-lg-3  text-left">
                    <p class="navbar-text">{{ $user->profile->qq ?? '暂无信息' }}</p>
                </div>
                <div class="col-lg-2  offset-lg-1 text-right">
                    <p class="navbar-text">微信:</p>
                </div>
                <div class="col-lg-3  text-left">
                    <p class="navbar-text">{{ $user->profile->weibo ?? '暂无信息' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>