<link rel="stylesheet" href="{{ mix('/css/front/course/navbar/index.css') }}">
<link rel="stylesheet" href="{{ mix('/css/front/personal/navBar.css') }}">
<div class="navbar_content col-xl-3 m-0 p-0 pl-0 col-md-12 col-12">
    <div class="card navbar-card student_style">
        <div class="list_group_item_content">
            <div class="list_group_item_title">
                账户中心
            </div>
            <hr style="margin:0 auto;" width="85%">
            <div class="list-group list-group-sm list-group-flush">
                <a href="{{ route('users.order') }}"
                   class="list-group-item list-group-item-action d-flex justify-content-between {{ user_active('order') }}">
                    <div>
                        <span>我的订单</span>
                    </div>
                </a>
                <a href="{{ route('users.refund') }}"
                   class="list-group-item list-group-item-action d-flex justify-content-between {{ user_active('refund') }}">
                    <div>
                        <span>退款管理</span>
                    </div>
                </a>
                <a href="{{ route('users.coin') }}"
                   class="list-group-item list-group-item-action d-flex justify-content-between {{ user_active('coin') }}">
                    <div>
                        <span>虚拟币账户</span>
                    </div>
                </a>
            </div>
        </div>
        <div class="list_group_item_content">
            <div class="list_group_item_title">
                个人设置
            </div>
            <hr style="margin:0 auto;" width="85%">
            <div class="list-group list-group-sm list-group-flush">
                <a href="{{ route('users.edit', auth('web')->user()) }}"
                   class="list-group-item list-group-item-action d-flex justify-content-between {{ user_active('edit') }}">
                    <div>
                        <span>个人信息</span>
                    </div>
                </a>
                <a href="{{ route('users.safe') }}"
                   class="list-group-item list-group-item-action d-flex justify-content-between {{ user_active('safe') }}">
                    <div>
                        <span>安全设置</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
