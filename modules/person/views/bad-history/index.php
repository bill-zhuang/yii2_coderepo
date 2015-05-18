<?php
use app\assets\person\AppAssetBadHistory;
AppAssetBadHistory::register($this);
Yii::$app->view->registerJs('var js_data = ' . json_encode($js_data) . ';', \yii\web\View::POS_END);
?>

<div class="panel panel-warning">
    <!-- panel heading -->
    <div class="panel-heading">
        <h2>Bad History</h2>
    </div>
    <!-- panel body -->
    <div class="panel-body">
        <div class="row">
            <form id="formSearch" action="/index.php/person/bad-history/index" method="get" class="form-inline">
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
        </div><hr>
    </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <table class="table table-striped table-bordered bill_table text-center">
                    <tr><td>序号</td>
                        <td>日期</td>
                        <td>次数</td>
                        <td>创建时间</td>
                        <td>更新时间</td>
                        <td>操作</td>
                    </tr>
                    <tbody>
                    <?php for($i = 0, $len = count($data); $i < $len; $i++){ ?>
                        <tr><td><?php echo ($js_data['start'] + $i + 1); ?></td>
                            <td><?php echo $data[$i]['bh_happen_date']; ?></td>
                            <td><?php echo $data[$i]['bh_count']; ?></td>
                            <td><?php echo $data[$i]['bh_create_time']; ?></td>
                            <td><?php echo $data[$i]['bh_update_time']; ?></td>
                            <td>
                                <a href="#" id="<?php echo 'modify_' . $data[$i]['bh_id']; ?>">修改</a>
                                <a href="#" id="<?php echo 'delete_' . $data[$i]['bh_id']; ?>">删除</a>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if (count($data) == 0) { ?>
                        <tr>
                            <td colspan="6" class="bill_table_no_data">对不起,没有符合条件的数据</td>
                        </tr>
                    <?php } ?>
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
</div>
<!-- modal -->
<div id="modalBadHistory" class="modal fade">
    <div class="modal-dialog bill_modal_sm" >
        <div class="modal-content">
            <div class="modal-header">
                <span>新增/修改</span>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <form id="formBadHistory" action="#" method="post" enctype="multipart/form-data" class="form-inline">
                <div class="modal-body">
                    <div class="input-group">
                        <span class="input-group-addon">日期：</span>
                        <input type="text" name="bad_history_date" id="bad_history_date" class="form-control form_date"/>
                    </div><br /><br />
                    <div class="input-group">
                        <span class="input-group-addon">次数：</span>
                        <input type="text" name="bad_history_count" id="bad_history_count" class="form-control"/>
                    </div><br /><br />
                    <input type="hidden" id="bad_history_bh_id" name="bad_history_bh_id"/>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="btn_submit_bad_history">提交</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">关闭</button>
                </div>
            </form>
        </div>
    </div>
</div>
