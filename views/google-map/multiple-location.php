<?php
use app\assets\AppAssetMultipleLocation;
AppAssetMultipleLocation::register($this);
Yii::$app->view->registerJs('var js_data = ' . json_encode($js_data) . ';', \yii\web\View::POS_END);
?>

<div class="panel panel-warning">
    <!-- panel heading -->
    <div class="panel-heading">
        <h2>Multiple Location</h2>
    </div>
    <!-- panel body -->
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div id="map_canvas_no_cluster" class="bill-google-map-canvas"></div>
                <div id="map_canvas_cluster" class="bill-google-map-canvas bill-margin-left"></div>
            </div>
        </div><hr>
    </div>
</div>
