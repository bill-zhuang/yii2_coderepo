<?php
use app\assets\person\AppAssetBadHistoryChart;
AppAssetBadHistoryChart::register($this);
Yii::$app->view->registerJs('var js_data = ' . json_encode($js_data) . ';', \yii\web\View::POS_END);
?>

<div class="panel panel-warning">
    <!-- panel heading -->
    <div class="panel-heading">
        <h2>Bad History Chart</h2>
    </div>
    <!-- panel body -->
    <div class="panel-body">
        <div class="row">
            <form class="form-inline">
            </form>
        </div><hr>

        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <span class="bill_font_bold_green">All bad history data by day</span>
                <div class="bill-chart-canvas_all">
                    <canvas id="bad_history_line_chart_all"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- panel footer -->
    <!--<div class="panel-footer">
    </div>-->
</div>
