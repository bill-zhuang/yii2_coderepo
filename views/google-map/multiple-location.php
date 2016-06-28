<?php
use app\assets\AppAssetMultipleLocation;
AppAssetMultipleLocation::register($this);
?>

<title>Google Map-Multiple Markers with not cluster & cluster - Bill Coderepo</title>
<div class="panel panel-warning">
    <!-- panel heading -->
    <div class="panel-heading">
        <h2>Multiple Markers with not cluster & cluster</h2>
    </div>
    <!-- panel body -->
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div id="map_canvas_nocluster" class="bill-google-map-canvas"></div>
                <div id="map_canvas_cluster" class="bill-google-map-canvas bill-margin-left"></div>
            </div>
        </div>
    </div>
    <!-- panel footer -->
    <!--<div class="panel-footer">
    </div>-->
</div>
