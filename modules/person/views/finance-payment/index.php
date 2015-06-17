<?php
use app\assets\person\AppAssetFinancePayment;
AppAssetFinancePayment::register($this);
Yii::$app->view->registerJs('var js_data = ' . json_encode($js_data) . ';', \yii\web\View::POS_END);
?>

<div class="panel panel-warning">
    <!-- panel heading -->
    <div class="panel-heading">
        <h2>Finance Payment</h2>
    </div>
    <!-- panel body -->
    <div class="panel-body">
        <div class="row">
            <form id="formSearch" action="/index.php/person/finance-payment/index" method="get" class="form-inline">
                <div class="col-sm-10 col-md-10 col-lg-10">
                    日期: <input type="text" class="form-control form_date" id="payment_date" name="payment_date"/>
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
                        <td>消费金额</td>
                        <td>分类</td>
                        <td>备注</td>
                        <td>更新时间</td>
                        <td>操作</td>
                    </tr>
                    <tbody>
                    <?php for($i = 0, $len = count($data); $i < $len; $i++){ ?>
                        <tr><td><?php echo ($js_data['start'] + $i + 1); ?></td>
                            <td><?php echo $data[$i]['fp_payment_date']; ?></td>
                            <td><?php echo $data[$i]['fp_payment']; ?></td>
                            <td><?php echo $data[$i]['category']; ?></td>
                            <td><?php echo $data[$i]['fp_detail']; ?></td>
                            <td><?php echo $data[$i]['fp_update_time']; ?></td>
                            <td>
                                <a href="#" id="<?php echo 'modify_' . $data[$i]['fp_id']; ?>">修改</a>
                                <a href="#" id="<?php echo 'delete_' . $data[$i]['fp_id']; ?>">删除</a>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if (count($data) == 0) { ?>
                        <tr>
                            <td colspan="7" class="bill_table_no_data">对不起,没有符合条件的数据</td>
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
<div id="modalFinancePayment" class="modal fade">
    <div class="modal-dialog bill_modal_md" >
        <div class="modal-content">
            <div class="modal-header">
                <span>新增/修改</span>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <form id="formFinancePayment" action="#" method="post" enctype="multipart/form-data" class="form-inline">
                <div class="modal-body">
                    <div class="input-group">
                        <span class="input-group-addon">日期：</span>
                        <input type="text"
                               name="finance_payment_payment_date"
                               id="finance_payment_payment_date"
                               class="form-control form_date"
                            />
                    </div><br /><br />
                    <div class="input-group">
                        <span class="input-group-addon">金额：</span>
                        <input type="text"
                               name="finance_payment_payment"
                               id="finance_payment_payment"
                               class="form-control"
                               placeholder="多个金额用英文逗号隔开"
                            />
                    </div><br /><br />
                    <div class="input-group">
                        <span class="input-group-addon">分类：</span>
                        <select name="finance_payment_fc_id[]" id="finance_payment_fc_id" class="form-control" multiple="multiple">
                            <?php foreach($parent_categories as $key => $value){ ?>
                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                            <?php } ?>
                        </select>
                    </div><br /><br />
                    <div class="input-group">
                        <span class="input-group-addon">备注：</span>
                        <textarea id="finance_payment_intro" name="finance_payment_intro" class="bill_textarea_full"></textarea>
                    </div><br />

                    <input type="hidden" id="finance_payment_fp_id" name="finance_payment_fp_id"/>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="btn_submit_finance_payment">提交</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">关闭</button>
                </div>
            </form>
        </div>
    </div>
</div>
