<?php
/* @var $module_name string module name */
/* @var $controller_name string controller name */
/* @var $form_element_prefix string prefix of form element */
/* @var $controller_url string controller name in url format */
/* @var $primary_id string primary id */
/* @var $all_batch_id string check all checkbox id */
/* @var $batch_id string checkbox id */

?>

$(document).ready(function(){
<?php if ($primary_id !== ''){ ?>
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
<?php } ?>
});
<?php if ($primary_id !== ''){ ?>
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
    window.form<?php echo $controller_name; ?>.reset();
    CKEDITOR.instances.ck_<?php echo $form_element_prefix; ?>_intro.setData('');
    $('#btn_submit_<?php echo $form_element_prefix; ?>').attr('disabled', false);
    $('#modal<?php echo $controller_name; ?>').modal('show');
});

$('#form<?php echo $controller_name; ?>').on('submit', (function(event){
    event.preventDefault();

    var <?php echo $primary_id; ?> = $('#<?php echo $form_element_prefix; ?>_<?php echo $primary_id; ?>').val();
    var type = (<?php echo $primary_id; ?> == '') ? 'add' : 'modify';
    var error_num = validInput();
    if(error_num == 0) {
        $('#btn_submit_<?php echo $form_element_prefix; ?>').attr('disabled', true);
        var content = $.trim(CKEDITOR.instances.ck_<?php echo $form_element_prefix; ?>_intro.getData());
        $('#<?php echo $form_element_prefix; ?>_intro').val(content);

        var post_url = '/index.php<?php echo '/' . strtolower($module_name); ?>/<?php echo $controller_url; ?>/' + type + '-<?php echo $controller_url; ?>';
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
    var <?php echo $primary_id; ?> = $(this).attr('id').substr('modify_'.length);
    var post_url = '/index.php<?php echo '/' . strtolower($module_name); ?>/<?php echo $controller_url; ?>/get-<?php echo $controller_url; ?>';
    var post_data = {
        '<?php echo $primary_id; ?>' : <?php echo $primary_id, PHP_EOL; ?>
    };
    var method = 'get';
    var success_function = function(result){
        $('#<?php echo $form_element_prefix; ?>_name').val(result.<?php echo $form_element_prefix; ?>_name);
        $('#<?php echo $form_element_prefix; ?>_weight').val(result.<?php echo $form_element_prefix; ?>_weight);
        CKEDITOR.instances.ck_<?php echo $form_element_prefix; ?>_intro.setData(result.<?php echo $form_element_prefix; ?>_intro);
        $('#<?php echo $form_element_prefix; ?>_<?php echo $primary_id; ?>').val(result.<?php echo $primary_id; ?>);
        $('#btn_submit_<?php echo $form_element_prefix; ?>').attr('disabled', false);
        $('#modal<?php echo $controller_name; ?>').modal('show');
    };
    callAjaxWithFunction(post_url, post_data, success_function, method);
});

$('a[id^=delete_]').on('click', function(){
    if (confirm(MESSAGE_DELETE_CONFIRM)) {
        var <?php echo $primary_id; ?> = $(this).attr('id').substr('delete_'.length);
        var url = '/index.php<?php echo '/' . strtolower($module_name); ?>/<?php echo $controller_url; ?>/delete-<?php echo $controller_url; ?>';
        var data = {
            '<?php echo $primary_id; ?>' : <?php echo $primary_id, PHP_EOL; ?>
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
    var name = $.trim($('#<?php echo $form_element_prefix; ?>_name').val());
    var weight = $('#<?php echo $form_element_prefix; ?>_weight').val();
    var image = $('#<?php echo $form_element_prefix; ?>_image').val();
    var content = $.trim(CKEDITOR.instances.ck_<?php echo $form_element_prefix; ?>_intro.getData());
    if(name == '') {
        error_num = error_num + 1;
        alert(MESSAGE_NAME_ERROR);
    } else if(!isUnsignedInt(weight)) {
        error_num = error_num + 1;
        alert(MESSAGE_WEIGHT_FORMAT_ERROR);
    } else if(type == 'add' && image == '') {
        error_num = error_num + 1;
        alert(MESSAGE_UPLOAD_IMAGE_ERROR);
    } else if(content == '') {
        error_num = error_num + 1;
        alert(MESSAGE_CONTENT_ERROR);
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

<?php if($all_batch_id !== '' && $batch_id !== ''){ ?>
$('#<?php echo $all_batch_id; ?>').on('click', function(){
    batchMute(this, '<?php echo $batch_id; ?>');
});

$('input[name="<?php echo $batch_id; ?>"]').on('click', function(){
    closeBatch(this, '<?php echo $all_batch_id; ?>');
});
<?php } ?>

/*  --------------------------------------------------------------------------------------------------------  */
function search(current_page, page_length, keyword)
{
    var params = {
        'keyword': keyword || $.trim($('#keyword').val()),
        'current_page': current_page || js_data.current_page,
        'page_length': page_length || js_data.page_length
    };
    location.href = '/index.php<?php echo '/' . strtolower($module_name); ?>/<?php echo $controller_url; ?>/index?' + $.param(params);
}

$('#page_length').on('change', function(){
    search(1, this.value);
});
<?php } ?>