<div class="site-menubar site-menubar-light">
    <div class="site-menubar-body">
        <ul class="site-menu" data-plugin="menu">
            <li class="site-menu-item has-sub
  {{ active_class(if_route_pattern('backstage.index')) }}
        ">
                <a href="javascript:void(0)">
                    <i class="site-menu-icon wb-dashboard" aria-hidden="true"></i>
                    <span class="site-menu-title">控制台</span>
                    <div class="site-menu-badge">
                        {{--<span class="badge badge-pill badge-success">3</span>--}}
                    </div>
                </a>
                <ul class="site-menu-sub">
                    <li class="site-menu-item
  {{ active_class(if_route_pattern('backstage.index')) }}
">
                        <a class="animsition-link" href="{{ route('backstage.index') }}">
                            <span class="site-menu-title">首页</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="site-menu-item has-sub
            {{ active_class(if_route_pattern('backstage.users.*')) }}
            {{ active_class(if_route_pattern('backstage.active.users')) }}
                    ">
                <a href="javascript:void(0)">
                    <i class="site-menu-icon  wb-user" aria-hidden="true"></i>
                    <span class="site-menu-title">用户管理</span>
                    <div class="site-menu-label">
                        {{--<span class="badge badge-danger badge-round">new</span>--}}
                    </div>
                </a>
                <ul class="site-menu-sub site-menu-sub-up">
                    <li class="site-menu-item
                    {{ active_class(if_route_pattern('backstage.users.index')) }}
                    {{ active_class(if_route_pattern('backstage.active.users')) }}
                    {{ active_class(if_route_pattern('backstage.users.login_log')) }}
                            ">
                        <a class="animsition-link" href="{{ route('backstage.users.index')  }}">
                            <span class="site-menu-title">用户管理</span>
                        </a>
                    </li>
                    <li class="site-menu-item
                    {{ active_class(if_route_pattern('backstage.users.teacher.index')) }}
                    {{ active_class(if_route_pattern('backstage.users.teacher.recommend.index')) }}
                            ">
                        <a class="animsition-link" href="{{ route('backstage.users.teacher.index') }}">
                            <span class="site-menu-title">教师管理</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="site-menu-item has-sub
                                    {{ active_class(if_route_pattern('backstage.roles.*')) }}
            {{ active_class(if_route_pattern('backstage.permissions.*')) }}
                    ">
                <a href="javascript:void(0)">
                    <i class="site-menu-icon wb-wrench" aria-hidden="true"></i>
                    <span class="site-menu-title">权限管理</span>
                    <div class="site-menu-label">
                        {{--<span class="badge badge-danger badge-round">new</span>--}}
                    </div>
                </a>
                <ul class="site-menu-sub site-menu-sub-up">
                    <li class="site-menu-item
                                    {{ active_class(if_route_pattern('backstage.permissions.*')) }}
                            ">
                        <a class="animsition-link" href="{{ route('backstage.permissions.index') }}">
                            <span class="site-menu-title">权限列表</span>
                        </a>
                    </li>
                    <li class="site-menu-item
                        {{ active_class(if_route_pattern('backstage.roles.*')) }}
                            ">
                        <a class="animsition-link" href="{{ route('backstage.roles.index') }}">
                            <span class="site-menu-title">角色列表</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="site-menu-item has-sub
                {{ active_class(if_route_pattern('backstage.courses.*')) }}
            {{ active_class(if_route_pattern('backstage.classrooms.*')) }}
                    ">
                <a href="javascript:void(0)">
                    <i class="site-menu-icon wb-library" aria-hidden="true"></i>
                    <span class="site-menu-title">课程管理</span>
                    <div class="site-menu-label">
                        {{--<span class="badge badge-danger badge-round">new</span>--}}
                    </div>
                </a>
                <ul class="site-menu-sub site-menu-sub-up">
                    <li class="site-menu-item {{ active_class(if_route_pattern('backstage.courses.*')) }}">
                        <a class="animsition-link" href="{{ route('backstage.courses.index') }}">
                            <span class="site-menu-title">课程管理</span>
                        </a>
                    </li>
                    @if (config('app.model') == 'classroom')
                    <li class="site-menu-item {{ active_class(if_route_pattern('backstage.classrooms.*')) }}">
                        <a class="animsition-link" href="{{ route('backstage.classrooms.index') }}">
                            <span class="site-menu-title">班级管理</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>

            <li class="site-menu-item has-sub
 {{ active_class(if_route_pattern('backstage.categoryGroup.*')) }}
            {{ active_class(if_route_pattern('backstage.tagGroups.*')) }}">
                <a href="javascript:void(0)">
                    <i class="site-menu-icon wb-plugin" aria-hidden="true"></i>
                    <span class="site-menu-title">标签分类</span>
                    <div class="site-menu-label">
                        {{--<span class="badge badge-danger badge-round">new</span>--}}
                    </div>
                </a>
                <ul class="site-menu-sub site-menu-sub-up">
                    <li class="site-menu-item {{ active_class(if_route_pattern('backstage.tagGroups.*')) }}">
                        <a class="animsition-link" href="{{ route('backstage.tagGroups.index') }}">
                            <span class="site-menu-title">标签管理</span>
                        </a>
                    </li>
                    <li class="site-menu-item {{ active_class(if_route_pattern('backstage.categoryGroup.*')) }}">
                        <a class="animsition-link" href="{{ route('backstage.categoryGroup.index') }}">
                            <span class="site-menu-title">分类管理</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="site-menu-item has-sub
            {{ active_class(if_route_pattern('backstage.notices.*')) }}
            {{ active_class(if_route_pattern('backstage.settings.*')) }}
            {{ active_class(if_route_pattern('backstage.notifications.*')) }}
            {{ active_class(if_route_pattern('backstage.slides.*')) }}
                    ">
                <a href="javascript:void(0)">
                    <i class="site-menu-icon wb-settings" aria-hidden="true"></i>
                    <span class="site-menu-title">网站配置</span>
                    <div class="site-menu-label">
                    </div>
                </a>
                <ul class="site-menu-sub site-menu-sub-up">
                    <li class="site-menu-item {{ active_class(if_route_pattern('backstage.notices.*')) }}">
                        <a class="animsition-link" href="{{ route('backstage.notices.index') }}">
                            <span class="site-menu-title">公告管理</span>
                        </a>
                    </li>
                    <li class="site-menu-item {{ active_class(if_route_pattern('backstage.notifications.*')) }}">
                        <a class="animsition-link" href="{{ route('backstage.notifications.index') }}">
                            <span class="site-menu-title">站内通知</span>
                        </a>
                    </li>
                    <li class="site-menu-item             {{ active_class(if_route_pattern('backstage.settings.*')) }}
                            ">
                        <a class="animsition-link"
                           href="{{ route('backstage.settings.show', ['namespace' => 'site']) }}">
                            <span class="site-menu-title">站点设置</span>
                        </a>
                    </li>
                    <li class="site-menu-item             {{ active_class(if_route_pattern('backstage.slides.*')) }}
                            ">
                        <a class="animsition-link" href="{{ route('backstage.slides.index') }}">
                            <span class="site-menu-title">轮播图设置</span>
                        </a>
                    </li>

                </ul>
            </li>

            <li class="site-menu-item has-sub
  {{ active_class(if_route_pattern('backstage.messages.manage.*')) }}
            {{ active_class(if_route_pattern('backstage.topics.*')) }}
            {{ active_class(if_route_pattern('backstage.notes.*')) }}
            {{ active_class(if_route_pattern('backstage.reviews.*')) }}

                    ">
                <a href="javascript:void(0)">
                    <i class="site-menu-icon wb-desktop" aria-hidden="true"></i>
                    <span class="site-menu-title">运营管理</span>
                    <div class="site-menu-label">
                    </div>
                </a>
                <ul class="site-menu-sub site-menu-sub-up">

                    <li class="site-menu-item
  {{ active_class(if_route_pattern('backstage.topics.*')) }}
                            ">
                        <a class="animsition-link" href="{{ route('backstage.topics.index') }}">
                            <span class="site-menu-title">问答管理</span>
                        </a>
                    </li>
                    <li class="site-menu-item
                            {{ active_class(if_route_pattern('backstage.messages.manage.*')) }}"
                    >
                        <a class="animsition-link" href="{{ route('backstage.messages.manage.index') }}">
                            <span class="site-menu-title">私信管理</span>
                        </a>
                    </li>

                    <li class="site-menu-item
{{ active_class(if_route_pattern('backstage.reviews.*')) }}
                            ">
                        <a class="animsition-link" href="{{ route('backstage.reviews.index') }}">
                            <span class="site-menu-title">评价管理</span>
                        </a>
                    </li>
                    <li class="site-menu-item
  {{ active_class(if_route_pattern('backstage.notes.*')) }}
                            ">
                        <a class="animsition-link" href="{{ route('backstage.notes.index') }}">
                            <span class="site-menu-title">笔记管理</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="site-menu-item has-sub
                            {{ active_class(if_route_pattern('backstage.coupons.*')) }}
            {{ active_class(if_route_pattern('backstage.orders.*')) }}
            {{ active_class(if_route_pattern('backstage.refunds.*')) }}
                    ">
                <a href="javascript:void(0)">
                    <i class="site-menu-icon wb-payment" aria-hidden="true"></i>
                    <span class="site-menu-title">财务管理</span>
                    <div class="site-menu-label">
                    </div>
                </a>
                <ul class="site-menu-sub site-menu-sub-up
                           ">
                    <li class="site-menu-item  {{ active_class(if_route_pattern('backstage.orders.*')) }}
                            ">
                        <a class="animsition-link" href="{{ route('backstage.orders.index') }}">
                            <span class="site-menu-title">订单管理</span>
                        </a>
                    </li>
                    <li class="site-menu-item  {{ active_class(if_route_pattern('backstage.refunds.*')) }}">
                        <a class="animsition-link" href="{{ route('backstage.refunds.index') }}">
                            <span class="site-menu-title">退款管理</span>
                        </a>
                    </li>
                    {{--<li class="site-menu-item ">--}}
                        {{--<a class="animsition-link" href="../apps/contacts/contacts.html">--}}
                            {{--<span class="site-menu-title">交易记录</span>--}}
                        {{--</a>--}}
                    {{--</li>--}}
                    <li class="site-menu-item
                            {{ active_class(if_route_pattern('backstage.coupons.*')) }}
                            ">
                        <a class="animsition-link" href="{{ route('backstage.coupons.index') }}">
                            <span class="site-menu-title">优惠码管理</span>
                        </a>
                    </li>
                    {{--<li class="site-menu-item">--}}
                    {{--<a class="animsition-link" href="../apps/calendar/calendar.html">--}}
                    {{--<span class="site-menu-title">虚拟币管理</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                </ul>
            </li>

            <li class="site-menu-item has-sub
            {{ active_class(if_route_pattern('backstage.feedback.*')) }}
                    ">
                <a href="javascript:void(0)">
                    <i class="site-menu-icon  wb-chat-text" aria-hidden="true"></i>
                    <span class="site-menu-title">反馈管理</span>
                    <div class="site-menu-label">
                    </div>
                </a>
                <ul class="site-menu-sub site-menu-sub-up
                           ">
                    <li class="site-menu-item  {{ active_class(if_route_pattern('backstage.feedback.*')) }}
                            ">
                        <a class="animsition-link" href="{{ route('backstage.feedback.index') }}">
                            <span class="site-menu-title">反馈管理</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>