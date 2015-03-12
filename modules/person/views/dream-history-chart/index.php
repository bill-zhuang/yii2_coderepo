<?php
use app\assets\person\AppAssetDreamHistoryChart;
AppAssetDreamHistoryChart::register($this);
Yii::$app->view->registerJs('var js_data = ' . json_encode($js_data) . ';', \yii\web\View::POS_END);
?>

<div class="panel panel-warning">
    <!-- panel heading -->
    <div class="panel-heading">
        <h2>Dream History Chart</h2>
    </div>
    <!-- panel body -->
    <div class="panel-body">
        <div class="row">
            <form class="form-inline">
            </form>
        </div><hr>
    </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <span class="bill_font_bold_green">All dream history data by day</span>
                <div class="bill-chart-canvas_all">
                    <canvas id="dream_history_line_chart_all"></canvas>
                </div>
            </div>

            <span class="bill-margin-left bill_font_bold_green">All dream history data by month</span>
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="bill-chart-canvas">
                    <canvas id="dream_history_line_chart"></canvas>
                </div>
                <div class="bill-chart-canvas bill-margin-left">
                    <canvas id="dream_history_bar_chart"></canvas>
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="bill-chart-canvas">
                    <canvas id="dream_history_pie_chart"></canvas>
                </div>
                <div class="bill-chart-canvas bill-margin-left">
                    <canvas id="dream_history_doughnut_chart"></canvas>
                </div>
            </div>
        </div>
        </div>
    </div>
    <!-- panel footer -->
    <!--<div class="panel-footer">
    </div>-->
</div>
