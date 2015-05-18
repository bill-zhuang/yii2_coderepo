
$(document).ready(function(){
    $('#keyword').val(js_data.keyword);
    $('#page_length').val(js_data.page_length);
    $('#current_page').val(js_data.current_page);
    $('#pagination').twbsPagination({
        totalPages: js_data.total_pages,
        startPage: js_data.current_page,
        visiblePages: 7,
        first: '首页',
        prev: '上一页',
        next: '下一页',
        last: '尾页',
        onPageClick: function (event, page) {
            $('#current_page').val(page);
            $('#formSearch')[0].submit();
        }
    });
});
$('#btn_add').on('click', function(){
    window.formBackendUser.reset();
    $('#btn_submit_backend_user').attr('disabled', false);
    $('#modalBackendUser').modal('show');
});

$('#formBackendUser').on('submit', (function(event){
    event.preventDefault();

    var bu_id = $('#backend_user_bu_id').val();
    var type = (bu_id == '') ? 'add' : 'modify';
    var error_num = validInput(type);
    if(error_num == 0) {
        $('#btn_submit_backend_user').attr('disabled', true);

        var post_url = '/index.php/backend-user/' + type + '-backend-user';
        var post_data = new FormData(this);
        var msg_success = (bu_id == '') ? MESSAGE_ADD_SUCCESS : MESSAGE_MODIFY_SUCCESS;
        var msg_error = (bu_id == '') ? MESSAGE_ADD_ERROR : MESSAGE_MODIFY_ERROR;
        var method = 'post';
        callAjaxWithForm(post_url, post_data, msg_success, msg_error, method);
    }
}));

$('a[id^=reset_password_]').on('click', function(){
    if (confirm(MESSAGE_RESET_PASSWROD_CONFIRM)) {
        var bu_id = $(this).attr('id').substr('reset_password_'.length);
        var url = '/index.php/backend-user/reset-password';
        var data = {
            'bu_id' : bu_id
        };
        var msg_success = MESSAGE_RESET_PASSWORD_SUCCESS;
        var msg_error = MESSAGE_RESET_PASSWORD_ERROR;
        var method = 'post';
        callAjaxWithAlert(url, data, msg_success, msg_error, method);
    }
});

$('a[id^=delete_]').on('click', function(){
    if (confirm(MESSAGE_DELETE_CONFIRM)) {
        var bu_id = $(this).attr('id').substr('delete_'.length);
        var url = '/index.php/backend-user/delete-backend-user';
        var data = {
            'bu_id' : bu_id
        };
        var msg_success = MESSAGE_DELETE_SUCCESS;
        var msg_error = MESSAGE_DELETE_ERROR;
        var method = 'post';
        callAjaxWithAlert(url, data, msg_success, msg_error, method);
    }
});

function validInput(type)
{
    var error_num = 0;
    var bu_name = $('#backend_user_bu_name').val();
    if (type == 'add' && bu_name == '') {
        error_num = error_num + 1;
        alert(MESSAGE_USER_NAME_EMPTY)
    }
    return error_num;
}

/*  --------------------------------------------------------------------------------------------------------  */
$('#page_length').on('change', function(){
    $('#current_page').val(1);
    $('#page_length').val(this.value);
    $('#formSearch')[0].submit();
});
