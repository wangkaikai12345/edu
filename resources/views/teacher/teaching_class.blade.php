<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>我的在教的班级</title>
    <link rel="stylesheet" href="{{ mix('/css/front/myTeaching/teachingCourses.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/theme.css') }}">
    <link rel="stylesheet" href="/vendor/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="/vendor/bootstrap/dist/css/bootstrap.min.css">
</head>
<body>
@include ('teacher.header')

<div class="czh_teaching_content">
    <div class="row teachingCourseHeader">
        <div class="header_left col-md-10">
            <form action="">
                <div class="courseTypeInp">
                    <select class="form-control" data-toggle="select" title="Simple select" data-live-search="true"
                            data-live-search-placeholder="Search ...">
                        <option>课程类型</option>
                        <option>正式班</option>
                        <option>双元班</option>
                        <option>线下班</option>
                        <option>试听班</option>
                    </select>
                </div>
                <div class="courseNameInp form-group">
                    <p class="courseNameInp_p">课程名称</p>
                    <input type="text" class="form-control courseNameInp_inp">
                </div>
                <div class="courseCreatorInp form-group">
                    <p class="courseCreatorInp_p">创建者</p>
                    <input type="text" class="form-control courseCreatorInp_inp">
                </div>

                <div class="courseSearch">
                    <button class="btn courseSearchBtn">搜索</button>
                </div>
            </form>
        </div>
        <div class="header_right col-md-2">
            <button class="btn createCourseBtn">＋ 创建课程</button>
        </div>
    </div>
    <div class="row coursesContent">
        <div class="col-md-3 courseDataBody">
            <div class=" courseData">
                <div class="courseImg">
                    <img src="/imgs/user/front/myTeaching/course.png" alt="">
                </div>
                <div class="titStaPic">
                    <span class="courseTitle">PHP正式班</span>
                    <span class="courseStatus badge badge-pill badge-warning text-uppercase">未发布</span>
                    <span class="courseCreator">某某某</span>
                    <span class="coursePrice">价格(元)：<span>19800</span></span>
                    <span class="courseStudent">学员：<span>110</span></span>
                </div>
                <div class="courseFooter">
                    <button class="btn courseManage">管理</button>
                    <i class="iconfont">&#xe62b;</i>
                </div>
            </div>
        </div>

    </div>
    <nav class="pageNumber" aria-label="Page navigation example">
        <ul class="pagination">
            <li class="page-item"><a class="page-link" href="#"><i class="iconfont">&#xe9d2;</i></a></li>
            <li class="page-item"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item active"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link" href="#">4</a></li>
            <li class="page-item"><a class="page-link" href="#">5</a></li>
            <li class="page-item"><a class="page-link" href="#">6</a></li>
            <li class="page-item"><a class="page-link" href="#"><i class="iconfont">&#xe632;</i></a></li>
        </ul>
    </nav>
</div>
{{--<script src="/vendor/jquery/dist/jquery.min.js"></script>--}}
<script src="/vendor/select2/dist/js/select2.min.js"></script>
<script src="/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ '/js/theme.js' }}"></script>
<script>

</script>
</body>
</html>