<?php
/* @var $module_name string module name */
/* @var $controller_name string controller name */
/* @var $form_element_prefix string prefix of form element */
/* @var $controller_url string controller name in url format */
/* @var $primary_id string primary id */
/* @var $all_batch_id string check all checkbox id */
/* @var $batch_id string checkbox id */
/* @var $table_data array table fields and default value */
/* @var $is_ckeditor bool use ckeditor or not */

$table_keys = array_keys($table_data);
?>

$(document).ready(function(){
<?php if ($primary_id !== ''){ ?>
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
<?php } ?>
});
<?php if ($primary_id !== ''){ ?>
$('#btn_add').on('click', function(){
    window.form<?php echo $controller_name; ?>.reset();
<?php if ($is_ckeditor){ ?>
    CKEDITOR.instances.ck_<?php echo $form_element_prefix; ?>_intro.setData('');
<?php } ?>
    $('#btn_submit_<?php echo $form_element_prefix; ?>').attr('disabled', false);
    $('#modal<?php echo $controller_name; ?>').modal('show');
});

$('#form<?php echo $controller_name; ?>').on('submit', (function(event){
    event.preventDefault();

    var <?php echo $primary_id; ?> = $('#<?php echo $form_element_prefix; ?>_<?php echo $primary_id; ?>').val();
    var type = (<?php echo $primary_id; ?> == '') ? 'add' : 'modify';
    var error_num = validInput(type);
    if(error_num == 0) {
        $('#btn_submit_<?php echo $form_element_prefix; ?>').attr('disabled', true);
<?php if ($is_ckeditor){ ?>
        var content = $.trim(CKEDITOR.instances.ck_<?php echo $form_element_prefix; ?>_intro.getData());
        $('#<?php echo $form_element_prefix; ?>_intro').val(content);
<?php } ?>

        var post_url = '/index.php<?php echo ($module_name === '') ? '' : '/' . strtolower($module_name); ?>/<?php echo $controller_url; ?>/' + type + '-<?php echo $controller_url; ?>';
        var post_data = new FormData(this);
        var msg_success = (<?php echo $primary_id; ?> == '') ? MESSAGE_ADD_SUCCESS : MESSAGE_MODIFY_SUCCESS;
        var msg_error = (<?php echo $primary_id; ?> == '') ? MESSAGE_ADD_ERROR : MESSAGE_MODIFY_ERROR;
        var method = 'post';
        callAjaxWithForm(post_url, post_data, msg_success, msg_error, method);
    }
}));

$('a[id^=modify_]').on('click', function(){
    var <?php echo $primary_id; ?> = $(this).attr('id').substr('modify_'.length);
    var post_url = '/index.php<?php echo ($module_name === '') ? '' : '/' . strtolower($module_name); ?>/<?php echo $controller_url; ?>/get-<?php echo $controller_url; ?>';
    var post_data = {
        '<?php echo $primary_id; ?>' : <?php echo $primary_id, PHP_EOL; ?>
    };
    var method = 'get';
    var success_function = function(result){
<?php foreach ($table_data as $key => $default_value)
    {
        echo str_repeat(' ', 4 * 2) . "$('#" . $form_element_prefix . '_' . $key . "').val(result." . $key . ");" . PHP_EOL;
    }
?>
<?php if ($is_ckeditor){ ?>
        CKEDITOR.instances.ck_<?php echo $form_element_prefix; ?>_intro.setData(result.{table_prefix}_intro);
<?php } ?>
        $('#btn_submit_<?php echo $form_element_prefix; ?>').attr('disabled', false);
        $('#modal<?php echo $controller_name; ?>').modal('show');
    };
    callAjaxWithFunction(post_url, post_data, success_function, method);
});

$('a[id^=delete_]').on('click', function(){
    if (confirm(MESSAGE_DELETE_CONFIRM)) {
        var <?php echo $primary_id; ?> = $(this).attr('id').substr('delete_'.length);
        var url = '/index.php<?php echo ($module_name === '') ? '' : '/' . strtolower($module_name); ?>/<?php echo $controller_url; ?>/delete-<?php echo $controller_url; ?>';
        var data = {
            '<?php echo $primary_id; ?>' : <?php echo $primary_id, PHP_EOL; ?>
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
<?php foreach ($table_data as $key => $default_value)
{
    if ($key != $primary_id)
    {
        if(strpos(implode('', $table_keys), 'img') !== false || strpos(implode('', $table_keys), 'image') !== false){
            echo str_repeat(' ', 4 * 1) . 'var image = $(\'#' . $form_element_prefix . '_image\').val();' . PHP_EOL;
        } else {
            echo str_repeat(' ', 4 * 1) . 'var '. $key . ' = $(\'#' . $form_element_prefix . '_' . $key . '\').val();' . PHP_EOL;
        }
    }
}
?>
<?php if ($is_ckeditor){ ?>
    var content = $.trim(CKEDITOR.instances.ck_<?php echo $form_element_prefix; ?>_intro.getData());
<?php } ?>
<?php
$table_keys_no_pkid = [];
foreach ($table_keys as $table_key)
{
    if ($table_key != $primary_id)
    {
        $table_keys_no_pkid[] = $table_key;
    }
}
foreach ($table_data as $key => $default_value)
{
    if ($key != $primary_id)
    {
        if(strpos(implode('', $table_keys), 'img') !== false || strpos(implode('', $table_keys), 'image') !== false){
            echo (array_search($key, $table_keys_no_pkid) === 0 ? str_repeat(' ', 4 * 1) : 'else ') . 'if (type == \'add\' && image == \'\') {' . PHP_EOL;
            echo str_repeat(' ', 4 * 2) . 'error_num = error_num + 1;' . PHP_EOL;
            echo str_repeat(' ', 4 * 2) . 'alert(MESSAGE_UPLOAD_IMAGE_ERROR)' . PHP_EOL;
            echo str_repeat(' ', 4 * 1) . '} ';
        } else {
            echo (array_search($key, $table_keys_no_pkid) === 0 ? str_repeat(' ', 4 * 1) : 'else ') . 'if (' . $key . ' == \'\') {' . PHP_EOL;
            echo str_repeat(' ', 4 * 2) . 'error_num = error_num + 1;' . PHP_EOL;
            echo str_repeat(' ', 4 * 2) . 'alert(\'todo set alert message\')' . PHP_EOL;
            echo str_repeat(' ', 4 * 1) . '} ';
        }
    }
}
?>
<?php if ($is_ckeditor){ ?>else if(content == '') {
    error_num = error_num + 1;
    alert(MESSAGE_CONTENT_ERROR);
    }<?php } ?>

    return error_num;
}

/*  --------------------------------------------------------------------------------------------------------  */
<?php if($all_batch_id !== '' && $batch_id !== ''){ ?>
$('#<?php echo $all_batch_id; ?>').on('click', function(){
    batchMute(this, '<?php echo $batch_id; ?>');
});

$('input[name="<?php echo $batch_id; ?>"]').on('click', function(){
    closeBatch(this, '<?php echo $all_batch_id; ?>');
});
<?php } ?>

/*  --------------------------------------------------------------------------------------------------------  */
$('#page_length').on('change', function(){
    $('#current_page').val(1);
    $('#page_length').val(this.value);
    $('#formSearch')[0].submit();
});
<?php } ?>