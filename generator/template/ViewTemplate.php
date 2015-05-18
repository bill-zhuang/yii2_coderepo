<?php
/* @var $module_name string module name */
/* @var $controller_name string controller name */
/* @var $asset_name string asset name */
/* @var $page_title_name string view page title name */
/* @var $all_batch_id string check all checkbox id */
/* @var $batch_id string checkbox id */
/* @var $table_row_data array html table, key is name, value is data key */
/* @var $primary_id string primary key name */
/* @var $table_data array table fields and default value */
/* @var $form_element_prefix string prefix of form element */
/* @var $controller_url string controller name in url format */
/* @var $view_modal_size string modal size */
/* @var $is_blacklist bool use blacklist or not */
/* @var $is_ckeditor bool use ckeditor or not */

$table_keys = array_keys($table_data);
echo "<?php\n";
echo 'use app\assets' . (($module_name === '') ? '' : ('\\' . strtolower($module_name))) . '\\AppAsset' . $asset_name . ';' . PHP_EOL;

echo  'AppAsset' . $asset_name . '::register($this);' . PHP_EOL;
echo 'Yii::$app->view->registerJs(\'var js_data = \' . json_encode($js_data) . \';\', \\yii\\web\\View::POS_END);' . PHP_EOL;

echo "?>\n";
?>

<div class="panel panel-warning">
    <!-- panel heading -->
    <div class="panel-heading">
        <h2><?php echo $page_title_name; ?></h2>
    </div>
    <!-- panel body -->
    <div class="panel-body">
        <div class="row">
<?php if ($primary_id !== ''){ ?>
            <form action="/index.php<?php echo ($module_name === '') ? '' : '/' . strtolower($module_name); ?>/<?php echo $controller_url; ?>/index" method="get" id="formSearch" class="form-inline">
                <div class="col-sm-10 col-md-10 col-lg-10">
                    关键字:
                    <input type="text" class="form-control" id="keyword" name="keyword"/>
                    <button class="btn btn-primary" type="submit" id="btn_search">
                        <span class="glyphicon glyphicon-search"></span>
                        <span>搜索</span>
                    </button>&nbsp;&nbsp;&nbsp;&nbsp;
                    <button class="btn btn-success" type="button" id="btn_add">
                        <span class="glyphicon glyphicon-plus"></span>
                        <span>新增</span>
                    </button>&nbsp;&nbsp;&nbsp;&nbsp;
<?php if ($is_blacklist){ ?>
                        <button class="btn btn-warning" type="button" id="btn_blacklist">
                            <span class="glyphicon glyphicon-warning-sign"></span>
                            <span>黑名单</span>
                        </button>&nbsp;&nbsp;&nbsp;&nbsp;
<?php } ?>
<?php if($all_batch_id !== ''){ ?>
                        <button class="btn btn-danger" type="button" id="btn_batch_delete">
                            <span class="glyphicon glyphicon-trash"></span>
                            <span>批量删除</span>
                        </button>&nbsp;&nbsp;&nbsp;&nbsp;
<?php } ?>
                </div>
                <div class="col-sm-2 col-md-2 col-lg-2 text-right">
                    <select name="page_length" id="page_length" class="form-control">
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="75">75</option>
                        <option value="100">100</option>
                    </select>
                    &nbsp;<label>每页</label>
                    <input type="hidden" id="current_page" name="current_page"/>
                </div>
            </form>
<?php } ?>
        </div><hr>
