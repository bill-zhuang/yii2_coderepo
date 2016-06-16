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
/* @var $tab_types array tab types for select */
/* @var $default_tab_value mixed default selected tab value */

$table_keys = array_keys($table_data);
echo "<?php\n";
echo 'use app\assets' . (($module_name === '') ? '' : ('\\' . strtolower($module_name))) . '\\AppAsset' . $asset_name . ';' . PHP_EOL;

echo  'AppAsset' . $asset_name . '::register($this);' . PHP_EOL;

echo "?>\n";
?>

<title>Bill Coderepo - <?php echo $page_title_name; ?></title>
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
<?php if(!empty($tab_types)){ ?>
                    <input type="hidden" id="tab_type" name="tab_type"/>
<?php } ?>
                </div>
            </form>
<?php } ?>
        </div>
        <hr>
<?php if($primary_id !== ''){ ?>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
<?php if(!empty($tab_types)){ ?>
            <nav class="navbar nav-tabs" role="navigation">
                <div>
                    <ul class="nav nav-tabs" id="ul_tab_type">
<?php foreach ($tab_types as $key => $value) { ?>
                        <li id="li_tab_type_<?php echo '' . $key; ?>" <?php echo ($key == $default_tab_value) ? 'class="active"' : ''; ?>><a href="#"><?php echo $value; ?></a></li><?php echo PHP_EOL; ?>
<?php } ?>
                    </ul>
                </div>
            </nav>
<?php } ?>
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
            <div id="div_pagination" class="col-sm-6 col-md-6 col-lg-6 text-right">
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
    if ($key != $primary_id && $key != 'status' && $key != 'create_time' && $key != 'update_time')
    {
        echo str_repeat(' ', 4 * 5) . '<div class="input-group">' . PHP_EOL;
        echo str_repeat(' ', 4 * 6) . '<span class="input-group-addon">' . $key . '：</span>' . PHP_EOL;
        echo str_repeat(' ', 4 * 6) . '<input type="text" name="' . $form_element_prefix . '_' . $key . '" id="' . $form_element_prefix. '_'  . $key . '" class="form-control"/>' . PHP_EOL;
        echo str_repeat(' ', 4 * 5) . '</div>' . PHP_EOL;
        echo str_repeat(' ', 4 * 5) . '<br /><br />' . PHP_EOL;
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