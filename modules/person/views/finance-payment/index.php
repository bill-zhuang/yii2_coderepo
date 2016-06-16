<?php
use app\assets\person\AppAssetFinancePayment;
AppAssetFinancePayment::register($this);
?>

<title>Bill Coderepo - Finance Payment</title>
<div class="panel panel-warning">
    <!-- panel heading -->
    <div class="panel-heading">
        <h2>Finance Payment</h2>
    </div>
    <!-- panel body -->
    <div class="panel-body">
        <div class="row">
            <form action="#" method="get" id="formSearch" class="form-inline">
                <div class="col-sm-10 col-md-10 col-lg-10">
                    日期: <input type="text" class="form-control form_date bill-ime-disabled"
                               id="payment_date" name="payment_date"/>
                    父级分类：<select name="category_parent_id" id="category_parent_id" class="form-control"></select>
                    <button class="btn btn-primary" type="submit" id="btn_search">
                        <span class="glyphicon glyphicon-search"></span>
                        <span>搜索</span>
                    </button>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <button class="btn btn-success" type="button" id="btn_add">
                        <span class="glyphicon glyphicon-plus"></span>
                        <span>新增</span>
                    </button>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                </div>
                <div class="col-sm-2 col-md-2 col-lg-2 text-right">
                    <select name="page_length" id="page_length" class="form-control">
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="75">75</option>
                        <option value="100">100</option>
                    </select>
                    &nbsp;<label>每页</label>
                </div>
                <input type="hidden" id="current_page" name="current_page"/>
            </form>
        </div>
        <hr>

        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <table id="tbl" class="table table-striped table-bordered bill_table text-center">
                    <thead>
                    <tr>
                        <td>序号</td>
                        <td>日期</td>
                        <td>消费金额</td>
                        <td>分类</td>
                        <td>备注</td>
                        <td>更新时间</td>
                        <td>操作</td>
                    </tr>
                    </thead>
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
</div>

<!-- modal -->
<div id="FinancePaymentModal" class="modal fade">
    <div class="modal-dialog bill_modal_md">
        <div class="modal-content">
            <div class="modal-header">
                <span></span>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <form id="FinancePaymentForm" action="#" method="post" enctype="multipart/form-data" class="form-inline">
                <div class="modal-body">
                    <div class="input-group">
                        <span class="input-group-addon">日期：</span>
                        <input type="text"
                               name="finance_payment_payment_date"
                               id="finance_payment_payment_date"
                               class="form-control form_date bill-ime-disabled"
                            />
                    </div>
                    <br/>

                    <div class="input-group">
                        <span class="input-group-addon">金额：</span>
                        <input type="text"
                               name="finance_payment_payment"
                               id="finance_payment_payment"
                               class="form-control bill-ime-disabled"
                            />
                    </div>
                    <br/>

                    <div class="input-group">
                        <span class="input-group-addon">分类：</span>
                        <select name="finance_payment_fcid[]" id="finance_payment_fcid" class="form-control"
                                multiple="multiple">
                        </select>
                    </div>
                    <br/>

                    <div class="input-group">
                        <span class="input-group-addon">备注：</span>
                        <textarea id="finance_payment_intro" name="finance_payment_intro"
                                  class="bill_textarea_full"></textarea>
                    </div>
                    <br/>
                    <input type="hidden" id="finance_payment_fpid" name="finance_payment_fpid"/>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="btn_submit_finance_payment">提交</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">关闭</button>
                </div>
            </form>
        </div>
    </div>
</div>
