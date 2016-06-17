<?php
use app\assets\AppAssetBackendUser;
AppAssetBackendUser::register($this);
?>

<title>Bill Coderepo - Backend User</title>
<div class="panel panel-warning">
    <!-- panel heading -->
    <div class="panel-heading">
        <h2>Backend User</h2>
    </div>
    <!-- panel body -->
    <div class="panel-body">
        <div class="row">
            <form action="#" method="get" id="formSearch" class="form-inline">
                <div class="col-sm-10 col-md-10 col-lg-10">
                    用户名: <input type="text" class="form-control" id="keyword" name="keyword"/>
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
                </div>
                <input type="hidden" id="current_page" name="current_page"/>
                <input type="hidden" id="tab_type" name="tab_type" value="1"/>
            </form>
        </div><hr>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <nav class="navbar nav-tabs" role="navigation">
                    <div>
                        <ul class="nav nav-tabs" id="ul_tab_type">
                            <li id="li_tab_type_1" class="active"><a href="#">正常用户</a></li>
                            <li id="li_tab_type_0"><a href="#">已删除</a></li>
                        </ul>
                    </div>
                </nav>
                <table id="tbl" class="table table-striped table-bordered bill_table text-center">
                    <thead>
                    <tr>
                        <td>序号</td>
                        <td>用户名</td>
                        <td>角色</td>
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
<div id="modalBackendUser" class="modal fade" >
    <div class="modal-dialog bill_modal_md" >
        <div class="modal-content">
            <div class="modal-header">
                <span>新增/修改</span>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <form id="formBackendUser" action="#" method="post" enctype="multipart/form-data" class="form-inline">
                <div class="modal-body">
                    <div class="input-group">
                        <span class="input-group-addon">用户名：</span>
                        <input type="text" name="backend_user_name" id="backend_user_name" class="form-control"/>
                    </div><br />
                    <div class="input-group">
                        <span class="input-group-addon">角色：</span>
                        <select name="backend_user_brid" id="backend_user_brid" class="form-control">
                        </select>
                    </div><br />
                    <input type="hidden" id="backend_user_buid" name="backend_user_buid"/>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="btn_submit_backend_user">提交</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">关闭</button>
                </div>
            </form>
        </div>
    </div>
</div>