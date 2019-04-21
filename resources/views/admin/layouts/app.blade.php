<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="bootstrap admin template">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}"><!--需要csrf token-->

    <title>@yield('title', 'EDUPlayer')-网站管理后台</title>

    <link rel="apple-touch-icon" href="/backstage/assets/images/apple-touch-icon.png">
    <link rel="shortcut icon" href="/backstage/assets/images/favicon.ico">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="/backstage/global/css/bootstrap.min.css">
    <link rel="stylesheet" href="/backstage/global/css/bootstrap-extend.min.css">
    <link rel="stylesheet" href="/backstage/assets/css/site.min.css">

    <!-- Plugins -->
    <link rel="stylesheet" href="/backstage/global/vendor/animsition/animsition.css">
    <link rel="stylesheet" href="/backstage/global/vendor/asscrollable/asScrollable.css">
    <link rel="stylesheet" href="/backstage/global/vendor/switchery/switchery.css">
    <link rel="stylesheet" href="/backstage/global/vendor/intro-js/introjs.css">
    <link rel="stylesheet" href="/backstage/global/vendor/slidepanel/slidePanel.css">
    <link rel="stylesheet" href="/backstage/global/vendor/flag-icon-css/flag-icon.css">
    <link rel="stylesheet" href="/backstage/global/vendor/layout-grid/layout-grid.css">
    <link rel="stylesheet" href="/backstage/global/vendor/bootstrap-datepicker/bootstrap-datepicker.css">

    <!-- Fonts -->
    <link rel="stylesheet" href="/backstage/global/fonts/web-icons/web-icons.min.css">
    <link rel="stylesheet" href="/backstage/global/fonts/brand-icons/brand-icons.min.css">
    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>
    <link rel="stylesheet" href="{{ asset('backstage/global/vendor/formvalidation/formValidation.css') }}">
    <link rel="stylesheet" href="{{ asset('backstage/assets/examples/css/forms/validation.css') }}">
    <link rel="stylesheet" href="{{ asset('backstage/global/vendor/alertify/alertify.css') }}">
    <link rel="stylesheet" href="{{ asset('backstage/global/vendor/notie/notie.css') }}">
    <link rel="stylesheet" href="{{ asset('backstage/assets/examples/css/advanced/alertify.css') }}">

    <!--[if lt IE 9]>
    <script src="/backstage/global/vendor/html5shiv/html5shiv.min.js"></script>
    <![endif]-->

    <!--[if lt IE 10]>
    <script src="/backstage/global/vendor/media-match/media.match.min.js"></script>
    <script src="/backstage/global/vendor/respond/respond.min.js"></script>
    <![endif]-->

    <!-- Scripts -->
    <script src="/backstage/global/vendor/breakpoints/breakpoints.js"></script>
    <script>
        Breakpoints();
    </script>
    <style>
        .pagination-body {
            padding-bottom: 20px;
        }
        .pagination {
            display: inline-flex;
        !important;
        }

        .pagination-body {
            text-align: center;
        }
    </style>
    @yield('style')
</head>
<body class="animsition" style="opacity: 1!important;">
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> to improve your experience.</p>
<![endif]-->
@include('admin.layouts.nav')


@include('admin.layouts.menubar')

<!-- Page -->
<div class="page">
    <div class="page-header">
        <h1 class="page-title">@yield('page-title', '首页')</h1>
        <p class="page-description"></p>
        {{--      <div class="page-header-actions">
                  <a class="btn btn-sm btn-inverse btn-round" href="http://clippings.github.io/layout-grid/"
                     target="_blank">
                      <i class="icon wb-link" aria-hidden="true"></i>
                      <span class="hidden-sm-down">Official Website</span>
                  </a>
              </div>--}}
    </div>

    <!-- Page Content -->
    <div class="page-content">
        @yield('content')
    </div>
    <!-- End Page Content -->
</div>
<!-- End Page -->


@include('admin.layouts.footer')

<script>
    (function (document, window, $) {
        'use strict';
        var Site = window.Site;
        $(document).ready(function () {
            Site.run();
        });
    })(document, window, jQuery);
</script>
@yield('script')
<div class="modal-backdrop fade" style="display: none"></div>
</body>
@include('admin.layouts.validation')
</html>
