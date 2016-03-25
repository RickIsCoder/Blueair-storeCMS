$(function(){
    var users = [];
    var userType = ["超级管理员", "门店管理员"];
    function getUsers(){
        $.ajax({
            url:"getUserList",
            method:"POST",
            dataType:"JSON",
            success:function(data){
                users = data;
                var appendHtml = "";
                for(var i = 0; i < data.length; i++){
                    var user = data[i];
                    var control = 
                    appendHtml += "<tr data-userid='"+user.userID+"'><td>"+user.username+"</td><td>"+user.password+"</td><td>"+userType[user.userType - 1]+"</td><td>"+user.address+"</td><td>"+user.remark+"</td><td><span class='modify' data-toggle='modal' data-target='#user-form' data-type='2' data-line='"+i+"'></span><span class='delete'></span></td></tr>"
                }
                $("#user-table-body").html(appendHtml);
            }
        });
    }
    
    getUsers();
    
    $("#user-table-body").delegate(".delete", "click", function(){
        var r = confirm("确认删除这个用户么？");
        if(r == true){
            var row = $(this).parent().parent();
            $.ajax({
                url:"delUser",
                method:"POST",
                data:{userID:row.data("userid")},
                success:function(data){
                    if(data == "success"){
                        alert("成功删除用户。");
                        getUsers();
                    }
                    else if(data == "failure"){
                        alert("删除失败");
                    }
                    else{
                        alert(data);
                    }
                }
            });
        }
    });
    
    $("#user-submit").click(function(){
        var username = $("#username").val();
        var password = $("#password").val();
        var address = $("#address").val();
        var remark = $("#mark").val();
        var userType = $("#userType").val();
        if(username != "" && password != "" && address != "" && remark != "" && userType > 0){
            var modal = $('#user-form');
            if(modal.data("userId") == -1){
                $.post("addUser", {
                    username:username,
                    password:password,
                    address:address,
                    remark:remark,
                    userType:userType
                },
                function(data){
                    if(data == true){
                        getUsers();
                        modal.modal('hide');
                    }
                    else if(data == false){
                        alert("操作失败。")
                    }
                    else{
                        alert(data);
                    }
                });
            }
            else{
                $.post("editUser", {
                    userID:modal.data("userId"),
                    username:username,
                    password:password,
                    address:address,
                    remark:remark,
                    userType:userType
                },
                function(data){
                    if(data){
                        getUsers();
                        modal.modal('hide');
                    }
                    else{
                        alert("操作失败。")
                    }
                });
            }
        }
    });
    $('#user-form').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var type = button.data('type');
        var line = button.data('line');
        var modal = $(this)
        if(type == 1){
            modal.find('.modal-title').text('新建用户');
            modal.find('#username').val("");
            modal.find('#password').val("");
            modal.find('#address').val("");
            modal.find('#mark').val("");
            modal.find('#userType').val("");
            modal.data("userId", -1);
        }
        else{
            modal.find('.modal-title').text('编辑用户');
            modal.find('#username').val(users[line].username);
            modal.find('#password').val(users[line].password);
            modal.find('#address').val(users[line].address);
            modal.find('#mark').val(users[line].remark);
            modal.find('#userType').val(users[line].userType);
            modal.data("userId", users[line].userID);
        }
    });
});