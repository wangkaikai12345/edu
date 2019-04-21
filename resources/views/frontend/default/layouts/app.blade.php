<!doctype html>
<html lang="zh-CN" class="full-height">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', '猿代码')</title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta http-equiv="expires" content="0">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="cache-control" content="no-cache">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <link href="{{ asset('dist/vendor/css/0.css') }}" rel="stylesheet">
    @yield('style')
</head>
<body class="white-skin">
    @inject('index', 'App\Handlers\IndexHandler')
    @include('frontend.default.layouts.header')
    <main class="py-6 mb-5">
        <div class="container">
            @yield('content')
        </div>
    </main>
    @include('frontend.default.layouts.footer')
    <script type="text/javascript" src="{{ asset('/dist/vendor/chunk/index.js') }}"></script>
    @yield('script')
    <script>
        window.onload = function () {
            var selectSelf = null
                ,debug = false
                ,pageLoaded = false;

            $(document).on('click', '.searchItem', function () {
                window.location.href = `/${$(this).data('type')}/${$(this).data('id')}`;
                return;
            });

            var searchTimer = null
                ,dropdownElm = $('#headerSearch .dropdown-content')
                ,searchLoadElm = dropdownElm.find('.searchLoading');

            function searchAjax(val) {
                pageLoaded = true;

                dropdownElm.find('li:not(.searchLoading)').remove();

                searchTimer = setTimeout(function () {
                    edu.ajax({
                        url: '/search',
                        method: 'get',
                        data: {
                            keyword: val
                        },
                        callback: (res) => {
                            var { classroom, course, domain } = res.data
                                ,html = '';
                            if(classroom.length) {
                                html += `<li class="disabled active"><span class="filtrable">搜索到的班级</span></li>`
                                classroom.map(item => {
                                    html += `<li class="searchItem" data-type="classrooms" data-id="${item.id}"><img alt="" src="${item.cover ? domain + '/' + item.cover : ''}" class="rounded-circle"><span class="filtrable">${item.title}</span></li>`
                                });
                            }

                            if(course.length) {
                                html += `<li class="disabled active"><span class="filtrable">搜索到的课程</span></li>`
                                course.map(item => {
                                    html += `<li class="searchItem" data-type="courses" data-id="${item.id}"><img alt="" src="${item.cover ? domain + '/' + item.cover : ''}" class="rounded-circle"><span class="filtrable">${item.title}</span></li>`
                                });
                            }

                            searchLoadElm.hide();

                            dropdownElm.append(html);
                        }
                    })
                }, 1000);
            }

            $('#headerSearch input')
                .on('input propertychange', function(t) {
                var _self = $(this)
                    ,val = _self.val();

                searchLoadElm
                    .css('display', 'inline-block')
                    .find('.preloader-wrapper')
                    .css('opacity', 1);

                if(!dropdownElm.hasClass('active')) {
                    dropdownElm
                        .addClass('active')
                        .animate({
                            'opacity': 1
                        }, 300);
                }

                if(val.replace(/(^s*)|(s*$)/g, "").length === 0) {
                    dropdownElm
                        .removeClass('active')
                        .css({
                            'opacity': 0
                        });
                    return;
                }

                if (typeof searchTimer === 'number') {
                    clearTimeout(searchTimer);
                }

                searchAjax(val);
            })
                .on({
                    focus: function () {
                        var val = $(this).val();
                        if(val.replace(/(^s*)|(s*$)/g, "").length !== 0) {
                            if(!dropdownElm.hasClass('active')) {
                                dropdownElm
                                    .addClass('active')
                                    .animate({
                                        'opacity': 1
                                    }, 300);
                            }
                            if(!pageLoaded) {
                                searchLoadElm.css('display', 'inline-block');
                                searchAjax(val);
                            }
                        }
                    }
                });

            $(document).on({
                click: function (_e) {
                    var _con = $('#headerSearch,#headerSearch .dropdown-content');
                    if(!_con.is(_e.target) && _con.has(_e.target).length === 0){
                        $('#headerSearch .dropdown-content')
                            .removeClass('active')
                            .animate({
                                'opacity': 0
                            }, 300)
                    }
                }
            });

            @if (Session::has('message'))
                edu.toastr.info("{{ Session::get('message') }}")
            @endif

            @if (Session::has('success'))
                edu.toastr.success("{{ Session::get('success') }}")
            @endif

            @if (Session::has('danger'))
                edu.toastr.error("{{ Session::get('danger') }}")
            @endif

            @if (count($errors) > 0)
                edu.toastr.error("{{ $errors->all()[0] }}")
            @endif
        }
    </script>

</body>
</html>