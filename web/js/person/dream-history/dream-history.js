
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
    window.formDreamHistory.reset();
    $('#dream_history_date').val(getCurrentDate());
    $('#dream_history_count').val(1);
    $('#btn_submit_dream_history').attr('disabled', false);
    $('#modalDreamHistory').modal('show');
});

$('#formDreamHistory').on('submit', (function(event){
    event.preventDefault();

    var dh_id = $('#dream_history_dh_id').val();
    var type = (dh_id == '') ? 'add' : 'modify';
    $('#btn_submit_dream_history').attr('disabled', true);

    var post_url = '/index.php/person/dream-history/' + type + '-dream-history';
    var post_data = new FormData(this);
    var msg_success = MESSAGE_ADD_SUCCESS;
    var msg_error = MESSAGE_ADD_ERROR;
    if (type == 'modify') {
        msg_success = MESSAGE_MODIFY_SUCCESS;
        msg_error = MESSAGE_MODIFY_ERROR;
    }
    var method = 'post';
    callAjaxWithForm(post_url, post_data, msg_success, msg_error, method);
}));

$('a[id^=modify_]').on('click', function(){
    var dh_id = $(this).attr('id').substr('modify_'.length);
    var post_url = '/index.php/person/dream-history/get-dream-history';
    var post_data = {
        'dh_id' : dh_id
    };
    var method = 'get';
    var success_function = function(result){
        $('#dream_history_date').val(result.dh_happen_date);
        $('#dream_history_count').val(result.dh_count);
        $('#dream_history_dh_id').val(result.dh_id);
        $('#btn_submit_dream_history').attr('disabled', false);
        $('#modalDreamHistory').modal('show');
    };
    callAjaxWithFunction(post_url, post_data, success_function, method);
});

$('a[id^=delete_]').on('click', function(){
    if (confirm(MESSAGE_DELETE_CONFIRM)) {
        var dh_id = $(this).attr('id').substr('delete_'.length);
        var url = '/index.php/person/dream-history/delete-dream-history';
        var data = {
            'dh_id' : dh_id
        };
        var msg_success = MESSAGE_DELETE_SUCCESS;
        var msg_error = MESSAGE_DELETE_ERROR;
        var method = 'post';
        callAjaxWithAlert(url, data, msg_success, msg_error, method);
    }
});

/*  --------------------------------------------------------------------------------------------------------  */
$('.form_date').datetimepicker({
    format: 'yyyy-mm-dd',
    todayBtn:  'linked',
    todayHighlight: 1,
    language: 'zh-CN',
    autoclose: 1,
    minView: 2 //needed, or show time
});

/*  --------------------------------------------------------------------------------------------------------  */
function search(current_page, page_length, keyword)
{
    var params = {
        'keyword': keyword || $.trim($('#keyword').val()),
        'current_page': current_page || js_data.current_page,
        'page_length': page_length || js_data.page_length
    };
    location.href = '/index.php/person/dream-history/index?' + $.param(params);
}

$('#page_length').on('change', function(){
    search(1, this.value);
});
