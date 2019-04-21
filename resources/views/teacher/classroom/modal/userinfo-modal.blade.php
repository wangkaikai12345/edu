<div class="add-student-message-modal-lg">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">个人详细信息</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="tab-content" id="myTabContent">
            {{-- 正式学员 --}}
            <div class="tab-pane fade show active" id="formal-student" role="tabpanel"
                 aria-labelledby="home-tab">
                <div class="table_content">
                    <table class="table">
                        <tr>
                            <th width="130">用户名</th>
                            <td>
                                {{ $user->username }}
                                <a href="{{ route('users.show', $user) }}" target="_blank" class="float-right">个人主页</a>
                            </td>
                        </tr>
                        <tr>
                            <th width="130">Email</th>
                            <td>
                                {{ $user->email }}
                            </td>
                        </tr>
                        <tr>
                            <th width="130">姓名</th>
                            <td>
                                {{ $profile->name }}
                            </td>
                        </tr>
                        <tr>
                            <th width="130">性别</th>
                            <td>
                                {{ \App\Enums\Gender::getDescription($profile->gender) }}
                            </td>
                        </tr>
                        <tr>
                            <th width="130">公司</th>
                            <td>
                                {{ $profile->company }}
                            </td>
                        </tr>
                        <tr>
                            <th width="130">职业</th>
                            <td>
                                {{ $profile->job }}
                            </td>
                        </tr>
                        <tr>
                            <th width="130">头衔</th>
                            <td>
                                {{ $profile->title }}
                            </td>
                        </tr>
                        <tr>
                            <th width="130">电话</th>
                            <td>
                                {{ $user->phone }}
                            </td>
                        </tr>
                        <tr>
                            <th width="130">城市</th>
                            <td>
                                {{ $profile->city }}
                            </td>
                        </tr>
                        <tr>
                            <th width="130">个人签名</th>
                            <td>
                                {{ $user->signature }}
                            </td>
                        </tr>
                        <tr>
                            <th width="130">自我介绍</th>
                            <td>
                                {{ $profile->about }}
                            </td>
                        </tr>
                        <tr>
                            <th width="130">个人网站</th>
                            <td>
                                {{ $profile->site }}
                            </td>
                        </tr>
                        <tr>
                            <th width="130">微博</th>
                            <td>
                                {{ $profile->weibo }}
                            </td>
                        </tr>
                        <tr>
                            <th width="130">微信</th>
                            <td>
                                {{ $profile->weixin }}
                            </td>
                        </tr>
                        <tr>
                            <th width="130">QQ</th>
                            <td>
                                {{ $profile->qq }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
    </div>

</div>
