/**
* ================================================
* title：drag.js
* time: 2016-10-7
* author: 杨凤玲
* content: 登录界面拖动滑块的js/jq
* ================================================
*/

    $(function ($) {
        //弹出登录
        $("#example").hover(function () {
            $(this).stop().animate({
                opacity: '1'
            }, 600);
        }, function () {
            $(this).stop().animate({
                opacity: '0.6'
            }, 1000);
        }).on('click', function () {
            $("body").append("<div id='mask'></div>");
            $("#mask").addClass("mask").fadeIn("slow");
            $("#LoginBox").fadeIn("slow");
        });
        //
        //按钮的透明度
        $("#loginbtn").hover(function () {
            $(this).stop().animate({
                opacity: '1'
            }, 600);
        }, function () {
            $(this).stop().animate({
                opacity: '0.8'
            }, 1000);
        });
        //文本框不允许为空---按钮触发
        $("#loginbtn").on('click', function () {
            var txtName = $("#txtName").val();
            var txtPwd = $("#txtPwd").val();
            if (txtName == "" || txtName == undefined || txtName == null) {
                if (txtPwd == "" || txtPwd == undefined || txtPwd == null) {
                    $(".warning").css({ display: 'block' });
                }
                else {
                    $("#warn").css({ display: 'block' });
                    $("#warn2").css({ display: 'none' });
                }
            }
            else {
                if (txtPwd == "" || txtPwd == undefined || txtPwd == null) {
                    $("#warn").css({ display: 'none' });
                    $(".warn2").css({ display: 'block' });
                }
                else {
                    $(".warning").css({ display: 'none' });
                }
            }
        });
        //文本框不允许为空---单个文本触发
        $("#txtName").on('blur', function () {
            var txtName = $("#txtName").val();
            if (txtName == "" || txtName == undefined || txtName == null) {
                $("#warn").css({ display: 'block' });
            }
            else {
                $("#warn").css({ display: 'none' });
            }
        });
        $("#txtName").on('focus', function () {
            $("#warn").css({ display: 'none' });
        });
        //
        $("#txtPwd").on('blur', function () {
            var txtName = $("#txtPwd").val();
            if (txtName == "" || txtName == undefined || txtName == null) {
                $("#warn2").css({ display: 'block' });
            }
            else {
                $("#warn2").css({ display: 'none' });
            }
        });
        $("#txtPwd").on('focus', function () {
            $("#warn2").css({ display: 'none' });
        });
        //关闭
        $(".close_btn").hover(function () { $(this).css({ color: 'black' }) }, function () { $(this).css({ color: '#999' }) }).on('click', function () {
            $("#LoginBox").fadeOut("fast");
            $("#mask").css({ display: 'none' });
        });
    });
(function($){
    $.fn.drag = function(options){
        var x, drag = this, isMove = false, defaults = {
        };
        var options = $.extend(defaults, options);
        //添加背景，文字，滑块
        var html = '<div class="drag_bg"></div>'+
                    '<div class="drag_text" id="text" onselectstart="return false;" unselectable="on">拖动滑块验证</div>'+
                    '<div class="handler handler_bg"></div>';
        this.append(html);

        var handler = drag.find('.handler');
        var drag_bg = drag.find('.drag_bg');
        var text = drag.find('.drag_text');
        var maxWidth = drag.width() - handler.width();  //能滑动的最大间距

        //鼠标按下时候的x轴的位置
        handler.mousedown(function(e){
            isMove = true;
            x = e.pageX - parseInt(handler.css('left'), 10);
        });

        //鼠标指针在上下文移动时，移动距离大于0小于最大间距，滑块x轴位置等于鼠标移动距离
        $(document).mousemove(function(e){
            var _x = e.pageX - x;
            if(isMove){
                if(_x > 0 && _x <= maxWidth){
                    handler.css({'left': _x});
                    drag_bg.css({'width': _x});
                }else if(_x > maxWidth){  //鼠标指针移动距离达到最大时清空事件
                    dragOk();
                }
            }
        }).mouseup(function(e){
            isMove = false;
            var _x = e.pageX - x;
            if(_x < maxWidth){ //鼠标松开时，如果没有达到最大距离位置，滑块就返回初始位置
                handler.css({'left': 0});
                drag_bg.css({'width': 0});
            }
        });

        //清空事件
        function dragOk(){
            handler.removeClass('handler_bg').addClass('handler_ok_bg');
            text.text('验证通过');
            drag.css({'color': '#fff'});
            handler.unbind('mousedown');
            $(document).unbind('mousemove');
            $(document).unbind('mouseup');
            $('#loginbtn').removeAttr('disabled');

        }
    };
})(jQuery);


