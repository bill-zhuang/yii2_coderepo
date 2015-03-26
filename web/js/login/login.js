
$(document).ready(function(){
    if (content !== '') {
        alert(content);
    }
});

$('#login').on('submit', function(event){
    event.preventDefault();
    loginCheck();
});

$('#login').on('keydown', function(event){
    if(event.keyCode == 13) {
        event.preventDefault();
        loginCheck();
    }
});

function loginCheck()
{
    var name = $.trim($('#username').val());
    var password = $.trim($('#password').val());

    if (name == '') {
        alert('用户名不能为空！');
        return false;
    } else if (password == '') {
        alert('密码不能为空！');
        return false;
    } else {
        $('#login').get(0).submit();
    }
}
