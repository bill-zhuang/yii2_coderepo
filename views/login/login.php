<?php
use app\assets\AppAssetLogin;
AppAssetLogin::register($this);
Yii::$app->view->registerJs('var content = ' . json_encode($content) . ';', \yii\web\View::POS_END);
?>

<div class="container">
    <div class="row">
        <div class="col-sm-4 col-md-4 col-lg-4"></div>
        <div class="col-sm-4 col-md-4 col-lg-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">请登录</h3>
                </div>
                <div class="panel-body">
                    <form role="form" action="/index.php/login/login" method="post" id="login">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="帐号" name="Auth[username]" id="username" autofocus/>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" placeholder="密码" name="Auth[password]" id="password" value=""/>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input name="Auth[rememberMe]" type="checkbox"/>
                                <span>记住我</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-lg btn-success btn-block" type="submit">登录</button><br/>
                            <button class="btn btn-lg btn-danger btn-block" type="reset" >重置</button>
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
