
$(document).ready(function(){
    $('#payment_date').val(js_data.payment_date);
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

    $('#finance_payment_fc_id').selectpicker();
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
        $('#finance_payment_fc_id').selectpicker('val', result.fc_ids);
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
$('#page_length').on('change', function(){
    $('#current_page').val(1);
    $('#page_length').val(this.value);
    $('#formSearch')[0].submit();
});

$('#btn_search').on('click', function(){
    $('#current_page').val(1);
    $('#formSearch')[0].submit();
});
