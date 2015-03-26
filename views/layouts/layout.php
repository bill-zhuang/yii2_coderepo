<?php
/* @var $content string */
use yii\helpers\Html;
use app\assets\AppAssetLayout;
AppAssetLayout::register($this);
?>

<!-- read js file in AppAssetLayout.php -->
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta charset="utf-8">
        <title>Bill Coderepo</title>
        <!-- read css file in AppAssetLayout.php -->
        <?php Html::csrfMetaTags() ?>
        <?php $this->head() ?>
    </head>

    <body>
    <?php $this->beginBody() ?>
        <div class="navbar navbar-default navbar-fixed-top">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Bill</a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="dropdown active">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="glyphicon glyphicon-user"></span>Person center
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="/index.php/main/modify-password">
                                    <span class="glyphicon glyphicon-wrench"></span>Modify Password
                                </a>
                            </li>
                            <li>
                                <a href="/index.php/login/logout">
                                    <span class="glyphicon glyphicon-log-out"></span>Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown active">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            Variety<b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="/index.php/index/index">Other</a></li>
                            <li><a href="/index.php/color-hex/index">Color Hex</a></li>
                            <li><a href="/index.php/gii">Gii</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="glyphicon glyphicon-map-marker"></span>
                            Map<b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="/index.php/google-map/index">Google Map</a></li>
                            <li><a href="/index.php/google-map/multiple-location">Multiple Markes</a></li>
                            <li><a href="#">Baidu Map</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            History<b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="/index.php/person/dream-history/index">Dream History</a></li>
                            <li><a href="/index.php/person/dream-history-chart/index">Dream History Chart</a></li>
                            <li><a href="/index.php/person/bad-history/index">Bad History</a></li>
                            <li><a href="/index.php/person/bad-history-chart/index">Bad History Chart</a></li>
                            <li class="nav-divider"></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="glyphicon glyphicon-usd"></span>
                            Finance<b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="/index.php/person/finance-category/index">Finance Category</a></li>
                            <li><a href="/index.php/person/finance-payment/index">Finance Payment</a></li>
                            <li><a href="/index.php/person/finance-history/index">Finance History</a></li>
                            <li class="nav-divider"></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row bill-layout-width">
            <div class="col-sm-3 col-md-3 col-lg-2 well bill-sidebar" id="main">
                <ul class="nav nav-sidebar">
                </ul>
            </div>
            <!-- below is for show action view file. -->
            <div class="col-sm-9 col-md-9 col-lg-10 bill-content">
                <?php echo $content;?>
            </div>
        </div>

        <div class="footer bill-footer">
            <div class="container text-center">
                <p class="text-muted">
                    <a href="mailto:zstu_bill@yahoo.com">Send mail</a>.
                    Released under <a href="http://apache.org/licenses/LICENSE-2.0.html" target="_blank">Apache 2.0</a>
                </p>
            </div>
        </div>
    <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
