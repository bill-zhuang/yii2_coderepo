<?php
use app\assets\person\AppAssetFinanceHistory;
AppAssetFinanceHistory::register($this);
Yii::$app->view->registerJs('var js_data = ' . json_encode($js_data) . ';', \yii\web\View::POS_END);
?>

<div class="panel panel-warning">
    <!-- panel heading -->
    <div class="panel-heading">
        <h2>Finance History</h2>
    </div>
    <!-- panel body -->
    <div class="panel-body">
        <div class="row">
        </div><hr>
    </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <span class="bill_font_bold_green">All payment history data by day</span>
                <div class="bill-chart-canvas_all">
                    <canvas id="payment_history_line_chart_all"></canvas>
                </div>
            </div>

            <span class="bill-margin-left bill_font_bold_green">All category payment history data in last 30 days</span>
            <span id="month_spent"></span>
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="bill-chart-canvas">
                    <canvas id="month_category_payment_history_line_chart"></canvas>
                </div>
                <div class="bill-chart-canvas bill-margin-left">
                    <canvas id="month_category_payment_history_bar_chart"></canvas>
                </div>
            </div>

            <br/>

            <span class="bill-margin-left bill_font_bold_green">All payment history data by month</span>
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="bill-chart-canvas">
                    <canvas id="payment_history_line_chart"></canvas>
                </div>
                <div class="bill-chart-canvas bill-margin-left">
                    <canvas id="payment_history_bar_chart"></canvas>
                </div>
            </div>

            <br/>

            <span class="bill-margin-left bill_font_bold_green">All category payment history data in last one year</span>
            <span id="year_spent"></span>
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="bill-chart-canvas">
                    <canvas id="category_payment_history_line_chart"></canvas>
                </div>
                <div class="bill-chart-canvas bill-margin-left">
                    <canvas id="category_payment_history_bar_chart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- panel footer -->
    <!--<div class="panel-footer">
    </div>-->
</div>
