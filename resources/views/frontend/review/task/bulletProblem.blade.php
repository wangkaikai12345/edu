<link rel="stylesheet" href="{{ mix('/css/front/task/bulletProblem/index.css') }}">

<script type="text/javascript">


    // $(document).on('click', '.option_item', function () {
    //     if ($(this).hasClass('active')) {
    //         set_style('del', $(this), 'active');
    //     } else {
    //         set_style('del', $('.option_item'), 'active');
    //         set_style('add', $(this), 'active', 'o_active');
    //     }
    // })
    //
    // $('#next_end').click(function () {
    //     var now_status = $(this).html();
    //
    //     if (now_status == '提交') {
    //         if (!$('.option_item').hasClass('active')) {
    //             edu.alert('danger', '请选择选项后提交！');
    //             return false
    //         }
    //
    //         var $qNow = $('.q_now').html() * 1;
    //         var $qTotal = $('.q_total').html() * 1;
    //
    //         if ($qNow == $qTotal) {
    //             $(this).html('提交答案');
    //         } else {
    //             $(this).html('下一题');
    //         }
    //
    //
    //         // 填充答案解析
    //         // $('.modal_answer').show().children().children('.question_title').html('哈哈哈哈哈');
    //         // 显示答案解析
    //         $('.modal_answer').show();
    //
    //         // 设置对的选项和错的选项的样式
    //         set_style('del', $('.option_item'), 'active'); // 清除选项选中的样式
    //
    //         set_style('add', $('.option_item'), 'true_style', 'o_true_style', 2); // 设置对的选项的样式
    //         set_style('add', $('.option_item'), 'false_style', 'o_false_style', 3); // 设置错的选项的样式
    //     } else if (now_status == '下一题') {
    //         // 清空样式
    //         set_style('del', $('.option_item'), 'true_style false_style');
    //
    //         // 自增题号
    //         $('.q_now').html(function () {
    //             return $(this).html() * 1 + 1;
    //         })
    //
    //         $(this).html('提交');
    //
    //         // 隐藏答案解析
    //         $('.modal_answer').hide();
    //
    //         // 展示下一题的题目信息
    //         show();
    //
    //     } else if (now_status == '提交答案') {
    //
    //         // 提交答题信息
    //         $('.bulletProblem_body').hide();
    //     }
    //
    // });
    //
    //
    // /**
    //  * @param operation 操作 add 增加样式 del 移除样式
    //  * @param dom 元素
    //  * @param style 父元素样式
    //  * @param c_style 子元素样式
    //  * @param eq 序号
    //  */
    // function set_style(operation, dom, style, c_style = false, eq = false) {
    //     if (operation == 'add') {
    //         if (eq) {
    //             dom.eq(eq).addClass(style).children('.option_status').removeClass('o_active').addClass(c_style).show();
    //         } else {
    //             dom.addClass(style).children('.option_status').removeClass('o_active').addClass(c_style).show();
    //         }
    //     } else if (operation == 'del') {
    //         dom.removeClass(style).children('.option_status').hide();
    //     }
    // }
    //
    // /**
    //  * 用于切换题目
    //  */
    // function show() {
    //
    // }
</script>

