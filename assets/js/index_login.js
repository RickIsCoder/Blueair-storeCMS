/* global $, alert, location ,console */
$(function () {

    $('#login').click(function () {
        var username = $('#username').val();
        var password = $('#password').val();

        $.post(
            'verify', {
                username: username,
                password: password
            },
            function (data) {
                switch (data) {
                case '1':
                    location.href = 'welcome.html';
                     console.log(data);
                    break;
                case '2':
                    location.href = 'welcome.html';
                    // alert('用户类型'+data);
                    break;
                default:
                    location.href = 'login.html';
                    console.log(data);
                    alert('账号或密码错误');
                    break;
                }
            }
        );
    });


});