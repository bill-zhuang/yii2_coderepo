
$(document).ready(function(){
    $('#keyword').val(js_data.keyword);
    $('#page_length').val(js_data.page_length);
    $('#pagination').twbsPagination({
        totalPages: js_data.total_pages,
        startPage: js_data.current_page,
        visiblePages: 7,
        first: '首页',
        prev: '上一页',
        next: '下一页',
        last: '尾页',
        onPageClick: function (event, page) {
            search(page);
        }
    });
});

$('#keyword').on('keydown', function(event){
    if (event.keyCode == 13) {
        //enter key
        event.preventDefault();
        $('#btn_search').click();
    }
});

$('#btn_search').on('click', function(){
    var keyword = $.trim($('#keyword').val());
    var current_page = js_data.current_page;
    var page_length = js_data.page_length;
    search(current_page, page_length, keyword);
});

$('#btn_add').on('click', function(){
    window.formFinanceCategory.reset();
    $('#btn_submit_finance_category').attr('disabled', false);
    $('#modalFinanceCategory').modal('show');
});

$('#formFinanceCategory').on('submit', (function(event){
    event.preventDefault();

    var fc_id = $('#finance_category_fc_id').val();
    var type = (fc_id == '') ? 'add' : 'modify';
    var error_num = validInput();
    if(error_num == 0) {
        $('#btn_submit_finance_category').attr('disabled', true);

        var post_url = '/index.php/person/finance-category/' + type + '-finance-category';
        var post_data = new FormData(this);
        var msg_success = MESSAGE_ADD_SUCCESS;
        var msg_error = MESSAGE_ADD_ERROR;
        if (type == 'modify') {
            msg_success = MESSAGE_MODIFY_SUCCESS;
            msg_error = MESSAGE_MODIFY_ERROR;
        }
        var method = 'post';
        callAjaxWithForm(post_url, post_data, msg_success, msg_error, method);
    }
}));

$('a[id^=modify_]').on('click', function(){
    var fc_id = $(this).attr('id').substr('modify_'.length);
    var post_url = '/index.php/person/finance-category/get-finance-category';
    var post_data = {
        'fc_id' : fc_id
    };
    var method = 'get';
    var success_function = function(result){
        $('#finance_category_name').val(result.fc_name);
        $('#finance_category_weight').val(result.fc_weight);
        $('#finance_category_fc_id').val(result.fc_id);
        $('#btn_submit_finance_category').attr('disabled', false);
        $('#modalFinanceCategory').modal('show');
    };
    callAjaxWithFunction(post_url, post_data, success_function, method);
});

$('a[id^=delete_]').on('click', function(){
    if (confirm(MESSAGE_DELETE_CONFIRM)) {
        var fc_id = $(this).attr('id').substr('delete_'.length);
        var url = '/index.php/person/finance-category/delete-finance-category';
        var data = {
            'fc_id' : fc_id
        };
        var msg_success = MESSAGE_DELETE_SUCCESS;
        var msg_error = MESSAGE_DELETE_ERROR;
        var method = 'post';
        callAjaxWithAlert(url, data, msg_success, msg_error, method);
    }
});

function validInput()
{
    var error_num = 0;
    var name = $.trim($('#finance_category_name').val());
    var weight = $.trim($('#finance_category_weight').val());
    if(name == '') {
        error_num = error_num + 1;
        alert(MESSAGE_NAME_ERROR);
    } else if (!isUnsignedInt(weight)) {
        error_num = error_num + 1;
        alert(MESSAGE_WEIGHT_FORMAT_ERROR);
    }

    return error_num;
}

/*  --------------------------------------------------------------------------------------------------------  */
function search(current_page, page_length, keyword)
{
    var params = {
        'keyword': keyword || $.trim($('#keyword').val()),
        'current_page': current_page || js_data.current_page,
        'page_length': page_length || js_data.page_length
    };
    location.href = '/index.php/person/finance-category/index?' + $.param(params);
}

$('#page_length').on('change', function(){
    search(1, this.value);
});
