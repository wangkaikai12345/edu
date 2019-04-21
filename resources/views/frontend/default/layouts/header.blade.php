<style>
    ::-webkit-input-placeholder { /* WebKit browsers */
        color: #999 !important;
    }

    :-moz-placeholder { /* Mozilla Firefox 4 to 18 */
        color: #999 !important;
    }

    ::-moz-placeholder { /* Mozilla Firefox 19+ */
        color: #999 !important;
    }

    :-ms-input-placeholder { /* Internet Explorer 10+ */
        color: #999 !important;
    }

    .navbar .dropdown-menu a {
        font-weight: 400;
    }

    @media (max-width: 1200px) {
        main .banner .container {
            min-width: 350px;
            width: 100%;
        }
    }

    @media (max-width: 992px) {
        header .navbar #headerSearch {
            width: 50% !important;
            margin-left: 7px!important;
        }

    }

    @media (max-width: 880px) {
        header .navbar #headerSearch {
            width: 60% !important;
        }
    }

    @media (max-width: 680px) {
        header .navbar #headerSearch {
            width: 70% !important;
        }
    }

    @media (max-width: 580px) {
        header .navbar #headerSearch {
            width: 80% !important;
        }
    }

    @media (max-width: 500px) {
        header .navbar #headerSearch {
            width: 100% !important;
        }
    }
</style>


<header>
    <nav class="navbar navbar-expand-lg scrolling-navbar">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ $index->site()['logo'] }}" height="45" alt="eduplay logo">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="nav navbar-nav mr-auto">
                    @foreach($index->header_nav() as $header)
                        <li class="nav-item mr-3">
                            <a class="nav-link waves-effect" href={{ $header['link'] }}>{{ $header['name'] }}</a>
                        </li>
                    @endforeach
                </ul>
                <form class="form-inline ml-auto" id="headerSearch" style="width: 250px;">
                    <div class="md-form my-0 w-100">
                        <input class="form-control m-0 font-weight-normal w-100" type="text" placeholder="搜索"
                               aria-label="Search">
                        <ul class="dropdown-content select-dropdown w-100"
                            style="width: 100%; position: absolute; top: 100%; left: 0px;display: block;">
                            <li class="disabled active searchLoading">
                                <span class="filtrable" style="display: inline-block;">请稍等</span>
                                <div class="preloader-wrapper small" style="width: 1rem;height: 1rem;">
                                    <div class="spinner-layer spinner-blue-only">
                                        <div class="circle-clipper left">
                                            <div class="circle" style="border-width: 0.075rem"></div>
                                        </div>
                                        <div class="gap-patch">
                                            <div class="circle"></div>
                                        </div>
                                        <div class="circle-clipper right">
                                            <div class="circle" style="border-width: 0.075rem"></div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </form>
                <ul class="navbar-nav nav-flex-icons">
                    @auth('web')
                        <li class="nav-item ml-3 mr-3">
                            <a class="nav-link waves-effect" href="{{ route('users.message') }}">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </li>
                        <li class="nav-item avatar dropdown">
                            <a class="nav-link dropdown-toggle waves-effect" id="navbarDropdownMenuLink-5"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <img src="{{ render_cover(auth('web')->user()['avatar'], 'avatar') }}"
                                     class="rounded-circle z-depth-0"
                                     alt="avatar image">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-primary"
                                 aria-labelledby="navbarDropdownMenuLink-5">
                                @if(auth('web')->user()->isSuperAdmin() || auth('web')->user()->isAdmin())
                                    <a class="dropdown-item waves-effect"
                                       href="{{ config('app.manage_url').'/user/login' }}" target="_blank">后台管理</a>
                                @endif

                                <a class="dropdown-item waves-effect"
                                   href="{{ route('users.edit', auth('web')->user()) }}">个人设置</a>
                                <a class="dropdown-item waves-effect" href="{{ route('users.order') }}">账户中心</a>
                                @if(!auth('web')->user()->isStudent())
                                    <a class="dropdown-item waves-effect"
                                       href="{{ config('app.manage_url').'/home/center/teach' }}"
                                       target="_blank">我的教学</a>
                                @endif
                                <a class="dropdown-item waves-effect" href="{{ route('users.courses') }}">我的学习</a>
                                <a class="dropdown-item waves-effect"
                                   href="{{ route('users.show', auth('web')->user()) }}">我的主页</a>
                                <a class="dropdown-item waves-effect" href="{{ route('logout') }}">退出登陆</a>
                            </div>
                        </li>
                    @else
                        <li class="nav-item mr-3">
                            <a class="nav-link waves-effect" href="{{ route('login') }}">登录</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link waves-effect pr-0" href="{{ route('register') }}">注册</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    <div class="button-collapse slide-logo" data-activates="slide-out">
        <a href="#" class="white-text"><i class="fas fa-bars"></i><span
                    class="sr-only" aria-hidden="true">Toggle side navigation</span></a>
    </div>
</header>
