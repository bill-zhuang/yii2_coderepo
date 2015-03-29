
$(document).ready(function(){
    $('#payment_date').val(js_data.payment_date);
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
$('#payment_date').on('keydown', function(event){
    if (event.keyCode == 13) {
        //enter key
        event.preventDefault();
        $('#btn_search').click();
    }
});

$('#btn_search').on('click', function(){
    var payment_date = $.trim($('#payment_date').val());
    var current_page = js_data.current_page;
    var page_length = js_data.page_length;
    search(current_page, page_length, payment_date);
});

$('#btn_add').on('click', function(){
    window.formFinancePayment.reset();
    $('#finance_payment_payment_date').val(getCurrentDate());
    $('#btn_submit_finance_payment').attr('disabled', false);
    $('#modalFinancePayment').modal('show');
});

$('#formFinancePayment').on('submit', (function(event){
    event.preventDefault();

    var fp_id = $('#finance_payment_fp_id').val();
    var type = (fp_id == '') ? 'add' : 'modify';
    var error_num = validInput();
    if(error_num == 0) {
        $('#btn_submit_finance_payment').attr('disabled', true);

        var post_url = '/index.php/person/finance-payment/' + type + '-finance-payment';
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
    var fp_id = $(this).attr('id').substr('modify_'.length);
    var post_url = '/index.php/person/finance-payment/get-finance-payment';
    var post_data = {
        'fp_id' : fp_id
    };
    var method = 'get';
    var success_function = function(result){
        $('#finance_payment_payment_date').val(result.fp_payment_date);
        $('#finance_payment_payment').val(result.fp_payment);
        $('#finance_payment_fc_id').val(result.fc_id);
        $('#finance_payment_intro').val(result.fp_detail);
        $('#finance_payment_fp_id').val(result.fp_id);
        $('#btn_submit_finance_payment').attr('disabled', false);
        $('#modalFinancePayment').modal('show');
    };
    callAjaxWithFunction(post_url, post_data, success_function, method);
});

$('a[id^=delete_]').on('click', function(){
    if (confirm(MESSAGE_DELETE_CONFIRM)) {
        var fp_id = $(this).attr('id').substr('delete_'.length);
        var url = '/index.php/person/finance-payment/delete-finance-payment';
        var data = {
            'fp_id' : fp_id
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
    var payment_date = $.trim($('#finance_payment_payment_date').val());
    var payment = $.trim($('#finance_payment_payment').val());
    if(payment_date == '') {
        error_num = error_num + 1;
        alert(MESSAGE_DATE_ERROR);
    } else if(payment === '') {
        error_num = error_num + 1;
        alert(MESSAGE_MONEY_FORMAT_EMPTY_ERROR);
    }

    return error_num;
}

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
function search(current_page, page_length, payment_date)
{
    var params = {
        'payment_date': payment_date || $.trim($('#payment_date').val()),
        'current_page': current_page || js_data.current_page,
        'page_length': page_length || js_data.page_length
    };
    location.href = '/index.php/person/finance-payment/index?' + $.param(params);
}

$('#page_length').on('change', function(){
    search(1, this.value);
});