<?php if($primary_id !== ''){ ?>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <table class="table table-striped table-bordered bill_table text-center">
                    <tr><?php if($all_batch_id !== ''){ echo PHP_EOL; ?>
                        <td><input type="checkbox" id="<?php echo $all_batch_id; ?>" name="<?php echo $all_batch_id; ?>"/></td>
                        <?php } ?>
                        <?php
                            echo PHP_EOL . str_repeat(' ', 4 * 6) . '<td>序号</td>' . PHP_EOL;
                            foreach ($table_row_data as $key => $value)
                            {
                                echo str_repeat(' ', 4 * 6) . '<td>' . $key . '</td>' . PHP_EOL;
                            }
                        ?>
                        <td>操作</td>
                    </tr>
                    <tbody>
                    <?php echo '<?php for($i = 0, $len = count($data); $i < $len; $i++){ ?>' . PHP_EOL; ?>
                        <tr><?php if($batch_id !== ''){ echo PHP_EOL; ?>
                            <td><input type="checkbox" value="<?php echo '<?php echo $data[$i][\'' . $primary_id . '\']; ?>'; ?>" name="<?php echo $batch_id; ?>"/></td>
                            <?php } ?>
                            <?php
                                echo PHP_EOL . str_repeat(' ', 4 * 7) . '<td><?php echo ($js_data[\'start\'] + $i + 1); ?></td>' . PHP_EOL;
                                foreach ($table_row_data as $value)
                                {
                                    echo str_repeat(' ', 4 * 7) . '<td><?php echo $data[$i][\'' . $value . '\']; ?></td>' . PHP_EOL;
                                }
                            ?>
                            <td>
                                <a href="#" id="<?php echo '<?php echo \'modify_\' . ' . '$data[$i][\'' . $primary_id . '\']; ?>'; ?>">修改</a>
                                <a href="#" id="<?php echo '<?php echo \'delete_\' . ' . '$data[$i][\'' . $primary_id . '\']; ?>'; ?>">删除</a>
                            </td>
                        </tr>
                    <?php echo '<?php } ?>'; ?>

                    <?php echo '<?php if (count($data) == 0) { ?>' . PHP_EOL; ?>
                        <tr>
                            <td colspan="<?php echo (2 + ($all_batch_id ==='' ? 0 : 1) + ($batch_id === '' ? 0 : 1) + count($table_row_data)); ?>" class="bill_table_no_data">对不起,没有符合条件的数据</td>
                        </tr>
                    <?php echo '<?php } ?>' . PHP_EOL; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- panel footer -->
    <div class="panel-footer">
        <div class="row">
            <div class="col-sm-6 col-md-6 col-lg-6 text-left">
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 text-right">
                <ul id="pagination" class="pagination-md"></ul>
            </div>
        </div>
    </div>
<?php } ?>
</div>
<?php if ($primary_id !== ''){ ?>
<!-- modal -->
<div id="modal<?php echo $controller_name; ?>" class="modal fade">
    <div class="modal-dialog bill_modal_<?php echo $view_modal_size; ?>" >
        <div class="modal-content">
            <div class="modal-header">
                <span>新增/修改</span>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <form id="form<?php echo $controller_name; ?>" action="#" method="post" enctype="multipart/form-data" class="form-inline">
                <div class="modal-body">
<?php foreach ($table_data as $key => $default_value)
{
    if ($key != $primary_id)
    {
        echo str_repeat(' ', 4 * 5) . '<div class="input-group">' . PHP_EOL;
        echo str_repeat(' ', 4 * 6) . '<span class="input-group-addon">' . $key . '：</span>' . PHP_EOL;
        echo str_repeat(' ', 4 * 6) . '<input type="text" name="' . $form_element_prefix . '_' . $key . '" id="' . $form_element_prefix. '_'  . $key . '" class="form-control"/>' . PHP_EOL;
        echo str_repeat(' ', 4 * 5) . '</div><br /><br />' . PHP_EOL;
    }
}
?>
<?php if(strpos(implode('', $table_keys), 'img') !== false || strpos(implode('', $table_keys), 'image') !== false){ ?>
                        图片：
                        <input type="file" name="<?php echo $form_element_prefix; ?>_image" id="<?php echo $form_element_prefix; ?>_image" accept="image/*"/><br /><br />
<?php } ?>
<?php if ($is_ckeditor){ ?>
                        简介：
                        <textarea class="ckeditor" id="ck_<?php echo $form_element_prefix; ?>_intro"></textarea>
                        <input type="hidden" id="<?php echo $form_element_prefix; ?>_intro" name="<?php echo $form_element_prefix; ?>_intro"/>
<?php } ?>
                    <input type="hidden" id="<?php echo $form_element_prefix; ?>_<?php echo $primary_id; ?>" name="<?php echo $form_element_prefix; ?>_<?php echo $primary_id; ?>"/>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="btn_submit_<?php echo $form_element_prefix; ?>">提交</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">关闭</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php } ?>