<?php
use app\assets\person\AppAssetGrainRecycleHistoryChart;
AppAssetGrainRecycleHistoryChart::register($this);
?>

<title>Grain Recycle History Chart - Bill Coderepo</title>
<div class="panel panel-warning">
    <!-- panel heading -->
    <div class="panel-heading">
        <h2>Grain Recycle History Chart</h2>
    </div>
    <!-- panel body -->
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12 col-md-10 col-lg-12">
                <form action="#" method="get" id="formSearchDay" class="form-inline">
                    Start Date: <input type="text" class="form-control form_date bill-ime-disabled"
                                       id="day_start_date" name="day_start_date"/>
                    End Date: <input type="text" class="form-control form_date bill-ime-disabled"
                                     id="day_end_date" name="day_end_date"/>
                    <button class="btn btn-primary" type="submit" id="btn_search_day">
                        <span class="glyphicon glyphicon-search"></span>
                        <span>Search</span>
                    </button>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                </form>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="bill-chart-canvas_all" id="grain_recycle_history_line_chart_all"></div>
            </div>

            <div class="col-sm-12 col-md-10 col-lg-12">
                <hr>
                <form action="#" method="get" id="formSearchMonth" class="form-inline">
                    Start Date: <input type="text" class="form-control form_date bill-ime-disabled"
                                       id="month_start_date" name="month_start_date"/>
                    End Date: <input type="text" class="form-control form_date bill-ime-disabled"
                                     id="month_end_date" name="month_end_date"/>
                    <button class="btn btn-primary" type="submit" id="btn_search_month">
                        <span class="glyphicon glyphicon-search"></span>
                        <span>Search</span>
                    </button>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                </form>
            </div>

            <div class="col-sm-12 col-md-12 col-lg-12">
                <div id="grain_recycle_history_month_chart"></div>
            </div>
        </div>
    </div>
    <!-- panel footer -->
    <!--<div class="panel-footer">
    </div>-->
</div>