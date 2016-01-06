$(function ()
{

    //add user
   	$('#addNewUser').click(function(){
   		var	username = $('#newUsername').val();
   		var	password = $('#newPassword').val();
   		var	password_re = $('#password_re').val();
   		var	type = $('#type').val();
   		var	address = $('#newAddress').val();
   		var	remark = $('#remark').val();
   		
   		if( password != password_re){
   			$('#passwordtips').html('两次密码不一致');
   		}
   		else{
   			$('#passwordtips').html('');
   			$.post(
   				'au',
   				{
   					username:username,
   					password:password,
   					type:type,
   					address:address,
   					remark:remark
   				},
   				function(data){
                  
   					var html = '';
   					var type1 ='';
   					var type2 ='';

   					if (type == 1) type1 = 'selected';
   					if (type == 2) type2 = 'selected';

   					html += '<tr userid="'+data+'">';
   					html += '<td><input class="username" type="text" value="'+username+'"></td>';
   					html += '<td><input class="password" type="password" value="'+password+'"></td>';
   					html += '<td><select class="type">';
   					html += '<option value="2"'+type2+' >店铺管理员</option>';
   					html += '<option value="1"'+type1+' >超级管理员</option>';
   					html += '</select></td>';
   					html += '<td><input class="address" type="text" value="'+address+'"></td>';
   					html += '<td><input class="remark" type="text" value="'+remark+'"></td>';
   					html += '<td>';
   					html += '<a href="" class="editUser">编辑</a>';
   					html += '<a href="" class="delUser">删除</a>';
   					html += '</td>';
   					html += '</tr>';

   					$('#userlist').append(html);
   				}
   			);
   		}
   	});

   	$('.editUser').click(function(){
   		var userID = $(this).parents('tr').attr('userid');
   		var username = $(this).parents('tr').children('td').children('.username').val();
   		var password = $(this).parents('tr').children('td').children('.password').val();
   		var type = $(this).parents('tr').children('td').children('.type').val();
   		var address = $(this).parents('tr').children('td').children('.address').val();
   		var remark = $(this).parents('tr').children('td').children('.remark').val();

   		$.post(
   			'eu',
   			{
   				userID:userID,
   				username:username,
   				password:password,
   				userType:type,
   				address:address,
   				remark:remark
   			},
   			function(data){
   				if(data == 'success') alert('修改成功');
   				else alert('修改失败');
   			});
   	});

   	$(document).delegate('.delUser','click',function(){
   		var del=confirm('确认删除?');
   		if(del){
	   		var userID = $(this).parents('tr').attr('userid');
	       	$(this).parents('tr').remove();
	         $.post(
	            "du",
	            {userID:userID},
	            function(data){
	                alert(data);
	            }
	        );
   		}       
    });

});