<?php
use app\assets\AppAssetModifyPassword;
AppAssetModifyPassword::register($this);
?>

<div class="panel panel-warning">
    <!-- panel heading -->
    <div class="panel-heading">
        <h2>修改密码</h2>
    </div>
    <!-- panel body -->
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-1 col-md-1 col-lg-1"></div>
            <div class="col-sm-4 col-md-4 col-lg-4 text-left">
                <form name="formModifyPassword" id="formModifyPassword" action="/index.php/main/modify-password" method="post" class="form-inline">
                    <div class="form-group">
                        <span>原密码：</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="password" name="old_password" id="old_password" class="form-control" autofocus/>
                    </div>
                    <br/><br/>
                    <div class="form-group">
                        <span>新密码：</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="password" name="new_password" id="new_password" class="form-control"/>

                    </div>
                    <br/><br/>
                    <div class="form-group">
                        <span>新密码确认：</span>
                        <input type="password" name="new_password_repeat" id="new_password_repeat" class="form-control"/>
                    </div>
                    <br/><br/>
                    <div class="form-group">
                        <button type="button" class="btn btn-success" id="btn_modify_password">确认修改</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- panel footer -->
    <!--<div class="panel-footer">
    </div>-->
</div>
