@extends('admin.layouts.app')
@section('style')
    <link rel="stylesheet" href="/backstage/assets/examples/css/uikit/modals.css">
    <style>
        .table a {
            text-decoration: none;
        }

        .required {
            color: red;
        }

        td {
            line-height: 36px
        }

        .panel > .table-bordered, .panel > .table-responsive > .table-bordered {
            border: 1px solid #e4eaec;
        !important;
        }
    </style>
@stop
@section('page-title', '文章管理')
@section('content')
    {{--小的modal--}}
    <div class="modal fade" id="mySimpleModal" aria-labelledby="mySimpleModalLabel" role="dialog" tabindex="-1"
         style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-simple">
            <form class="modal-content">

            </form>
        </div>
    </div>
    <div class="panel">
        <div class="panel-body container-fluid" style="padding-bottom: 0px">
            <div class="row row-lg">
                <div class="col-xl-12">
                    <!-- Example Tabs -->
                    <div class="example-wrap">
                        <div class="nav-tabs-horizontal" data-plugin="tabs">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link  active show" href="{{ route('backstage.posts.index') }}">文章管理
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" href="{{ route('backstage.posts.recommend.index') }}">推荐文章
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content pt-20">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="panel">
                                            <header class="panel-heading">
                                                <h3 class="panel-title">文章搜索:</h3>
                                            </header>
                                            <div class="panel-body" style="padding:1px 30px;">
                                                <form action="{{ route('backstage.posts.index') }}" method="GET">
                                                    <div class="row">
                                                        <div class="form-group col-md-3">
                                                            <input type="text" class="form-control" name="title"
                                                                   placeholder="标题"
                                                                   autocomplete="off" value="{{ request('title') }}">
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <input type="text" class="form-control" name="creator"
                                                                   placeholder="创建人"
                                                                   autocomplete="off" value="{{ request('creator') }}">
                                                        </div>


                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-3">
                                                            <select class="form-control" name="essence">
                                                                <option value="2">
                                                                    <font style="vertical-align: inherit;">
                                                                        <font style="vertical-align: inherit;">
                                                                            加精状态
                                                                        </font>
                                                                    </font>
                                                                </option>
                                                                <option value="1" @if(request('essence') == 1) selected @endif>
                                                                    <font style="vertical-align: inherit;">
                                                                        <font style="vertical-align: inherit;">
                                                                            加精
                                                                        </font>
                                                                    </font>
                                                                </option>
                                                                <option value="3" @if(request('essence') == 3) selected @endif>
                                                                    <font style="vertical-align: inherit;">
                                                                        <font style="vertical-align: inherit;">
                                                                            未加精
                                                                        </font>
                                                                    </font>
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <select class="form-control" name="category_id">
                                                                <option value="0">
                                                                    <font style="vertical-align: inherit;">
                                                                        <font style="vertical-align: inherit;">
                                                                            文章分类
                                                                        </font>
                                                                    </font>
                                                                </option>
                                                                @foreach($categories as $value)
                                                                    <option value="{{$value->id}}" @if(request('category_id') == $value->id) selected @endif>
                                                                        <font style="vertical-align: inherit;">
                                                                            <font style="vertical-align: inherit;">
                                                                                {{ $value->name }}
                                                                            </font>
                                                                        </font>
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <select class="form-control" name="status">
                                                                <option value="0">
                                                                    <font style="vertical-align: inherit;">
                                                                        <font style="vertical-align: inherit;">
                                                                            发布状态
                                                                        </font>
                                                                    </font>
                                                                </option>
                                                                @foreach(\App\Enums\Status::getValues() as $value)
                                                                    <option value="{{$value}}" @if(request('status') == $value) selected @endif>
                                                                        <font style="vertical-align: inherit;">
                                                                            <font style="vertical-align: inherit;">
                                                                                {{ \App\Enums\Status::getDescription($value) }}
                                                                            </font>
                                                                        </font>
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <select class="form-control" name="stick">
                                                                <option value="2">
                                                                    <font style="vertical-align: inherit;">
                                                                        <font style="vertical-align: inherit;">
                                                                            置顶状态
                                                                        </font>
                                                                    </font>
                                                                </option>
                                                                <option value="1" @if(request('stick') == 1) selected @endif>
                                                                    <font style="vertical-align: inherit;">
                                                                        <font style="vertical-align: inherit;">
                                                                            置顶
                                                                        </font>
                                                                    </font>
                                                                </option>
                                                                <option value="3" @if(request('stick') == 3) selected @endif>
                                                                    <font style="vertical-align: inherit;">
                                                                        <font style="vertical-align: inherit;">
                                                                            未置顶
                                                                        </font>
                                                                    </font>
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group text-right">
                                                        <button type="submit" class="btn btn-primary"><font
                                                                    style="vertical-align: inherit;">
                                                                <font style="vertical-align: inherit;">搜索
                                                                </font></font></button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="panel">
                <header class="panel-heading">
                    <h3 class="panel-title" style="display: inline-block"></h3>
                    <a class="btn btn-sm btn-outline btn-primary"
                       style="float: right;margin-top: 10px;margin-right: 30px;margin-bottom: 10px"
                       href="{{ route('backstage.posts.create')}}">
                        <i class="icon wb-plus" aria-hidden="true"></i> 添加文章
                    </a>
                </header>
                <div class="panel-body ">
                    <table class="table table-bordered table-hover  toggle-circle"
                           data-paging="false">
                        <thead>
                        <tr>
                            <th data-name="username">标题</th>
                            <th data-name="phone" data-breakpoints="xs sm">创建人</th>
                            <th data-name="email" data-breakpoints="xs sm">分类</th>
                            <th data-name="email" data-breakpoints="xs sm">状态</th>
                            <th data-name="created_at" data-breakpoints="xs sm">点赞数</th>
                            <th data-name="last_logined_at" data-breakpoints="xs sm">查看数</th>
                            <th data-name="last_logined_at" data-breakpoints="xs sm">回复数</th>
                            {{--<th data-name="last_logined_at" data-breakpoints="xs sm">属性</th>--}}
                            <th data-name="last_logined_at">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($posts as $post)
                            <tr>
                                <td>{{ $post->title }}</td>
                                <td>{{ $post->user->username }}</td>
                                <td>{{ $post->category->name }}</td>
                                <td id="post-status-cotent-{{$post->id}}">{{ \App\Enums\Status::getDescription($post->status) }}</td>
                                <td>{{ $post->vote_count }}</td>
                                <td>{{ $post->view_count }}</td>
                                <td>{{ $post->reply_count }}</td>
                                {{--<td>{{ $post->reply_count }}</td>--}}
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-outline">
                                            <font style="vertical-align: inherit;">
                                                <a style="vertical-align: inherit;color: #76838f"
                                                   href="{{ route('backstage.posts.edit', ['post' => $post->id ])}}">
                                                    编辑文章
                                                </a>
                                            </font>
                                        </button>
                                        <button type="button" class="btn btn-default dropdown-toggle btn-outline"
                                                id="exampleSplitDropdown1" data-toggle="dropdown"
                                                aria-expanded="false"></button>
                                        <div class="dropdown-menu" aria-labelledby="exampleSplitDropdown1" role="menu"
                                             x-placement="bottom-start"
                                             style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(57px, 39px, 0px);">


                                            <a class="dropdown-item" href="javascript:void(0)" role="menuitem"
                                               id="post-publish-{{$post->id}}"
                                               @if($post->status == 'published') style="display: none"
                                               @endif onclick="publicshPost('{{route('backstage.posts.publish', ['post' => $post->id])}}', '{{$post->id}}')">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">
                                                        发布文章
                                                    </font>
                                                </font>
                                            </a>
                                            <a class="dropdown-item" href="javascript:void(0)" role="menuitem"
                                               id="post-close-{{$post->id}}"
                                               @if($post->status != 'published') style="display: none"
                                               @endif onclick="closePost('{{route('backstage.posts.close', ['post' => $post->id])}}', '{{$post->id}}')">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">
                                                        关闭文章
                                                    </font>
                                                </font>
                                            </a>
                                            <a class="dropdown-item" href="javascript:void(0)" role="menuitem"
                                               id="essence-{{$post->id}}"
                                               @if((boolean)$post->is_essence) style="display: none"
                                               @endif onclick="essence('{{route('backstage.posts.essence', ['post' => $post->id])}}', '{{$post->id}}')">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">
                                                        文章加精
                                                    </font>
                                                </font>
                                            </a>
                                            <a class="dropdown-item" href="javascript:void(0)" role="menuitem"
                                               id="un-essence-{{$post->id}}"
                                               @if(!(boolean)$post->is_essence) style="display: none"
                                               @endif onclick="unEssence('{{route('backstage.posts.un-essence', ['post' => $post->id])}}', '{{$post->id}}')">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">
                                                        取消精华
                                                    </font>
                                                </font>
                                            </a>
                                            <a class="dropdown-item" href="javascript:void(0)" role="menuitem"
                                               id="stick-{{$post->id}}"
                                               @if((boolean)$post->is_stick) style="display: none"
                                               @endif onclick="stick('{{route("backstage.posts.stick", ['post' => $post->id])}}', '{{ $post->id }}')">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">
                                                        置顶文章
                                                    </font>
                                                </font>
                                            </a>
                                            <a class="dropdown-item" href="javascript:void(0)" role="menuitem"
                                               id="un-stick-{{$post->id}}"
                                               @if(!(boolean)$post->is_stick) style="display: none"
                                               @endif onclick="unStick('{{route("backstage.posts.un-stick", ['post' => $post->id])}}', '{{ $post->id }}')">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">
                                                        取消置顶
                                                    </font>
                                                </font>
                                            </a>
                                            <a class="dropdown-item" href="javascript:void(0)" role="menuitem" id="recommend-{{$post->id}}"
                                               data-target="#mySimpleModal" data-toggle="modal"
                                               onclick="showSimpleModal('{{route("backstage.posts.recommend.show", ['post' => $post->id])}}')"
                                               @if((boolean)$post->is_recommend) style="display: none" @endif>
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">
                                                        推荐文章
                                                    </font>
                                                </font>
                                            </a>
                                            <a class="dropdown-item" href="javascript:void(0)" role="menuitem" id="un-recommend-{{$post->id}}"
                                               @if(!(boolean)$post->is_recommend) style="display: none"
                                               @endif onclick="unRecommend('{{route("backstage.posts.un-recommend",  ['post' => $post->id])}}', '{{$post->id}}')">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">
                                                        取消推荐
                                                    </font>
                                                </font>
                                            </a>

                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination-body">
                    {{ $posts->appends(request()->all())->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script>
        // 展示modal
        function showSimpleModal(url) {
            $("#mySimpleModal .modal-content").load(url);
        }

        // 展示modal
        function showLgModal(url) {
            $("#myLgModal .modal-content").load(url);
        }

        $("body").on("hidden.bs.modal", function () {
            // 这个#showModal是模态框的id
            $(this).removeData("bs.modal");
            $(this).find(".modal-content").children().remove();
        });

        // 发布文章
        function publicshPost(fetchUrl, id) {
            alertify.theme("bootstrap");
            alertify
                .okBtn("确定")
                .cancelBtn("取消")
                .confirm("确定发布文章?", function () {
                    // 进行AJAX请求
                    $.ajax({
                        url: fetchUrl,
                        type: 'PATCH',
                        dataType: 'JSON',
                        data: {"_token": "{{csrf_token()}}"},
                        success: function (response) {
                            // 提示操作成功
                            notie.alert({'type': 1, 'text': '操作成功', 'time': 1.5});
                            $('#post-publish-' + id).hide();
                            $('#post-close-' + id).show();
                            $('#post-status-cotent-' + id).html('已发布');
                        },
                        error: function (error) {
                            // 获取返回的状态码
                            const statusCode = error.status;

                            // 提示信息
                            let message = null;
                            // 状态码判断
                            switch (statusCode) {
                                case 422:
                                    message = getFormValidationMessage(error.responseJSON.errors);
                                    break;
                                default:
                                    message = !error.responseJSON.message ? '操作失败' : error.responseJSON.message;
                                    break;
                            }

                            // 弹出提示
                            notie.alert({'type': 3, 'text': message, 'time': 1.5});
                        }
                    });
                }, function () {

                });
        }

        // 发布文章
        function closePost(fetchUrl, id) {
            alertify.theme("bootstrap");
            alertify
                .okBtn("确定")
                .cancelBtn("取消")
                .confirm("确定关闭文章?", function () {
                    // 进行AJAX请求
                    $.ajax({
                        url: fetchUrl,
                        type: 'PATCH',
                        dataType: 'JSON',
                        data: {"_token": "{{csrf_token()}}"},
                        success: function (response) {
                            // 提示操作成功
                            notie.alert({'type': 1, 'text': '操作成功', 'time': 1.5});
                            $('#post-publish-' + id).show();
                            $('#post-close-' + id).hide();
                            $('#post-status-cotent-' + id).html('已关闭');
                        },
                        error: function (error) {
                            // 获取返回的状态码
                            const statusCode = error.status;

                            // 提示信息
                            let message = null;
                            // 状态码判断
                            switch (statusCode) {
                                case 422:
                                    message = getFormValidationMessage(error.responseJSON.errors);
                                    break;
                                default:
                                    message = !error.responseJSON.message ? '操作失败' : error.responseJSON.message;
                                    break;
                            }

                            // 弹出提示
                            notie.alert({'type': 3, 'text': message, 'time': 1.5});
                        }
                    });
                }, function () {

                });
        }

        // 置顶文章
        function stick(fetchUrl, id) {
            alertify.theme("bootstrap");
            alertify
                .okBtn("确定")
                .cancelBtn("取消")
                .confirm("确定置顶文章?", function () {
                    // 进行AJAX请求
                    $.ajax({
                        url: fetchUrl,
                        type: 'PATCH',
                        dataType: 'JSON',
                        data: {"_token": "{{csrf_token()}}"},
                        success: function (response) {
                            // 提示操作成功
                            notie.alert({'type': 1, 'text': '操作成功', 'time': 1.5});
                            $('#un-stick-' + id).show();
                            $('#stick-' + id).hide();
                        },
                        error: function (error) {
                            // 获取返回的状态码
                            const statusCode = error.status;

                            // 提示信息
                            let message = null;
                            // 状态码判断
                            switch (statusCode) {
                                case 422:
                                    message = getFormValidationMessage(error.responseJSON.errors);
                                    break;
                                default:
                                    message = !error.responseJSON.message ? '操作失败' : error.responseJSON.message;
                                    break;
                            }

                            // 弹出提示
                            notie.alert({'type': 3, 'text': message, 'time': 1.5});
                        }
                    });
                }, function () {

                });
        }

        // 取消置顶
        function unStick(fetchUrl, id) {
            alertify.theme("bootstrap");
            alertify
                .okBtn("确定")
                .cancelBtn("取消")
                .confirm("确定取消置顶?", function () {
                    // 进行AJAX请求
                    $.ajax({
                        url: fetchUrl,
                        type: 'PATCH',
                        dataType: 'JSON',
                        data: {"_token": "{{csrf_token()}}"},
                        success: function (response) {
                            // 提示操作成功
                            notie.alert({'type': 1, 'text': '操作成功', 'time': 1.5});
                            $('#un-stick-' + id).hide();
                            $('#stick-' + id).show();
                        },
                        error: function (error) {
                            // 获取返回的状态码
                            const statusCode = error.status;

                            // 提示信息
                            let message = null;
                            // 状态码判断
                            switch (statusCode) {
                                case 422:
                                    message = getFormValidationMessage(error.responseJSON.errors);
                                    break;
                                default:
                                    message = !error.responseJSON.message ? '操作失败' : error.responseJSON.message;
                                    break;
                            }

                            // 弹出提示
                            notie.alert({'type': 3, 'text': message, 'time': 1.5});
                        }
                    });
                }, function () {

                });
        }

        // 精华文章
        function essence(fetchUrl, id) {
            alertify.theme("bootstrap");
            alertify
                .okBtn("确定")
                .cancelBtn("取消")
                .confirm("确定加精文章?", function () {
                    // 进行AJAX请求
                    $.ajax({
                        url: fetchUrl,
                        type: 'PATCH',
                        dataType: 'JSON',
                        data: {"_token": "{{csrf_token()}}"},
                        success: function (response) {
                            // 提示操作成功
                            notie.alert({'type': 1, 'text': '操作成功', 'time': 1.5});
                            $('#un-essence-' + id).show();
                            $('#essence-' + id).hide();
                        },
                        error: function (error) {
                            // 获取返回的状态码
                            const statusCode = error.status;

                            // 提示信息
                            let message = null;
                            // 状态码判断
                            switch (statusCode) {
                                case 422:
                                    message = getFormValidationMessage(error.responseJSON.errors);
                                    break;
                                default:
                                    message = !error.responseJSON.message ? '操作失败' : error.responseJSON.message;
                                    break;
                            }

                            // 弹出提示
                            notie.alert({'type': 3, 'text': message, 'time': 1.5});
                        }
                    });
                }, function () {

                });
        }

        // 取消置顶
        function unEssence(fetchUrl, id) {
            alertify.theme("bootstrap");
            alertify
                .okBtn("确定")
                .cancelBtn("取消")
                .confirm("确定取消加精?", function () {
                    // 进行AJAX请求
                    $.ajax({
                        url: fetchUrl,
                        type: 'PATCH',
                        dataType: 'JSON',
                        data: {"_token": "{{csrf_token()}}"},
                        success: function (response) {
                            // 提示操作成功
                            notie.alert({'type': 1, 'text': '操作成功', 'time': 1.5});
                            $('#un-essence-' + id).hide();
                            $('#essence-' + id).show();
                        },
                        error: function (error) {
                            // 获取返回的状态码
                            const statusCode = error.status;

                            // 提示信息
                            let message = null;
                            // 状态码判断
                            switch (statusCode) {
                                case 422:
                                    message = getFormValidationMessage(error.responseJSON.errors);
                                    break;
                                default:
                                    message = !error.responseJSON.message ? '操作失败' : error.responseJSON.message;
                                    break;
                            }

                            // 弹出提示
                            notie.alert({'type': 3, 'text': message, 'time': 1.5});
                        }
                    });
                }, function () {

                });
        }

        // 取消推荐
        function unRecommend(fetchUrl, id) {
            alertify.theme("bootstrap");
            alertify
                .okBtn("确定")
                .cancelBtn("取消")
                .confirm("确定取消推荐?", function () {
                    // 进行AJAX请求
                    $.ajax({
                        url: fetchUrl,
                        type: 'PATCH',
                        dataType: 'JSON',
                        data: {"_token": "{{csrf_token()}}"},
                        success: function (response) {
                            // 提示操作成功
                            notie.alert({'type': 1, 'text': '操作成功', 'time': 1.5});
                            $('#recommend-' + id).show();
                            $('#un-recommend-' + id).hide();
                        },
                        error: function (error) {
                            // 获取返回的状态码
                            const statusCode = error.status;

                            // 提示信息
                            let message = null;
                            // 状态码判断
                            switch (statusCode) {
                                case 422:
                                    message = getFormValidationMessage(error.responseJSON.errors);
                                    break;
                                default:
                                    message = !error.responseJSON.message ? '操作失败' : error.responseJSON.message;
                                    break;
                            }

                            // 弹出提示
                            notie.alert({'type': 3, 'text': message, 'time': 1.5});
                        }
                    });
                }, function () {

                });
        }
    </script>
@stop








