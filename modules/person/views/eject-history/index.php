<?php
use app\assets\person\AppAssetEjectHistory;
AppAssetEjectHistory::register($this);
?>

<title>Eject History - Bill Coderepo</title>
<div class="panel panel-warning">
    <!-- panel heading -->
    <div class="panel-heading">
        <h2>Eject History</h2>
    </div>
    <!-- panel body -->
    <div class="panel-body">
        <div class="row">
            <form action="#" method="get" id="formSearch" class="form-inline">
                <div class="col-sm-10 col-md-10 col-lg-10">
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
                <input type="hidden" id="tab_type" name="tab_type"/>
            </form>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <nav class="navbar nav-tabs" role="navigation">
                    <div>
                        <ul class="nav nav-tabs" id="ul_tab_type">
                            <li id="li_tab_type_0" class="active"><a href="#">All</a></li>
                            <li id="li_tab_type_1"><a href="#">Dream</a></li>
                            <li id="li_tab_type_2"><a href="#">Bad</a></li>
                        </ul>
                    </div>
                </nav>
                <table id="tbl" class="table table-striped table-bordered bill_table text-center">
                    <thead>
                    <tr>
                        <td>序号</td>
                        <td>日期</td>
                        <td>次数</td>
                        <td>类型</td>
                        <td>创建时间</td>
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
            <div id="div_pagination" class="col-sm-12 col-md-12 col-lg-12 text-right">
            </div>
        </div>
    </div>
</div>
<!-- modal -->
<div id="modalEjectHistory" class="modal fade">
    <div class="modal-dialog bill_modal_md">
        <div class="modal-content">
            <div class="modal-header">
                <span>新增/修改</span>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <form id="formEjectHistory" action="#" method="post" enctype="multipart/form-data" class="form-inline">
                <div class="modal-body">
                    <div class="input-group">
                        <span class="input-group-addon">happen_date：</span>
                        <input type="text" name="eject_history_happen_date" id="eject_history_happen_date"
                               class="form-control form_date"/>
                    </div>
                    <br/>

                    <div class="input-group">
                        <span class="input-group-addon">count：</span>
                        <input type="text" name="eject_history_count" id="eject_history_count"
                               class="form-control bill-ime-disabled" value="1"/>
                    </div>
                    <br/>

                    <div class="input-group">
                        <span class="input-group-addon">type：</span>
                        <select name="eject_history_type" id="eject_history_type" class="form-control">
                            <option value="1">Dream</option>
                            <option value="2">Bad</option>
                        </select>
                    </div>
                    <br/>
                    <input type="hidden" id="eject_history_ehid" name="eject_history_ehid"/>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="btn_submit_eject_history">提交</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">关闭</button>
                </div>
            </form>
        </div>
    </div>
</div>
