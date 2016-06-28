<?php
use app\assets\AppAssetBackendAcl;
AppAssetBackendAcl::register($this);
?>

<title>Backend Acl - Bill Coderepo</title>
<div class="panel panel-warning">
    <!-- panel heading -->
    <div class="panel-heading">
        <h2>Backend Acl</h2>
    </div>
    <!-- panel body -->
    <div class="panel-body">
        <div class="row">
            <form action="#" method="get" id="formSearch" class="form-inline">
                <div class="col-sm-10 col-md-10 col-lg-10">
                    关键字: <input type="text" class="form-control" id="keyword" name="keyword"/>
                    <button class="btn btn-primary" type="submit" id="btn_search">
                        <span class="glyphicon glyphicon-search"></span>
                        <span>搜索</span>
                    </button>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <button class="btn btn-success" type="button" id="btn_load_acl">
                        <span class="glyphicon glyphicon-refresh"></span>
                        <span>加载ACL</span>
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
                        <td>name</td>
                        <td>module</td>
                        <td>controller</td>
                        <td>action</td>
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
<div id="modalBackendAcl" class="modal fade">
    <div class="modal-dialog bill_modal_md">
        <div class="modal-content">
            <div class="modal-header">
                <span>修改</span>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <form id="formBackendAcl" action="#" method="post" enctype="multipart/form-data" class="form-inline">
                <div class="modal-body">
                    <div class="input-group">
                        <span class="input-group-addon">name：</span>
                        <input type="text" name="backend_acl_name" id="backend_acl_name" class="form-control"/>
                    </div>
                    <br/>

                    <div class="input-group">
                        <span class="input-group-addon">module：</span>
                        <input type="text" name="backend_acl_module" id="backend_acl_module" class="form-control"
                               readonly/>
                    </div>
                    <br/>

                    <div class="input-group">
                        <span class="input-group-addon">controller：</span>
                        <input type="text" name="backend_acl_controller" id="backend_acl_controller"
                               class="form-control" readonly/>
                    </div>
                    <br/>

                    <div class="input-group">
                        <span class="input-group-addon">action：</span>
                        <input type="text" name="backend_acl_action" id="backend_acl_action" class="form-control"
                               readonly/>
                    </div>
                    <br/>
                    <input type="hidden" id="backend_acl_baid" name="backend_acl_baid"/>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="btn_submit_backend_acl">提交</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">关闭</button>
                </div>
            </form>
        </div>
    </div>
</div>
