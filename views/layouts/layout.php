<?php
/* @var $content string */
use yii\helpers\Html;
use app\library\bill\Constant;
use app\assets\AppAssetLayout;
use app\assets\plugins\AdminLTEAsset;
AppAssetLayout::register($this);
$bundle = AdminLTEAsset::register($this);
?>

<!-- read js file in AppAssetLayout.php -->
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta charset="utf-8">
        <!-- read css file in AppAssetLayout.php -->
        <?php Html::csrfMetaTags() ?>
        <?php $this->head() ?>
    </head>

    <!-- ADD THE CLASS fixed TO GET A FIXED HEADER AND SIDEBAR LAYOUT -->
    <!-- the fixed layout is not compatible with sidebar-mini -->
    <body class="hold-transition skin-blue fixed sidebar-mini">
    <?php $this->beginBody() ?>
    <!-- Site wrapper -->
    <div class="wrapper">

        <header class="main-header">
            <!-- Logo -->
            <a href="javascript:void(0)" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b>B</b></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>Bill</b></span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li>
                            <a href="/login/logout" class="btn"><span>Log out</span></a>
                        </li>
                        <!-- Control Sidebar Toggle Button -->
                        <li>
                            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i><span> </span></a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <!-- =============================================== -->

        <!-- Left side column. contains the sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="<?php echo $bundle->baseUrl . '/dist/img/avatar5.png'; ?>" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p>Welcome, <?php echo Yii::$app->user->identity->name; ?></p>
                        <a href="javascript:void(0)"><i class="fa fa-circle text-success"></i>Online</a>
                    </div>
                </div>
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu">
                    <li class="header">MAIN NAVIGATION</li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-user"></i> <span>Person center</span> <i
                                class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="/index.php/main/modify-password"><i class="fa fa-circle-o"></i>Modify Password</a></li>
                            <li><a href="/index.php/login/logout"><i class="fa fa-circle-o"></i>Logout</a></li>
                        </ul>
                    </li>
                    <?php if (Yii::$app->user->identity->name == Constant::ADMIN_NAME) { ?>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-dashboard"></i> <span>Backend Panel</span> <i
                                    class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="/index.php/backend-user/index"><i class="fa fa-circle-o"></i>Backend User</a></li>
                                <li><a href="/index.php/backend-role/index"><i class="fa fa-circle-o"></i>Backend Role</a></li>
                                <li><a href="/index.php/backend-acl/index"><i class="fa fa-circle-o"></i>Access List</a></li>
                                <li><a href="/index.php/backend-log/index"><i class="fa fa-circle-o"></i>Backend Log</a></li>
                            </ul>
                        </li>
                    <?php } ?>
                    <li class="treeview">
                        <a href="#">
                            <i class="glyphicon glyphicon-leaf"></i> <span>Variety</span> <i
                                class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="/index.php/index/index"><i class="fa fa-circle-o"></i>Other</a></li>
                            <li><a href="/index.php/color-hex/index"><i class="fa fa-circle-o"></i>Color Hex</a></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#">
                            <i class="glyphicon glyphicon-map-marker"></i> <span>Map</span> <i
                                class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="/index.php/google-map"><i class="fa fa-circle-o"></i>Google Map</a></li>
                            <li><a href="/index.php/google-map/multiple-location"><i class="fa fa-circle-o"></i>Multiple Markes</a>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#">
                            <i class="glyphicon glyphicon-stats"></i> <span>History</span> <i
                                class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="/index.php/person/grain-recycle-history/index"><i class="fa fa-circle-o"></i>Grain Recycle
                                    History</a></li>
                            <li><a href="/index.php/person/grain-recycle-history-chart/index"><i class="fa fa-circle-o"></i>Grain
                                    Recycle History Chart</a></li>
                            <li><a href="/index.php/person/eject-history/index"><i class="fa fa-circle-o"></i>Eject History</a></li>
                            <li><a href="/index.php/person/eject-history-chart/index"><i class="fa fa-circle-o"></i>Eject History
                                    Chart</a></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#">
                            <i class="glyphicon glyphicon-usd"></i> <span>Finance</span> <i
                                class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="/index.php/person/finance-category/index"><i class="fa fa-circle-o"></i>Finance Category</a>
                            </li>
                            <li><a href="/index.php/person/finance-payment/index"><i class="fa fa-circle-o"></i>Finance Payment</a>
                            </li>
                            <li><a href="/index.php/person/finance-history/index"><i class="fa fa-circle-o"></i>Finance History</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- below is for show action view file. -->
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <section class="content">
                <?php echo $content; ?>
            </section>
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer text-center">
            <strong><a href="mailto:zstu_bill@yahoo.com">Send mail</a>.</strong>
            Released under <a href="http://apache.org/licenses/LICENSE-2.0.html" target="_blank">Apache 2.0</a>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Create the tabs -->
            <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!-- Home tab content -->
                <div class="tab-pane" id="control-sidebar-home-tab"></div>
                <!-- /.tab-pane -->
                <!-- Stats tab content -->
                <div class="tab-pane" id="control-sidebar-stats-tab"></div>
                <!-- /.tab-pane -->
            </div>
        </aside>
        <!-- /.control-sidebar -->
        <!-- Add the sidebar's background. This div must be placed
             immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
        <div class="scroll-up" id="go-top">
            <i class="fa fa-chevron-up scroll-icon"></i>
        </div>
    </div>
    <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
