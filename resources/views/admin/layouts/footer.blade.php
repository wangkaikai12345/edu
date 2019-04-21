<!-- Footer -->
<footer class="site-footer">
    {{--<div class="site-footer-legal">© 2018 <a href="http://themeforest.net/item/remark-responsive-bootstrap-admin-template/11989202">Remark</a></div>--}}
    {{--<div class="site-footer-right">--}}
        {{--Crafted with <i class="red-600 wb wb-heart"></i> by <a href="https://themeforest.net/user/creation-studio">Creation Studio</a>--}}
    {{--</div>--}}
</footer>
<!-- Core  -->
<script src="/backstage/global/vendor/babel-external-helpers/babel-external-helpers.js"></script>
{{--<script src="/backstage/global/vendor/jquery/jquery.js"></script>--}}
<script src="/backstage/global/vendor/jquery/1.11.3/jquery.min.js"></script>
<script src="/backstage/global/vendor/popper-js/umd/popper.min.js"></script>
<script src="/backstage/global/vendor/bootstrap/bootstrap.js"></script>
<script src="/backstage/global/vendor/animsition/animsition.js"></script>
<script src="/backstage/global/vendor/mousewheel/jquery.mousewheel.js"></script>
<script src="/backstage/global/vendor/asscrollbar/jquery-asScrollbar.js"></script>
<script src="/backstage/global/vendor/asscrollable/jquery-asScrollable.js"></script>
<script src="/backstage/global/vendor/ashoverscroll/jquery-asHoverScroll.js"></script>

<!-- Plugins -->
<script src="/backstage/global/vendor/switchery/switchery.js"></script>
<script src="/backstage/global/vendor/intro-js/intro.js"></script>
<script src="/backstage/global/vendor/screenfull/screenfull.js"></script>
<script src="/backstage/global/vendor/slidepanel/jquery-slidePanel.js"></script>
<script src="/backstage/global/vendor/layout-grid/layout-grid.js"></script>

<!-- Scripts -->
<script src="/backstage/global/js/Component.js"></script>
<script src="/backstage/global/js/Plugin.js"></script>
<script src="/backstage/global/js/Base.js"></script>
<script src="/backstage/global/js/Config.js"></script>

<script src="/backstage/assets/js/Section/Menubar.js"></script>
<script src="/backstage/assets/js/Section/Sidebar.js"></script>
<script src="/backstage/assets/js/Section/PageAside.js"></script>
<script src="/backstage/assets/js/Plugin/menu.js"></script>

<!-- Config -->
<script src="/backstage/global/js/config/colors.js"></script>
<script src="/backstage/assets/js/config/tour.js"></script>
<script>Config.set('assets', '../../assets');</script>

<!-- Page -->
<script src="/backstage/assets/js/Site.js"></script>
<script src="/backstage/global/js/Plugin/asscrollable.js"></script>
<script src="/backstage/global/js/Plugin/slidepanel.js"></script>
<script src="/backstage/global/js/Plugin/switchery.js"></script>
<script src="/backstage/global/vendor/alertify/alertify.js"></script>
<script src="/backstage/global/vendor/notie/notie.js"></script>
<script src="/backstage/global/js/Plugin/alertify.js"></script>
<script src="/backstage/global/js/Plugin/notie-js.js"></script>

<script>
    function serializeObject($form)
    {
        var o = {};
        var a = $form.serializeArray();
        $.each(a, function() {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };
</script>
