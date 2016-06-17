<?php
use app\assets\AppAssetLogin;
AppAssetLogin::register($this);
?>

<div class="container">
    <div class="row">
        <div class="col-sm-4 col-md-4 col-lg-4"></div>
        <div class="col-sm-4 col-md-4 col-lg-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">帐号登录</h3>
                </div>
                <div class="panel-body">
                    <form role="form" action="#" method="post" id="formLogin">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="帐号" name="username" id="username"
                                   autofocus/>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control bill-ime-disabled" placeholder="密码" name="password" id="password"
                                   value=""/>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input name="remember" id="remember" type="checkbox" value="Remember Me"/>
                                <span>记住帐号</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-lg btn-success btn-block" type="submit">登录</button>
                        </div>
                    </form>
                </div>
                <!--<div class="panel-footer">
                </div>-->
            </div>
        </div>
        <div class="col-sm-4 col-md-4 col-lg-4"></div>
    </div>
</div>
