$(function () {
	$('#login-button').click(function () {
        login();
    });
    
    $("#username, #password").keypress(function(evt){
        if(evt.which == 13){
            login();
        }
    });
    
    function login(){
        var username = $('#username').val();
        var password = $('#password').val();
        
        if(username == "" || password == ""){
            if (username == "") {
                $("#username").parent().parent().addClass("has-error");
            }
            if(password == ""){
                $("#password").parent().parent().addClass("has-error");
            }
            return ;
        }
        $.post(
            'verify', {
                username: username,
                password: password
            },
            function (data) {
            	if (data == 'success') {
            		 location.href = 'index.html';
            	}else if(data == 'failure'){
            		$("#error-label").show();
            	}
            }
        );
    }
});